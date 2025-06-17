<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Candidate;

class AdminController extends Controller
{
    public function votes()
    {
        $results = Candidate::withCount('votes')
            ->where('status', 'approved')
            ->orderByDesc('votes_count')
            ->get();

        $setting = Setting::first();
        return view('admin.votes', compact('results', 'setting'));
    }
}
