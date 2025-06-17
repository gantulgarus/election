<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Vote;
use App\Models\Setting;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function vote(Candidate $candidate)
    {
        $setting = Setting::first();

        if (!$setting || now()->lt($setting->voting_start) || now()->gt($setting->voting_end)) {
            return redirect()->back()->with('error', 'Санал хураалт эхлээгүй байна.');
        }

        $user = Auth::user();

        if ($user->votes()->where('candidate_id', $candidate->id)->exists()) {
            return back()->with('error', 'Та энэ нэр дэвшигчид аль хэдийн санал өгсөн байна.');
        }

        if ($user->votes()->count() >= 30) {
            return back()->with('error', 'Та зөвхөн 30 нэр дэвшигчид санал өгч чадна.');
        }

        Vote::create([
            'user_id' => $user->id,
            'candidate_id' => $candidate->id,
        ]);

        return back()->with('success', 'Санал амжилттай өглөө.');
    }

    public function reset()
    {
        // Саналын бүртгэлийг устгана
        Vote::truncate();

        return back()->with('status', 'Санал хураалт амжилттай дахин эхлүүллээ.');
    }

    public function startVoting()
    {
        Setting::updateOrCreate(['id' => 1], [
            'voting_start' => now(),
            'voting_end' => now()->addHours(2), // жишээ нь 2 цагийн санал хураалт
        ]);

        return redirect()->back()->with('success', 'Санал хураалт эхэллээ!');
    }

    public function endVoting()
    {
        $setting = Setting::first();
        if ($setting) {
            $setting->update(['voting_end' => Carbon::now()]);
            return back()->with('success', 'Санал хураалт амжилттай дуусгалаа.');
        }

        return back()->with('error', 'Тохиргоо олдсонгүй.');
    }
}
