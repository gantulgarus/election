<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $results = Candidate::withCount('votes')
            ->where('status', 'approved')
            ->orderByDesc('votes_count')
            ->get();

        return view('home', compact('results'));
    }
}
