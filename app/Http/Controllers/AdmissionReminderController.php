<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdmissionReminderController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $admission = Admission::where('slug', $slug)->firstOrFail();
        if (! $admission->deadline_date || $admission->deadline_date->isPast()) {
            return back()->withErrors(['reminder' => 'A future recorded deadline is required for reminders.']);
        }
        $scheduled = 0;
        foreach ([7, 2] as $days) {
            $at = $admission->deadline_date->copy()->subDays($days);
            if ($at->isPast()) {
                continue;
            }
            $scheduled++;
            Notification::firstOrCreate(['user_id' => $request->user()->id, 'title' => $admission->title.' — '.$days.' day reminder', 'action_url' => route('admissions.show', $slug)], ['message' => 'The recorded application deadline is '.$admission->deadline_date->format('d M Y').'. Confirm current details with the institution.', 'category' => 'deadline', 'status' => 'scheduled', 'dispatched_at' => $at, 'channels' => ['in_app']]);
        }
        if (! $scheduled) {
            return back()->withErrors(['reminder' => 'No future reminder dates remain before this deadline.']);
        }

        return back()->with('toast_success', 'In-app reminders saved for upcoming reminder dates.');
    }
}
