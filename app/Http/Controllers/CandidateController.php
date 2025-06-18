<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function create()
    {
        return view('admin.candidates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'organization_name' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        Candidate::create($request->all());

        return redirect()->route('admin.candidates.index')->with('success', 'Нэр дэвшигч амжилттай нэмэгдлээ.');
    }

    public function show(Candidate $candidate)
    {
        return view('admin.candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate)
    {
        return view('admin.candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'organization_name' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $candidate->update($request->all());

        return redirect()->route('admin.candidates.index')->with('success', 'Нэр дэвшигч амжилттай шинэчлэгдлээ.');
    }

    public function destroy(Candidate $candidate)
    {
        $candidate->delete();

        return redirect()->route('admin.candidates.index')->with('success', 'Нэр дэвшигч устгагдлаа.');
    }
}
