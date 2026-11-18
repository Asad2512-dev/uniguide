<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.notifications.index', [
            'notifications' => $request->user()->notifications()->where('status', 'active')->latest()->paginate(20),
        ]);
    }

    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->where('status', 'active')->findOrFail($id);
        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return back()->with('toast_success', 'Notification marked as read.');
    }

    public function readAll(Request $request)
    {
        $request->user()->notifications()->where('status', 'active')->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('toast_success', 'All notifications marked as read.');
    }
}
