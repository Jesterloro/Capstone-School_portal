<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\StudentOtpMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Hash;


class StudentLoginController extends Controller
{
    public function showHomepage()
    {
        return view('student_login.studentLogin');
    }

    // Login without OTP
    public function studentLogin(Request $request)
    {
        Log::info('Student login attempt', ['email' => $request->email]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            $email = $request->email;
            // Store the new email in the session
            session(['email' => $email]);

            Log::info('Student login successful', ['email' => $email]);

            // Send OTP after successful login
            return response()->json([
                'success' => true,
                'email' => $email // Pass email for OTP request
            ]);
        } else {
            Log::warning('Student login failed - Invalid credentials', ['email' => $request->email]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
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
        'email' => 'required|email|exists:students,email',
    ]);
    $student = Student::where('email', $email)->first();
    if (!$student) {
        Log::error("Student not found for email: " . $request->email);
        return response()->json(['error' => 'Student not found'], 404);
    }
    $otp = rand(100000, 999999);
    Log::info("Generated OTP: " . $otp);

    // Store OTP in DB
    $student->update([
        'otp' => $otp,
        'otp_expires_at' => Carbon::now()->addMinutes(5),
    ]);

    sleep(2);  // Short delay to simulate processing time

    // Send OTP Email
    try {
        Mail::to($request->email)->send(new StudentOtpMail($otp));
        Log::info("OTP email sent successfully.");
    } catch (\Exception $e) {
        Log::error("Error sending OTP email: " . $e->getMessage());
    }

    return response()->json(['message' => 'OTP sent successfully!']);
}



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
    if (!$request->email) {
        Log::error('OTP Verification Failed - Email missing');
        return redirect()->back()->with('error', 'Email is missing. Please login again.');
    }

    $request->validate([
        'email' => 'required|email|exists:students,email',
        'otp' => 'required|digits:6',
    ]);

    $student = Student::where('email', $request->email)->first();

    if ($student && $student->otp == $request->otp && Carbon::now()->lt($student->otp_expires_at)) {
        Log::info('OTP Verified Successfully', [
            'email' => $request->email,
            'timestamp' => Carbon::now(),
        ]);

        Auth::guard('student')->loginUsingId($student->id);
        session()->regenerate();
        $student->update(['otp' => null, 'otp_expires_at' => null]);

        return redirect()->route('student.studentDashboard')->with('success', 'Welcome, ' . $student->first_name . '!');
    }

    Log::warning('Invalid or Expired OTP Attempt', [
        'email' => $request->email,
        'provided_otp' => $request->otp,
        'expected_otp' => $student->otp ?? 'N/A',
        'otp_expires_at' => $student->otp_expires_at ?? 'N/A',
        'timestamp' => Carbon::now(),
    ]);

    return redirect()->back()->with('error', 'Invalid or expired OTP. Please try again.');
}


    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:students,email']);

        $otp = rand(100000, 999999);

        // Update OTP and expiration
        Student::where('email', $request->email)->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        try {
            Mail::to($request->email)->send(new StudentOtpMail($otp)); // Use the correct mailable class
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
        Auth::guard('student')->logout();
        session()->flush();
        return redirect()->route('student_login.homepage')->with('success', 'Logged out successfully');
    }
}
