<?php

use App\Models\Notification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:publish-due', function () {
    $count = Notification::where('status', 'scheduled')->where('dispatched_at', '<=', now())->update(['status' => 'active']);
    $this->info($count.' notifications published.');
})->purpose('Publish scheduled in-app reminders when due');

Schedule::command('notifications:publish-due')->everyMinute();
