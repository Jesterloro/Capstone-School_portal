@extends('layouts.student')

@section('content')
<div class="container mt-4">
    <!-- Top Profile Header with Dark Navy Blue Theme -->
    <div class="position-relative mb-5 p-4 rounded-4 header-container"
        style="background: linear-gradient(to right, #0F172A, #1E293B); color: white;">

        <!-- Success Toast Notification -->
@if (session('success'))
<div id="successToast" class="position-fixed top-0 end-0 m-3 shadow-lg" style="
    z-index: 1050;
    background-color: #1E293B;
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-left:20px;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease-in-out;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    min-width: 280px;
    max-width: 400px;
    width: 100%;
">

    <div style="font-size: 1.5rem;">
        <i class="bi bi-check-circle-fill"></i>
    </div>
    <div style="flex-grow: 1;">
        {{ session('success') }}
    </div>
    <button id="closeToast" style="
        background: transparent;
        border: none;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        margin-left: 5px;
    ">&times;</button>
</div>

<!-- JavaScript for Animation & Close -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successToast = document.getElementById('successToast');
        const closeToast = document.getElementById('closeToast');

        // Show toast with slide-in effect
        setTimeout(() => {
            successToast.style.opacity = '1';
            successToast.style.transform = 'translateX(0)';
        }, 100);

        // Hide toast after 4 seconds automatically
        setTimeout(() => {
            hideToast();
        }, 4000);

        // Manually close toast
        closeToast.addEventListener('click', function() {
            hideToast();
        });

        // Hide function
        function hideToast() {
            successToast.style.opacity = '0';
            successToast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                successToast.remove(); // Remove element after hiding
            }, 500);
        }
    });
</script>
@endif




        <!-- Profile Section -->
<div class="profile-container">

    <div class="text-center text-md-end order-2 order-md-1">
        <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/default-profile.png') }}"
            alt="Profile Picture" class="profile-pic rounded-circle"
            style="width: 80px; height: 80px; object-fit: cover;">
    </div>
    <div class="text-white text-center text-md-end ms-3 order-1 order-md-2 mt-2 mt-md-0">
        <h5 class="fw-bold mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
        <p class="mb-0" style="font-size: 0.9rem;">🎓 Student</p>

        <!-- Enrollment Status -->
        <p class="mb-0 mt-2" style="font-size: 1rem;">
            <strong>Status:</strong>
            <span class="px-3 py-2 rounded-pill
            {{ $student->is_enrolled ? 'bg-success text-white' : 'bg-danger text-white' }}">
            {{ $student->is_enrolled ? 'Enrolled' : 'Not Enrolled' }}
        </span>
        </p>
    </div>
</div>

<!-- Add a section for the quote below the profile image and name -->
<div class="quote-container mt-3 text-center">
    <p class="quote-text fs-4 mb-0">"Unlock new possibilities and stay inspired!"</p>
</div>

    </div>

     <!-- Student Information Section in a Separate Container -->
     <div class="container mt-5 mb-5">
        <div class="card shadow-sm border-0 rounded-4 student-info-container">
            <div class="card-header bg-dark-navy text-white">
                <i class="fas fa-user-graduate me-2"></i> Student Information
            </div>

            <div class="card-body p-4 student-info-content">
                <div class="row gy-3">
                    <div class="col-12 col-md-6">
                        <p class="text-center text-md-start mb-0">
                            <strong>🎓 Student ID:</strong> {{ $student->student_id }}
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <p class="text-center text-md-start mb-0">
                            <strong>📧 Email:</strong> {{ $student->email }}
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <p class="text-center text-md-start mb-0">
                            <strong>🏫 Department:</strong> {{ $student->department->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-12 col-md-6">
                        <p class="text-center text-md-start mb-0">
                            <strong>📘 Year Level:</strong> {{ $student->year_level }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Current Semester Dates -->
<div class="school-info-card fade-slide-in p-4 mb-4">
    <div class="row">
        <div class="col-md-6 col-sm-12 mb-3 mb-md-0 d-flex align-items-center">
            <i class="bi bi-calendar-event-fill text-primary fs-4 me-3"></i>
            <div>
                <div class="info-label">Current School Year</div>
                <div class="info-value">{{ $currentSchoolYear }}-{{ $currentSchoolYear + 1 }}</div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex align-items-center">
            <i class="bi bi-clock-fill text-success fs-4 me-3"></i>
            <div>
                <div class="info-label">Current Semester</div>
                <div class="info-value">
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
            </div>
        </div>
    </div>

    <!-- Semester Dates -->
    <div class="row">
        <div class="col-md-6 col-sm-12 mb-3 mb-md-0 d-flex align-items-center">
            <i class="bi bi-calendar-day text-info fs-4 me-3"></i>
            <div>
                <div class="info-label">Start Date</div>
                {{ \Carbon\Carbon::parse($latestSemester->start_date)->format('M d, Y') }}
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex align-items-center">
            <i class="bi bi-calendar-day text-danger fs-4 me-3"></i>
            <div>
                <div class="info-label">End Date</div>
                {{ \Carbon\Carbon::parse($latestSemester->end_date)->format('M d, Y') }}
            </div>
        </div>
    </div>
</div>


<!-- Year Level Filter -->
<div class="mb-4">
    <label for="yearLevelFilter" class="form-label fw-semibold">Filter by Year Level</label>
    <select class="form-select shadow-sm w-auto d-inline-block" id="yearLevelFilter">
        <option value="all" selected>All Year Levels</option>
        @foreach($groupedSubjects->keys() as $year)
            <option value="{{ $year }}">{{ is_numeric($year) ? $year . ' Year' : $year }}</option>
        @endforeach
    </select>
</div>

@php
    $currentSemester = request()->get('semester', 1);
    $filteredSubjects = $student->grades->filter(fn($grade) => $grade->subject->semester == $currentSemester);
    $groupedSubjects = $filteredSubjects->groupBy(fn($grade) => $grade->subject->year ?? 'Unknown');
@endphp



<!-- Grid Layout for New Sections -->
<div style="border-radius: 20px;" class="col-12">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-dark-navy text-white rounded-top-4">
            <i class="fas fa-book-open me-2"></i> Subject Overview
        </div>

        <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            @if($filteredSubjects->isEmpty())
                <div class="alert alert-light text-center" role="alert">
                    <i class="fas fa-info-circle me-1"></i> No subjects available for this semester yet.
                </div>
            @else
                <div class="accordion" id="courseOverviewAccordion">
                    @foreach($groupedSubjects as $year => $subjects)
                        <div class="year-group" data-year="{{ $year }}">
                            <h5 class="fw-bold text-primary-emphasis mt-4">
                                <i class="fas fa-layer-group me-2 text-info"></i>{{ is_numeric($year) ? $year . ' Year' : $year }}
                            </h5>

                            @foreach($subjects as $grade)
                                <div class="accordion-item border-0 mb-3 shadow-sm rounded-3"
                                     style="background-color: #F8FAFC;">
                                    <h2 class="accordion-header" id="heading{{ $grade->id }}">
                                        <button class="accordion-button collapsed rounded-3 shadow-sm" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $grade->id }}"
                                                aria-expanded="false" aria-controls="collapse{{ $grade->id }}"
                                                style="background: #fff; color: #0F172A; border-radius: 12px; transition: background 0.3s ease;">
                                            <i class="fas fa-book me-2"></i> {{ $grade->subject->name }}

                                            @php
                                                $roundedGrade = $grade->grade !== null
                                                    ? ($grade->grade - floor($grade->grade) >= 0.5 ? ceil($grade->grade) : floor($grade->grade))
                                                    : null;
                                                $badgeClass = $roundedGrade === null ? 'bg-secondary' :
                                                            ($roundedGrade == 0 ? 'bg-warning text-dark' :
                                                            ($roundedGrade >= 75 ? 'bg-success' : 'bg-danger'));
                                            @endphp
                                            <span class="badge {{ $badgeClass }} ms-2">
                                                @if ($roundedGrade === null)
                                                    No grades yet
                                                @elseif ($roundedGrade == 0)
                                                    INC
                                                @elseif ($roundedGrade >= 75 && $roundedGrade <= 100)
                                                    {{ number_format((100 - $roundedGrade) / 5 + 1, 1) }}
                                                @else
                                                    5.0
                                                @endif
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $grade->id }}" class="accordion-collapse collapse"
                                         aria-labelledby="heading{{ $grade->id }}" data-bs-parent="#courseOverviewAccordion">
                                        <div class="accordion-body p-3 bg-light rounded-bottom-3">
                                            <div class="schedule-details p-3 rounded-3">
                                                <strong class="text-navy">Schedule Information:</strong>
                                                <ul class="list-unstyled mb-0 mt-2">
                                                    <li><i class="fas fa-calendar-day me-2 text-danger"></i>
                                                        <strong>Day:</strong> {{ $grade->subject->day ?? 'TBA' }}
                                                    </li>
                                                    <li><i class="fas fa-clock me-2 text-primary"></i>
                                                        <strong>Time:</strong> {{ $grade->subject->time ?? 'TBA' }}
                                                    </li>
                                                    <li><i class="fas fa-door-open me-2 text-success"></i>
                                                        <strong>Room:</strong> {{ $grade->subject->room ?? 'TBA' }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for Year Level Filtering -->
<script>
    document.getElementById('yearLevelFilter').addEventListener('change', function () {
        const selected = this.value;
        document.querySelectorAll('.year-group').forEach(group => {
            const year = group.getAttribute('data-year');
            group.style.display = (selected === 'all' || selected === year) ? '' : 'none';
        });
    });
</script>


<!-- JavaScript for Year Filter -->
<script>
    document.getElementById('yearLevelFilter').addEventListener('change', function () {
        const selectedYear = this.value;
        document.querySelectorAll('.year-group').forEach(group => {
            const groupYear = group.getAttribute('data-year');
            if (selectedYear === 'all' || groupYear === selectedYear) {
                group.style.display = '';
            } else {
                group.style.display = 'none';
            }
        });
    });
</script>



<!-- Custom CSS -->
<style>
    /* =============================== */
/* 🚀 PAGE LOAD ANIMATIONS & STYLES */
/* =============================== */

/* 🟦 Top Header Animation */
.header-container {
    opacity: 0;
    transform: scale(0.95);
    animation: fadeZoomIn 0.6s ease-in-out 0.2s forwards;
}

@keyframes fadeZoomIn {
    0% {
        opacity: 0;
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* 🎓 Student Info Animation */
.student-info-container {
    opacity: 0;
    transform: translateY(20px);
    animation: slideUpBounce 0.7s ease-in-out 0.4s forwards;
}

@keyframes slideUpBounce {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    60% {
        opacity: 1;
        transform: translateY(-5px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* 📚 Subject Overview Animation */
.subject-overview-container {
    opacity: 0;
    transform: translateX(50px);
    animation: slideInRight 0.7s ease-in-out 0.5s forwards;
}

@keyframes slideInRight {
    0% {
        opacity: 0;
        transform: translateX(50px);
    }
    60% {
        opacity: 1;
        transform: translateX(-5px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* ✅ Accordion Items Animation */
.accordion-item {
    opacity: 0;
    transform: translateY(10px);
    animation: fadeSlideIn 0.6s ease-in-out 0.6s forwards;
}

@keyframes fadeSlideIn {
    0% {
        opacity: 0;
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* 🧑‍💓 Profile Section Animation */
.profile-container {
    opacity: 0;
    transform: scale(0.9);
    animation: fadeInUp 0.6s ease-in-out 0.3s forwards;
}

@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* 🪦 Profile Picture Hover Glow */
.profile-pic {
    border: 4px solid #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.profile-pic:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.3);
}

/* ========== Card Header Design ========== */
.bg-dark-navy {
    background-color: #0F172A;
    color: #F8FAFC;
    padding: 15px;
    font-size: 1.25rem;
    font-weight: bold;
    text-align: center;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* ========== Accordion Button with Hover ========== */
.accordion-button {
    background-color: #fff;
    color: #0F172A;
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.accordion-button:not(.collapsed) {
    background-color: #F1F5F9;
    color: #0F172A;
    transform: scale(1.02);
    transition: all 0.3s ease-out;
}

/* ✨ Accordion Hover Glow */
.accordion-button:hover {
    background-color: #E2E8F0;
    color: #0F172A;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    transform: scale(1.05);
    transition: all 0.2s ease-in-out;
}

/* 🎉 Accordion Body with Bounce Effect */
.accordion-body {
    background-color: #F8FAFC;
    border-top: 1px solid #E5E7EB;
    padding: 15px;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    animation: bounceIn 0.4s ease-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.9);
    }
    60% {
        transform: scale(1.03);
    }
    100% {
        transform: scale(1);
    }
}

/* 📚 Schedule Details with Smooth Fade */
.schedule-details {
    background-color: #F1F5F9;
    border: 1px solid #E5E7EB;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
}

/* ========== Responsive Animation for Smaller Screens ========== */
@media (max-width: 768px) {
    .accordion-button {
        font-size: 1rem;
    }

    .accordion-body {
        padding: 10px;
    }

    h1 {
        font-size: 1.8rem;
    }



}


/* ========================== */
/* 🚀 Profile Quote Styling  */
/* ========================== */

.quote-container {
    margin-top: 20px;
    text-align: center;
    font-size: 1.2rem;
    font-style: italic;
    color: #F8FAFC;
}

.quote-text {
    font-size: 1.5rem;  /* Adjust size for better visibility */
    font-weight: bold;
    color: #F1F5F9;
}

.quote-author {
    font-size: 1.1rem;
    font-weight: 500;
    color: #B1BCC8;
}

/* ========================== */
/* 📱 Responsive Adjustments  */
/* ========================== */

/* On smaller devices, reduce font sizes */
@media (max-width: 768px) {
    .quote-text {
        font-size: 1.2rem;  /* Smaller font on mobile */
    }
    .quote-author {
        font-size: 1rem;
    }

    .quote-container {
        margin-top: 15px;
    }
}

/* On very small screens, center and adjust quote further */
@media (max-width: 480px) {
    .quote-container {
        margin-top: 10px;
    }

    .quote-text {
        font-size: 1rem;
    }

    .quote-author {
        font-size: 0.9rem;
    }
}

/* ========================== */
/* 🚀 Profile Layout Adjustments for Laptop/iPad Views */
/* ========================== */
@media (min-width: 768px) {
    .profile-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .profile-container .text-center {
        text-align: left; /* Left-align the name on larger screens */
    }

    .profile-container .text-md-end {
        text-align: right; /* Right-align the profile image on larger screens */
    }

    .profile-container .order-1 {
        order: 1; /* Move name to the left side */
    }

    .profile-container .order-2 {
        order: 2; /* Move profile picture to the right side */
    }

    .profile-pic {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
}

/* ========================== */
/* 📱 Mobile View (No Changes) */
/* ========================== */
@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
        text-align: center;
    }

    .profile-container .text-center {
        text-align: center;
    }

    .profile-container .profile-pic {
        width: 80px;
        height: 80px;
    }
}


.school-info-card {
        background: linear-gradient(135deg, #f0f8ff, #e0f7fa);
        border: 1px solid #b2ebf2;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .school-info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .fade-slide-in {
        animation: fadeSlideIn 0.8s ease-out;
    }

    @keyframes fadeSlideIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .info-label {
        font-size: 1rem;
        color: #555;
    }

    .info-value {
        font-weight: 600;
        font-size: 1.25rem;
        color: #212529;
    }

    @media (max-width: 576px) {
        .info-label {
            font-size: 0.95rem;
        }

        .info-value {
            font-size: 1.1rem;
        }
    }
</style>
@endsection
