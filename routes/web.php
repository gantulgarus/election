<?php

use App\Models\Candidate;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/vote/{candidate}', [VoteController::class, 'vote'])->name('vote');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::resource('candidates', CandidateController::class)->names('admin.candidates');

    Route::get('/votes', [AdminController::class, 'votes'])->name('admin.votes');
    Route::post('/admin/voting/reset', [VoteController::class, 'reset'])->middleware('auth', 'is_admin')->name('voting.reset');
    Route::post('/voting/start', [VoteController::class, 'startVoting'])->name('voting.start');
    Route::post('/voting/end', [VoteController::class, 'endVoting'])->name('voting.end');

    Route::resource('/admin/users', AdminAuthController::class)->names('admin.users');
    Route::get('/admin/users/{user}/votes', [AdminAuthController::class, 'userVotes'])->name('admin.users.votes');
});

Route::get('/admin/votes/live', function () {
    return Candidate::where('status', 'approved')
        ->withCount('votes')
        ->orderByDesc('votes_count')
        ->get(['id', 'name', 'votes_count']);
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/verify-otp', function () {
    return redirect('/');
})->name('verifyOtp'); // Зүгээр redirect хийж байгаа, verify-otp POST руу явна
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/send-login-otp', [AuthController::class, 'sendLoginOtp'])->name('send.otp');



Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// require __DIR__ . '/auth.php';