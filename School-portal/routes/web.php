<?php

use Illuminate\Support\Facades\Auth;
//Admin Dashboard Controller Import
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminLoginController;
//Instructor Dashboard Controller Import
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\AnnouncementsController;
//Student Dashboard Controller Import
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InstructorAuthController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\InstructorLoginController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\InstructorDashboardController;
use App\Models\Teacher;

Route::get('/', function () {
    return view('admin/dashboard');
});




// admin routes

// // Dashboard route
// Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


// Route::resource('students', StudentController::class);  // Register the resource route
// Route::resource('teachers', TeacherController::class);
// Route::resource('subjects', SubjectController::class);
// Route::resource('subjects', SubjectController::class);

Route::resource('classes', ClassController::class);
Route::get('/admin/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
Route::post('/admin/update-semester', [AdminDashboardController::class, 'updateSemester'])->name('admin.updateSemester');
Route::post('/admin/increment-year', [AdminDashboardController::class, 'incrementYearLevel'])->name('admin.incrementYear');

Route::put('/admin/students/{student}/grades', [StudentController::class, 'updateGrades'])->name('admin.update.student.grades');

//Route for Announcements
Route::resource('/admin/announcements', AnnouncementsController::class)->names([
    'index' => 'admin.announcements', // Custom name for index route
    // 'create' => 'admin.schoolCalendar.create',
    // 'store' => 'admin.schoolCalendar.store',
    // 'show' => 'admin.schoolCalendar.show',
    // 'edit' => 'admin.schoolCalendar.edit',
    // 'update' => 'admin.schoolCalendar.update',  // Fix: Add update route name
    // 'destroy' => 'admin.schoolCalendar.destroy',
]);
//Route::get('/admin/announcements', [AnnouncementsController::class, 'index'])->name('admin.announcements');
//Route::post('/announcements/store', [AnnouncementsController::class, 'store'])->name('announcements.store');

// Route for School Calendar
Route::resource('/admin/schoolCalendar', SchoolCalendarController::class)->names([
    'index' => 'admin.schoolCalendar', //Custom name for index route
    'create' => 'admin.schoolCalendar.create',
    // 'store' => 'admin.schoolCalendar.store',
    // 'show' => 'admin.schoolCalendar.show',
    // 'edit' => 'admin.schoolCalendar.edit',
    'update' => 'admin.schoolCalendar.update', //custom name for update route
    // 'destroy' => 'admin.schoolCalendar.destroy',
]);
// Route::get('/admin/schoolCalendar', [SchoolCalendarController::class, 'index'])->name('admin.schoolCalendar');


// // Show login form
// Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
// // Register admin
// Route::get('/admin/register', [AdminLoginController::class, 'showRegisterForm'])->name('admin.register');
// Route::post('/admin/register', [AdminLoginController::class, 'adminRegister'])->name('admin.register.submit');
// Route::post('/admin/password/reset', [AdminLoginController::class, 'resetPassword'])->name('admin.password.reset');

// // Handle login request
// Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
// // Protect Admin Routes
// Route::get('/admin/dashboard', function () {
//     // Check if admin is logged in
//     if (!session()->has('admin_logged_in')) {
//         return redirect()->route('admin.login')->with('error', 'You must log in first.');
//     }
//     return view('admin.dashboard');
// })->name('admin.dashboard');
// // Logout Route
// Route::get('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');



// Admin Login Routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// Admin Dashboard Routes (Protected by Admin Guard)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('students', StudentController::class);  // Register the resource route
    Route::resource('teachers', TeacherController::class);
    Route::resource('subjects', SubjectController::class);
    //Route for Announcements
Route::resource('/admin/announcements', AnnouncementsController::class)->names([
    'index' => 'admin.announcements', // Custom name for index route
]);
// Route for School Calendar
Route::resource('/admin/schoolCalendar', SchoolCalendarController::class)->names([
    'index' => 'admin.schoolCalendar', //Custom name for index route
    'create' => 'admin.schoolCalendar.create',
    'update' => 'admin.schoolCalendar.update', //custom name for update route
]);

Route::post('/admin/update-password', [AdminDashboardController::class, 'updatePassword'])->name('admin.password.update');
Route::post('/admin/update-email', [AdminDashboardController::class, 'updateEmail'])->name('admin.change-email');

    Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
// Route for updating admin profile
Route::post('/admin/profile/update', [AdminProfileController::class, 'update'])->name('admin.profile.update');
// Route::post('/admin/update-semester-dates', [AdminDashboardController::class, 'updateSemesterDates'])->name('admin.updateSemesterDates');
Route::post('/update-semester-dates', [AdminDashboardController::class, 'updateSemesterDates'])->name('admin.updateSemesterDates');



Route::post('/admin/assign-tutorial/{student}', [AdminDashboardController::class, 'assignTutorial'])->name('admin.assign.tutorial');


Route::patch('/students/{student}/toggle-enrollment', [StudentController::class, 'toggleEnrollment'])->name('students.toggleEnrollment');
Route::get('/admin/graduates', [AdminDashboardController::class, 'showGraduates'])->name('admin.graduates');
Route::post('graduate-student/{student_id}', [StudentController::class, 'graduateStudent'])->name('graduate.student');
Route::get('/students/{studentId}/grades', [StudentController::class, 'getStudentGrades']);
Route::get('/admin/student/{studentId}/subjects-grades', [StudentController::class, 'showSubjectsGrades'])->name('student.subjects.grades');
// In routes/web.php
Route::get('/student/{id}/grades', [StudentController::class, 'getGrades'])->name('student.getGrades');
// routes/web.php
// Route::patch('/students/{student_id}/enroll', [StudentController::class, 'enroll'])->name('students.enroll');
// In routes/web.php
Route::post('/students/bulk-enroll', [StudentController::class, 'bulkEnroll'])->name('students.bulkEnroll');



// end of admin routes

//==========================================================================================================================================//

//ROUTE FOR INSTRUCTOR DASHBOARD
// Instructor login routes
Route::get('/instructor_login/instructorLogin', [InstructorLoginController::class, 'showHomepage'])->name('instructor_login.homepage');
Route::post('/instructor/login', [InstructorLoginController::class, 'instructorLogin'])->name('instructor.login.submit');
Route::post('/instructor/logout', [InstructorLoginController::class, 'logout'])->name('instructor.logout');
Route::post('/instructor/send-otp', [InstructorLoginController::class, 'sendOtp'])->name('instructor.sendOtp');
Route::post('/otp-verify', [InstructorLoginController::class, 'verifyOtp'])->name('instructor.verify-otp');
Route::post('/instructorResend-otp', [InstructorLoginController::class, 'instructorResendOtp'])->name('instructor.resend-otp');
// Instructor dashboard route with middleware protection
Route::middleware(['teacher.auth'])->group(function () {
    Route::get('/instructor/instructorDashboard', [InstructorDashboardController::class, 'dashboard'])->name('instructor.instructorDashboard');
    Route::get('/instructor/instructorSched', [InstructorDashboardController::class, 'sched'])->name('instructor.instructorSched');
    Route::get('/instructor/studGrade', [InstructorDashboardController::class, 'studgrade'])->name('instructor.studGrade');
    Route::post('instructor/studGrade/update', [InstructorDashboardController::class, 'updateGrade'])->name('instructor.updateGrade'); //newly added by alvin

    Route::get('/instructor/schoolCalendar', [InstructorDashboardController::class, 'schoolCalendar'])->name('instructor.schoolCalendar');
    Route::get('/instructor/announcements', [InstructorDashboardController::class, 'announcements'])->name('instructor.announcements');
    Route::post('/instructor/set-incomplete', [InstructorDashboardController::class, 'setIncomplete'])->name('instructor.setIncomplete');

});

Route::get('/test-auth', function () {
    return Auth::guard('teacher')->user() ?? 'Not authenticated';
});
Route::put('/teachers/{teacherId}/status', [TeacherController::class, 'updateStatus'])->name('teachers.updateStatus');
Route::post('/teacher/change-password', [TeacherController::class, 'changePassword'])->name('teacher.changePassword');
Route::post('/instructor/tutorial-grades/update', [InstructorDashboardController::class, 'updateTutorialGrades'])
    ->name('instructor.updateTutorialGrades');

//==========================================================================================================================================//

//ROUTE FOR STUDENT DASHBOARD

Route::get('/student_login/studentLogin', [StudentLoginController::class, 'showHomepage'])->name('student_login.homepage');
Route::post('/student/login', [StudentLoginController::class, 'studentLogin'])->name('student.login.submit');
Route::post('/student/logout', [StudentLoginController::class, 'logout'])->name('student.logout');
Route::post('/student/send-otp', [StudentLoginController::class, 'sendOtp'])->name('student.sendOtp');
Route::post('/student-otp-verify', [StudentLoginController::class, 'verifyOtp'])->name('student.student-verify-otp');
Route::post('/resend-otp', [StudentLoginController::class, 'resendOtp'])->name('student.resend-otp');
Route::put('/student/update', [StudentController::class, 'updateProfile'])->name('student.updateProfile');


Route::middleware(['student.auth'])->group(function () {
    Route::get('/student/studentDashboard', [StudentDashboardController::class, 'dashboard'])->name('student.studentDashboard');
    Route::get('/student/profile', [StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::get('/student/grades', [StudentDashboardController::class, 'grades'])->name('student.grades');
    Route::get('/student/schedule', [StudentDashboardController::class, 'schedule'])->name('student.schedule');
    Route::get('/student/schoolCalendar', [StudentDashboardController::class, 'schoolCalendar'])->name('student.schoolCalendar');
    Route::get('/student/announcements', [StudentDashboardController::class, 'announcements'])->name('student.announcements');
    Route::post('/student/upload-profile-picture', [StudentDashboardController::class, 'uploadProfilePicture'])->name('student.uploadPp');
});
