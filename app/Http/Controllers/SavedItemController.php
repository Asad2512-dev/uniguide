<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Program;
use App\Models\Scholarship;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavedItemController extends Controller
{
    public function toggle(Request $request)
    {
        $data = $request->validate(['item_type' => 'required|in:university,program,scholarship,admission', 'item_slug' => 'required|string|max:255']);
        $model = match ($data['item_type']) {
            'university' => University::class,'program' => Program::class,'scholarship' => Scholarship::class,'admission' => Admission::class
        };
        $record = $model::where('slug', $data['item_slug'])->firstOrFail();
        DB::transaction(function () use ($request, $data, $record) {
            User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $query = $request->user()->savedItems()->where('item_type', $data['item_type'])->where('item_id', $record->id);
            if ($query->exists()) {
                $query->delete();
            } else {
                $request->user()->savedItems()->create(['item_type' => $data['item_type'], 'item_id' => $record->id, 'item_slug' => $record->slug]);
            }
        });

        return back()->with('toast_success', 'Your saved collection has been updated.');
    }
}
