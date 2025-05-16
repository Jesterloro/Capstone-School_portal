<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\InstructorOtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Notifications\SuspiciousLoginAttempt;
use App\Http\Controllers\InstructorAuthController;


class InstructorLoginController extends Controller
{
    public function showHomepage()
    {
        return view('instructor_login.instructorLogin');
    }



    public function instructorLogin(Request $request)
    {
        Log::info('Instructor login attempt', ['email' => $request->email]);

        $credentials = $request->only('email', 'password');
        $teacher = Teacher::where('email', $request->email)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'attempts_left' => 3
            ]);
        }

        // ✅ Reset attempts if cooldown is over
if ($teacher->cooldown_until && Carbon::now()->greaterThanOrEqualTo($teacher->cooldown_until)) {
    $teacher->update([
        'login_attempts' => 0,
        'cooldown_until' => null,
        'last_failed_login' => null
    ]);
}

// ❌ Block if in cooldown period
if ($teacher->cooldown_until && Carbon::now()->lessThan($teacher->cooldown_until)) {
    $penalty_time_in_seconds = $teacher->penalty_time ?? 600;  // default to 600 if null
    $cooldownUntil = Carbon::now()->addSeconds($penalty_time_in_seconds);

    $diffInSeconds = Carbon::now()->diffInSeconds($teacher->cooldown_until);
    $minutes = floor($diffInSeconds / 60);
    $seconds = $diffInSeconds % 60;

    return response()->json([
        'success' => false,
        'message' => 'Too many failed attempts. Please try again later.',
        'attempts_left' => 0,
        'time_left' => sprintf('%02d:%02d', $minutes, $seconds),
        'penalty_time' => $penalty_time_in_seconds,  // this is your original integer value
        'cooldown_until' => $cooldownUntil->format('Y-m-d H:i:s')  // formatted cooldown time
    ]);
}


        if ($teacher->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Your account is inactive. Please contact the administrator.',
                'attempts_left' => 3 - $teacher->login_attempts
            ]);
        }

        // ✅ Attempt login
        if (Auth::guard('teacher')->attempt($credentials)) {
            $teacher->update([
                'login_attempts' => 0,
                'last_failed_login' => null,
                'cooldown_until' => null
            ]);

            session(['email' => $request->email]);

            return response()->json([
                'success' => true,
                'email' => $request->email
            ]);
        } else {
            $teacher->increment('login_attempts');
            $teacher->update(['last_failed_login' => now()]);

            $attemptsLeft = max(0, 3 - $teacher->login_attempts);

            // ⏳ Apply cooldown if needed
            if ($teacher->login_attempts >= 3) {
                $penaltySeconds = $teacher->penalty_time ?? 600;
                $cooldownUntil = Carbon::now()->addSeconds($penaltySeconds);

                $teacher->update([
                    'cooldown_until' => $cooldownUntil
                ]);

                $teacher->notify(new SuspiciousLoginAttempt());

                return response()->json([
                    'success' => false,
                    'message' => "Too many failed attempts. Your account is temporarily locked.",
                    'attempts_left' => 0,
                    'time_left' => sprintf('%02d:%02d', floor($penaltySeconds / 60), $penaltySeconds % 60),
                    'penalty_time' => $penaltySeconds,
                    'cooldown_until' => $cooldownUntil->format('Y-m-d H:i:s')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'attempts_left' => $attemptsLeft
            ]);
        }
    }








    public function sendOtp(Request $request)
{
    Log::info("sendOtp() called for: " . $request->email);

    // If the email is not passed in the request, use the session value
    $email = $request->email ?: session('email');

    if (!$email) {
        Log::error("Email is missing in sendOtp.");
        return response()->json(['error' => 'Email is missing'], 400);
    }

    $request->validate([
        'email' => 'required|email|exists:teachers,email',
    ]);

    $instructor = Teacher::where('email', $email)->first();

    if (!$instructor) {
        Log::error("Instructor not found for email: " . $email);
        return response()->json(['error' => 'Instructor not found'], 404);
    }

    $otp = rand(100000, 999999);
    Log::info("Generated OTP: " . $otp);

    // Store OTP in DB
    $instructor->update([
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(5),
    ]);
    sleep(2);

    // Send OTP Email
    try {
        Mail::to($email)->send(new InstructorOtpMail($otp));
        Log::info("OTP email sent successfully.");
    } catch (\Exception $e) {
        Log::error("Error sending OTP email: " . $e->getMessage());
    }

    return response()->json(['message' => 'OTP sent successfully!']);
}


    // Verify OTP
    public function verifyOtp(Request $request)
{
    Log::info('OTP Verification Attempt', [
        'email' => $request->email,
        'otp' => $request->otp,
        'timestamp' => Carbon::now(),
    ]);

    // If the email is missing from the request, fallback to session email
    $email = $request->email ?: session('email');

    if (!$email) {
        Log::error('OTP Verification Failed - Email missing');
        return redirect()->back()->with('error', 'Email is missing. Please login again.');
    }

    $request->validate([
        'email' => 'required|email|exists:teachers,email',
        'otp' => 'required|digits:6',
    ]);

    $instructor = Teacher::where('email', $email)->first();

    if ($instructor && $instructor->otp == $request->otp && Carbon::now()->lt($instructor->otp_expires_at)) {
        Log::info('OTP Verified Successfully', [
            'email' => $email,
            'timestamp' => Carbon::now(),
        ]);

        Auth::guard('teacher')->loginUsingId($instructor->id);
        session()->regenerate();
        $instructor->update(['otp' => null, 'otp_expires_at' => null]);

        return redirect()->route('instructor.instructorDashboard');
    }

    Log::warning('Invalid or Expired OTP Attempt', [
        'email' => $email,
        'provided_otp' => $request->otp,
        'expected_otp' => $instructor->otp ?? 'N/A',
        'otp_expires_at' => $instructor->otp_expires_at ?? 'N/A',
        'timestamp' => Carbon::now(),
    ]);
    return redirect()->back()->with('error', 'Invalid or expired OTP. Please try again.');
}


    public function instructorResendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:teachers,email']);

        $otp = rand(100000, 999999);

        // Update OTP and expiration
        Teacher::where('email', $request->email)->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new InstructorOtpMail($otp)); // Use the correct mailable class
            Log::info("Resent OTP email successfully.");
        } catch (\Exception $e) {
            Log::error("Error resending OTP email: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP. Try again later.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'OTP resent successfully!']);
    }


    // Logout Instructor
    public function logout()
    {
        Auth::guard('teacher')->logout();
        session()->flush();
        return redirect()->route('instructor_login.homepage')->with('success', 'Logged out successfully');
    }
}
