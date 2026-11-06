<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $title,
        public string $message,
        public string $category = 'general',
        public ?int $userId = null
    ) {}

    public function handle(): void
    {
        if ($this->userId) {
            Notification::create([
                'user_id' => $this->userId,
                'title' => $this->title,
                'message' => $this->message,
                'category' => $this->category,
                'dispatched_at' => now(),
                'status' => 'active',
            ]);
        } else {
            // Broadcast to all active students
            $students = User::where('role', 'student')->get();
            foreach ($students as $student) {
                Notification::create([
                    'user_id' => $student->id,
                    'title' => $this->title,
                    'message' => $this->message,
                    'category' => $this->category,
                    'dispatched_at' => now(),
                    'status' => 'active',
                ]);
            }
        }
    }
}
