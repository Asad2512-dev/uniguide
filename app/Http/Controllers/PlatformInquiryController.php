<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Notification;
use Illuminate\Http\Request;

class PlatformInquiryController extends Controller
{
    public function index()
    {
        return view('pages.national-admin.inquiries', ['inquiries' => Inquiry::where('institution_slug', 'platform')->latest()->paginate(20)]);
    }

    public function reply(Request $request, string $id)
    {
        $data = $request->validate(['reply' => 'required|string|min:5|max:5000']);
        $inquiry = Inquiry::where('institution_slug', 'platform')->findOrFail($id);
        $inquiry->update(['reply_content' => $data['reply'], 'status' => 'resolved']);
        if ($inquiry->user_id) {
            Notification::create(['user_id' => $inquiry->user_id, 'title' => 'Your platform inquiry has a reply', 'message' => $data['reply'], 'category' => 'system', 'dispatched_at' => now()]);
        }

        return back()->with('toast_success', 'Reply saved in the platform.');
    }
}
