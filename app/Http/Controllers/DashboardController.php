<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // зөвхөн approved нэр дэвшигчдийг авах
        $candidates = Candidate::where('status', 'approved')->get();

        // хэрэглэгчийн өгсөн саналын мэдээлэл
        $userVotes = Auth::user()->votes()->pluck('candidate_id')->toArray();
        $voteCount = count($userVotes);

        $setting = Setting::first();

        // Огноог Carbon объект болгож дамжуулна
        $votingStart = $setting ? \Carbon\Carbon::parse($setting->voting_start) : null;
        $votingEnd = $setting ? \Carbon\Carbon::parse($setting->voting_end) : null;

        return view('dashboard', compact('candidates', 'userVotes', 'voteCount', 'votingStart', 'votingEnd'));
    }
}
