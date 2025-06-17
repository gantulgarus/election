<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Throttle limits
    const MAX_OTP_ATTEMPTS = 3;
    const OTP_RESEND_DELAY = 60; // seconds

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration with OTP sending
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|regex:/^[0-9]{8,15}$/',
        ]);

        // Check if phone number is valid format
        if (!$this->validatePhoneNumber($request->phone)) {
            return back()->withErrors(['phone' => 'Утасны дугаар буруу форматтай байна.']);
        }

        DB::beginTransaction();
        try {
            $otp = $this->generateOtp();
            $otpExpiresAt = Carbon::now()->addMinutes(5);

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'otp_code' => $otp,
                'otp_expires_at' => $otpExpiresAt,
            ]);

            $this->sendSms($user->phone, "Tany batalgaajuulah kod: $otp");

            DB::commit();

            return view('auth.verify-otp', [
                'phone' => $user->phone,
                'resendTimeout' => self::OTP_RESEND_DELAY
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Registration failed: " . $e->getMessage());
            return back()->withErrors(['system' => 'Бүртгэл амжилтгүй боллоо. Дахин оролдоно уу.']);
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login OTP request
     */
    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9]{8,15}$/',
        ]);

        // Check rate limiting
        if (RateLimiter::tooManyAttempts('login-otp:' . $request->phone, self::MAX_OTP_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn('login-otp:' . $request->phone);
            return back()->withErrors(['phone' => "Дахин илгээхэд $seconds секунд хүлээнэ үү."]);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'Хэрэглэгч бүртгэлгүй байна.']);
        }

        DB::beginTransaction();
        try {
            $otp = $this->generateOtp();
            $otpExpiresAt = Carbon::now()->addMinutes(5);

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => $otpExpiresAt,
            ]);

            $this->sendSms($user->phone, "Nevtreh batalgaajuulah kod: $otp");

            DB::commit();
            RateLimiter::hit('login-otp:' . $request->phone, self::OTP_RESEND_DELAY);

            return view('auth.verify-otp', [
                'phone' => $user->phone,
                'resendTimeout' => self::OTP_RESEND_DELAY,
                'isLogin' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Login OTP failed: " . $e->getMessage());
            return back()->withErrors(['system' => 'Код илгээхэд алдаа гарлаа. Дахин оролдоно уу.']);
        }
    }

    /**
     * Verify OTP for both registration and login
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string|digits:6',
        ]);

        $user = $this->verifyOtpCommon($request->phone, $request->otp_code);

        if (!$user) {
            return view('auth.verify-otp', [
                'phone' => $request->phone,
                'errorMessage' => 'Код буруу эсвэл хугацаа дууссан байна.',
                'isLogin' => $request->has('isLogin')
            ]);
        }

        Auth::login($user, $request->remember ?? false);

        if (Route::has('dashboard')) {
            return redirect()->route('dashboard')->with('success', 'Амжилттай нэвтэрлээ!');
        }

        return redirect('/')->with('success', 'Амжилттай нэвтэрлээ!');
    }

    /**
     * Common OTP verification logic
     */
    private function verifyOtpCommon($phone, $otp)
    {
        $user = User::where('phone', $phone)->first();

        if (
            !$user ||
            $user->otp_code !== $otp ||
            !$user->otp_expires_at ||
            !Carbon::parse($user->otp_expires_at)->isFuture()
        ) {
            return null;
        }

        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'is_verified' => true,
            'phone_verified_at' => Carbon::now(),
        ]);

        return $user;
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Амжилттай гарлаа.');
    }

    /**
     * Generate secure OTP
     */
    private function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Validate phone number format
     */
    private function validatePhoneNumber($phone): bool
    {
        return preg_match('/^[0-9]{8,15}$/', $phone);
    }

    /**
     * Send SMS via EasyCall API
     */
    private function sendSms($phone, $message)
    {
        Log::info("Attempting to send SMS to {$phone}: {$message}");

        $response = Http::timeout(10)->retry(3, 100)->post('https://dash.easycall.mn/api_v1/send_sms_api', [
            'api_key' => config('services.easycall.api_key'),
            'phone' => $phone,
            'message' => $message,
        ]);

        if ($response->failed()) {
            Log::error('SMS API Error: ' . $response->body());
            throw new \Exception('SMS илгээхэд алдаа гарлаа: ' . $response->body());
        }

        return $response->json();
    }
}
