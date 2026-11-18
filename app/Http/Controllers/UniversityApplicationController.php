<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use App\Models\University;
use App\Models\UniversityApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniversityApplicationController extends Controller
{
    public function create()
    {
        return view('pages.universities.join');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200', 'website' => 'required|url:http,https|max:255',
            'city' => 'required|string|max:100', 'province' => 'required|in:punjab,ict',
            'sector' => 'required|in:public,private', 'contact_name' => 'required|string|max:150',
            'email' => 'required|email|max:255', 'phone' => 'required|string|max:30',
            'description' => 'required|string|min:30|max:5000', 'consent' => 'accepted',
        ]);
        unset($data['consent']);
        $application = UniversityApplication::create($data + ['reference' => 'UG-'.Str::upper(Str::random(10))]);

        return redirect()->route('universities.join')->with('application_reference', $application->reference);
    }

    public function index()
    {
        return view('pages.national-admin.applications', ['applications' => UniversityApplication::latest()->paginate(15)]);
    }

    public function review(Request $request, UniversityApplication $application)
    {
        $data = $request->validate(['decision' => 'required|in:approved,rejected']);
        DB::transaction(function () use ($application, $data, $request) {
            $application = UniversityApplication::whereKey($application->id)->lockForUpdate()->firstOrFail();
            abort_unless($application->status === 'pending', 409, 'This application has already been reviewed.');
            if ($data['decision'] === 'approved') {
                $existing = University::withoutGlobalScopes()->where('website', $application->website)->first();
                $province = Province::firstOrCreate(['slug' => $application->province], ['name' => $application->province === 'ict' ? 'Islamabad Capital Territory' : 'Punjab']);
                $city = City::firstOrCreate(['province_id' => $province->id, 'slug' => Str::slug($application->city)], ['name' => $application->city]);
                $university = $existing ?? University::create([
                    'city_id' => $city->id,
                    'slug' => Str::slug($application->name).'-'.strtolower($application->reference),
                    'name' => $application->name, 'website' => $application->website,
                    'city' => $application->city, 'province' => $application->province,
                    'province_label' => $application->province === 'ict' ? 'Islamabad Capital Territory' : 'Punjab',
                    'sector' => $application->sector, 'email' => $application->email,
                    'phone' => $application->phone, 'description' => $application->description,
                    'status' => 'upcoming', 'verification_status' => 'pending',
                ]);
                $application->university_id = $university->id;
            }
            $application->fill(['status' => $data['decision'], 'reviewed_by' => $request->user()->id, 'reviewed_at' => now()])->save();
        });

        return back()->with('toast_success', $data['decision'] === 'approved' ? 'University approved and added to the directory.' : 'Application rejected.');
    }
}
