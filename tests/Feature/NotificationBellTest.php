<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NotificationBellTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    public function test_bell_shows_only_current_users_notifications_and_real_unread_count(): void
    {
        foreach (['student', 'university_admin', 'university_staff', 'super_admin'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $other = User::factory()->create();
            $notification = Notification::create(['user_id' => $user->id, 'title' => 'Your update', 'message' => 'A real account update', 'status' => 'active']);
            Notification::create(['user_id' => $other->id, 'title' => 'Private other update', 'status' => 'active']);
            Notification::create(['user_id' => $user->id, 'title' => 'Inactive update', 'status' => 'inactive']);
            $this->actingAs($user);
            $html = Blade::render('<x-nav.notifications />');
            $this->assertStringContainsString('Notifications, 1 unread', $html);
            $this->assertStringContainsString('Your update', $html);
            $this->assertStringNotContainsString('Private other update', $html);
            $this->assertStringNotContainsString('Inactive update', $html);
            $this->post(route('notifications.read', $notification->id))->assertRedirect();
            $this->assertNotNull($notification->fresh()->read_at);
            $this->assertStringNotContainsString('data-notification-unread-indicator', Blade::render('<x-nav.notifications />'));
        }
    }

    public function test_mark_all_is_scoped_and_other_users_notifications_cannot_be_read(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $mine = Notification::create(['user_id' => $user->id, 'title' => 'Mine']);
        $theirs = Notification::create(['user_id' => $other->id, 'title' => 'Theirs']);
        $this->actingAs($user)->post(route('notifications.read', $theirs->id))->assertNotFound();
        $this->post(route('notifications.read_all'))->assertRedirect();
        $this->assertNotNull($mine->fresh()->read_at);
        $this->assertNull($theirs->fresh()->read_at);
        $this->get(route('notifications.index'))->assertOk()->assertSee('Mine')->assertDontSee('Theirs');
    }

    public function test_empty_state_guest_redirect_and_safe_links(): void
    {
        $this->get(route('notifications.index'))->assertRedirect(route('auth.login'));
        $this->post(route('notifications.read_all'))->assertRedirect(route('auth.login'));
        $this->actingAs(User::factory()->create());
        $html = Blade::render('<x-nav.notifications />');
        $this->assertStringContainsString('You’re all caught up', $html);
        $this->assertStringNotContainsString('data-notification-unread-indicator', $html);
        foreach (['javascript:alert(1)', '//example.com', '/\\example.com', 'https://example.com/account'] as $url) {
            $this->assertNull((new Notification(['action_url' => $url]))->safeActionUrl());
        }
        $this->assertSame('/student/tracker', (new Notification(['action_url' => '/student/tracker']))->safeActionUrl());
        $this->assertSame(url('/student/tracker'), (new Notification(['action_url' => url('/student/tracker')]))->safeActionUrl());
    }
}
