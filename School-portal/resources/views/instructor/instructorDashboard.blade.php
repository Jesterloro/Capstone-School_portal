@extends('layouts.instructor')

@section('content')
@php
    // Random Welcome Messages Array
    $welcomeMessages = [
        'Ready to inspire and empower your students? Let’s make learning amazing today!',
        'Your guidance makes a difference! Let’s create a positive impact today!',
        'Another day to shape brilliant minds! Let’s make it count!',
        'Your dedication lights the way. Time to guide your students to success!',
        'Ready to change lives? Let’s empower your students with knowledge!',
        'Learning begins with you! Let’s make this day impactful and inspiring!'
    ];

    // Pick a Random Message
    $randomMessage = $welcomeMessages[array_rand($welcomeMessages)];
@endphp

<div class="container-fluid p-0 position-relative">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center shadow-sm rounded-4 p-5 header-section">
        <!-- Welcome Message with Dynamic Scaling -->
        <div class="welcome-section">
            <h1 class="fw-bold mb-3 welcome-title">👋 Welcome Back, <span
                    class="fw-normal" style="color: #16C47F;">{{ $teacher->name ?? 'Instructor' }}</span>!</h1>
            <p class="fs-5 mb-0 text-white-75">
                {{ $randomMessage }}
            </p>
        </div>
    </div>

    <!-- Profile Section - Positioned Top Right -->
<div class="position-absolute top-0 end-0 m-4 profile-container">
    <div class="d-flex align-items-center position-relative">
        <div class="me-3 text-end d-none d-md-block">
            <p class="mb-0 fw-bold text-white profile-name">{{ $teacher->name ?? 'Instructor' }}</p>
            <small style="color: white;" class="text-white-75">Instructor</small>
        </div>
        <div class="position-relative">
            <img src="{{ asset('image/ibsmalogo.png') }}" alt="User Avatar"
                class="rounded-circle border border-3 border-white shadow-sm profile-img"
                id="profileDropdownToggle">
            <!-- Profile Dropdown -->
            <div id="profileDropdown"
                class="dropdown-menu position-absolute end-0 mt-2 rounded-4 shadow-lg border border-light dropdown-custom"
                style="display: none;">
                <a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                    <i class="fas fa-cog me-2" style="color: #16C47F;"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <form id="logout-form" action="{{ route('instructor.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages with Animation -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade custom-flash show" role="alert" id="flash-success">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade custom-flash show" role="alert" id="flash-error">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title" id="settingsModalLabel"><i class="fas fa-cog me-2"></i> Account Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('teacher.changePassword') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <input type="password" class="form-control shadow-sm" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-semibold">New Password</label>
                        <input type="password" class="form-control shadow-sm" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" class="form-control shadow-sm" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .profile-img {
        cursor: pointer;
        width: 60px;
        height: 60px;
    }

    .dropdown-custom {
        z-index: 2000;
        display: none;
        background-color: white;
        min-width: 200px;
    }

    .custom-flash {
        position: fixed;
        top: -100px;
        right: 20px;
        z-index: 1050;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.6s ease-in-out;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        max-width: 350px;
    }

    .custom-flash.show {
        top: 20px;
        opacity: 1;
        transform: translateY(0);
    }
</style>

<!-- JS to Toggle Dropdown -->
<script>
        const toggle = document.getElementById('profileDropdownToggle');
    const dropdown = document.getElementById('profileDropdown');

    document.addEventListener('click', function (e) {
        if (toggle.contains(e.target)) {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        } else if (!dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Hide dropdown on click outside
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('profileDropdown');
        const toggle = document.getElementById('profileDropdownToggle');
        if (!toggle.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    // for fade in effect of success
    window.addEventListener('DOMContentLoaded', () => {
        const success = document.getElementById('flash-success');
        const error = document.getElementById('flash-error');

        [success, error].forEach(alert => {
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 600); // Remove after fade out
                }, 5000); // 5 seconds display
            }
        });
    });
</script>

<!-- Custom School Info Card -->
<div class="custom-school-info bg-white p-4 rounded-4 shadow-sm animate-info mt-5">
    <div class="row align-items-center">

        <!-- Left: Current School Year & Semester -->
        <div class="col-md-8 mb-4 mb-md-0">
            <div class="d-flex flex-wrap">
                <!-- School Year -->
                <div class="info-box d-flex align-items-center me-md-5 mb-3 mb-md-0">
                    <div class="icon-wrapper me-3">
                        <i class="bi bi-calendar-event-fill text-navy fs-3"></i>
                    </div>
                    <div>
                        <div class="label">Current School Year</div>
                        <div class="value">{{ $currentSchoolYear }} - {{ $currentSchoolYear + 1 }}</div>
                    </div>
                </div>

                <!-- Semester & Duration -->
                <div class="info-box d-flex align-items-center">
                    <div class="icon-wrapper me-3">
                        <i class="bi bi-clock-fill text-success fs-3"></i>
                    </div>
                    <div>
                        <div class="label">Current Semester</div>
                        <div class="value">
                            @if($currentSemester == 1)
                                First Semester
                            @elseif($currentSemester == 2)
                                Second Semester
                            @elseif($currentSemester == 3)
                                Summer
                            @else
                                Unknown
                            @endif
                        </div>

                        @if ($latestSemester)
                        <div class="small text-muted mt-1">
                            <i class="bi bi-calendar-range me-1"></i>
                            {{ \Carbon\Carbon::parse($latestSemester->start_date)->format('M d, Y') }}
                            –
                            {{ \Carbon\Carbon::parse($latestSemester->end_date)->format('M d, Y') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Total Subjects -->
        <div class="col-md-4">
            <div class="total-subjects text-center p-4 rounded-3">
                <div class="subject-icon mb-2"><i class="fas fa-book-open text-navy fs-2"></i></div>
                <div class="subject-label">📘 Total Subjects</div>
                <div class="subject-count">{{ $subjects->count() }}</div>
            </div>
        </div>

    </div>
</div>

<style>
    .custom-school-info {
        border-left: 5px solid #1E293B;
        transition: all 0.4s ease;
    }

    .animate-info {
        animation: slideFade 0.6s ease;
    }

    @keyframes slideFade {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .info-box .label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
    }

    .info-box .value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1E293B;
    }

    .icon-wrapper i {
        background-color: #f0f4f8;
        padding: 10px;
        border-radius: 10px;
        display: inline-block;
    }

    .total-subjects {
        background: linear-gradient(135deg, #f0f4f8, #e2e8f0);
        border: 2px solid #d1d5db;
    }

    .total-subjects .subject-label {
        font-size: 1rem;
        color: #475569;
        font-weight: 500;
    }

    .total-subjects .subject-count {
        font-size: 2rem;
        font-weight: bold;
        color: #1E293B;
    }

    .text-navy {
        color: #1E293B !important;
    }
</style>


    <div class="mt-1 p-3 bg-white shadow-sm rounded-4">
        <div class="row">
           <!-- Subject Overview Section (3/4 width) -->
<div class="col-md-9">
    <h4 style="background-color: #0F172A" class="fw-bold p-3 text-white rounded">📚 Subject Overview</h4>

    <div class="scrollable-container mt-2">
        <!-- Dropdown to select semester -->
        <div class="mb-3">
            <label for="semester" class="form-label text-white">Select Semester</label>
            <select id="semester" class="form-select" onchange="filterSubjects()">
                <option value="">Select Semester</option>
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3">Summer</option>
            </select>
        </div>

        <div id="subjects-container">
            @if($subjects->isNotEmpty())
                @foreach($subjects as $subject)
                <div class="mb-3 subject-card" data-semester="{{ $subject->semester }}">
                    <div class="p-3 bg-light border rounded-4 shadow-sm">
                        <h6 style="color: #0F172A" class="fw-bold mb-1">{{ $subject->name }}</h6>

                        <p class="text-muted mb-1" style="font-size: 0.9rem;">
                            Day: {{ $subject->day ?? 'N/A' }}
                        </p>

                        <p class="text-muted mb-1" style="font-size: 0.9rem;">
                            Time: {{ $subject->time ?? 'N/A' }}
                        </p>

                        <p class="text-muted mb-1" style="font-size: 0.9rem;">
                            Room: {{ $subject->room ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                @endforeach
            @else
                <div class="text-center">
                    <p class="text-muted">⚡️ No subjects assigned yet. Please check with the admin.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Function to filter subjects based on selected semester
    function filterSubjects() {
        let selectedSemester = document.getElementById('semester').value;
        let subjectCards = document.querySelectorAll('.subject-card');

        subjectCards.forEach(function(card) {
            let semester = card.getAttribute('data-semester');

            // Show or hide the subject based on selected semester
            if (selectedSemester === "" || semester == selectedSemester) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>



            <!-- Re-taking & Incomplete Students Section (1/4 width) -->
            <div class="col-md-3">
                <h4 class="fw-bold p-3 bg-danger text-white rounded">🚨 Re-taking & Incomplete</h4>
                <div class="scrollable-container mt-2" style="max-height: 600px; overflow-y: auto;">
                    @php
                        $reTakingStudents = collect();

                        foreach ($subjects as $subject) {
                            $students = $subject->students;
                            foreach ($students as $student) {
                                $grade = $editableGrades->where('student_id', $student->student_id)
                                    ->where('subject_id', $subject->id)
                                    ->sortByDesc('school_year')
                                    ->first();

                                if (!$grade) continue;

                                if ($grade->grade === null || $grade->grade == 0) {
                                    if (!$reTakingStudents->contains(fn($s) => $s['student']->student_id === $student->student_id && $s['subject'] === $subject->name)) {
                                        $reTakingStudents->push([
                                            'student' => $student,
                                            'status' => 'INC',
                                            'subject' => $subject->name,
                                        ]);
                                    }
                                } elseif ($grade->grade < 74.50) {
                                    if (!$reTakingStudents->contains(fn($s) => $s['student']->student_id === $student->student_id && $s['subject'] === $subject->name)) {
                                        $reTakingStudents->push([
                                            'student' => $student,
                                            'status' => 'Re-taking',
                                            'subject' => $subject->name,
                                        ]);
                                    }
                                }
                            }
                        }
                    @endphp

                    @if($reTakingStudents->isNotEmpty())
                        @foreach($reTakingStudents as $entry)
                            <div class="p-3 bg-light border rounded-4 shadow-sm mb-3">
                                <h6 class="fw-bold text-danger mb-1">
                                    {{ $entry['student']->last_name }}, {{ $entry['student']->first_name }}
                                </h6>
                                <p class="text-muted mb-0">Student ID: {{ $entry['student']->student_id }}</p>
                                <small class="text-secondary d-block">Department: {{ $entry['student']->department->name ?? 'N/A' }}</small>
                                <small class="text-secondary d-block">Year Level: {{ $entry['student']->year_level }}</small>
                                <p class="text-muted mb-1">Subject: {{ $entry['subject'] }}</p>

                                <div class="badge {{ $entry['status'] === 'INC' ? 'bg-warning text-dark' : 'bg-danger' }}">
                                    {{ $entry['status'] }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center">
                            <p class="text-muted">✅ No students currently re-taking or incomplete.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>





<!-- Custom CSS -->
<style>
    /* ===== General Styling ===== */
    body {
        background-color: #ffffff;
        color: #212529;
    }

    h1,
    .welcome-title {
        font-size: 2.8rem;
        font-weight: bold;
        color: #F9FAFB;
    }

    p {
        font-size: 1.1rem;
        color: #E5E7EB;
    }

    .rounded-4 {
        border-radius: 20px;
    }

    /* ===== Header Section ===== */
    .header-section {
        background: linear-gradient(135deg, #1E293B, #0F172A);
        color: white;
        transition: all 0.4s ease-in-out;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    /* ===== Subject Overview ===== */
    .subject-section {
        background-color: #ffffff;
        border: 1px solid #E5E7EB;
        transition: box-shadow 0.3s ease-in-out;
    }

    .subject-card {
        background-color: #f9fafb;
        border-left: 5px solid #16C47F;
        transition: transform 0.3s ease-in-out;
    }

    .subject-card:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    /* ===== Profile Dropdown Styling =====
    #profileDropdown {
        top: 70px;
        right: 0;
        width: 220px;
        border: 1px solid #E5E7EB;
        background-color: #ffffff;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    } */

    /* ===== Calendar Section ===== */
    .calendar-section {
        background-color: #ffffff;
        border: 1px solid #E5E7EB;
        transition: box-shadow 0.3s ease-in-out;
    }

    /* Responsive Handling */
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 2.3rem;
        }

        .profile-img {
            width: 60px;
            height: 60px;
        }

        .calendar-embed {
            height: 300px;
        }
    }

    @media (max-width: 576px) {
        .welcome-title {
            font-size: 1.6rem;
        }

        .profile-img {
            width: 55px;
            height: 55px;
        }

        .calendar-embed {
            height: 250px;
        }
    }

   /* ===== Subject Overview ===== */
.subject-section {
    background-color: #ffffff;
    border: 1px solid #E5E7EB;
    transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
    max-width: 100%;
    padding: 1.5rem;
    margin-top: 20px;
}

/* Subject Header Styling */
.subject-header {
    background-color: #0F172A;
    color: white;
    border-radius: 12px 12px 0 0;
    margin: -1.5rem -1.5rem 1rem -1.5rem;
    padding: 1rem 1.5rem;
    font-size: 1.25rem;
    text-align: center;
}

/* Scrollable Container for Subjects */
.scrollable-container {
    max-height: 500px; /* Define a fixed height */
    overflow-y: auto;
    padding-right: 10px;
}

/* Subject Card Styling */
.subject-card {
    background-color: #f9fafb;
    border-left: 4px solid #16C47F;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    padding: 1rem;
    font-size: 0.95rem;
}

/* Hover Effect */
.subject-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

/* Custom Scrollbar Styling */
.scrollable-container::-webkit-scrollbar {
    width: 6px;
}

.scrollable-container::-webkit-scrollbar-thumb {
    background-color: #2E392B;
    border-radius: 6px;
}

.scrollable-container::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}
/* ===== Profile Section Styling =====
.profile-container {
    top: 20px;
    right: 20px;
    z-index: 1000;
} */

/* Profile Name and Role */
.profile-name {
    font-size: 1rem;
}

/* Profile Image - Default Size
.profile-img {
    width: 60px;
    height: 60px;
    border: 3px solid #ffffff;
    object-fit: cover;
} */

/* Profile Dropdown */
/* #profileDropdown {
    top: 70px;
    right: 0;
    width: 220px;
    border: 1px solid #E5E7EB;
    background-color: #ffffff;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
} */

/* Responsive Profile Image - Medium Screens */
@media (max-width: 768px) {
    .profile-img {
        width: 50px;
        height: 50px;
    }

    .profile-name {
        font-size: 0.9rem;
    }
}

/* Responsive Profile Image - Small Screens */
@media (max-width: 576px) {
    .profile-img {
        width: 45px;
        height: 45px;
    }

    .profile-name {
        font-size: 0.85rem;
    }
}


</style>
@endsection
