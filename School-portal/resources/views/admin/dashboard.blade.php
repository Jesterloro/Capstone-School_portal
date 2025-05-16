@extends('layouts.app')

@section('content')
<div class="">
    <!-- Top Profile Container with small side gaps -->
<div class="d-flex justify-content-between align-items-center mb-5 p-4 rounded-4 shadow-lg profile-container" style="
background-color: #0F172A; /* Dark Navy Blue */
color: white;
position: relative;
overflow: hidden;
">
<div class="position-relative">
    <h1 class="display-4 fw-bold mb-0">Welcome Back!</h1>
    <p class="lead">Stay on top of your tasks and manage everything seamlessly.</p>
</div>

<!-- Profile & Settings Button -->
<div class="d-flex align-items-center gap-3">
    <button class="btn btn-light text-dark rounded-pill shadow-sm animate-btn"
            data-bs-toggle="modal" data-bs-target="#settingsModal"
            onclick="showSection('password')">
        <i class="fas fa-user-cog me-1"></i> Manage Account Settings
    </button>
</div>
</div>

<!-- Dashboard Cards with Counts & Icons -->
<div class="row g-4" style="margin: 0px 20px;">
<!-- Teachers Card -->
<div class="col-md-3">
    <div class="card dashboard-card shadow-lg border-0 rounded-4 hover-effect" style="background-color: #0F172A; color: white;">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="icon-container" style="background-color: #3B82F6;">
                <i class="fas fa-chalkboard-teacher icon"></i>
            </div>
            <div class="text-end">
                <h6 class="fw-bold text-uppercase">Total Teachers</h6>
                <h3 class="font-weight-bold">{{ $totalTeacher }}</h3>
                <p class="small mb-0">Manage teacher information</p>
            </div>
        </div>
    </div>
</div>

<!-- School Year & Semester Container -->
<div class="col-md-3">
    <div class="card dashboard-card shadow-lg border-0 rounded-4 hover-effect" style="background-color: #0F172A; color: white;">
        <div class="card-body ">
            <h6 class="fw-bold text-uppercase">Current School Year & Semester</h6>
            <div class="mt-3">
                <p class="mb-1">📅 <strong>School Year:</strong> {{ $currentSchoolYear  }} - {{ $currentSchoolYear  + 1}} </p>
                <p class="text-white">Current Semester: <strong>
                    @if ($currentSemester == 1)
                        Semester 1
                    @elseif ($currentSemester == 2)
                        Semester 2
                    @elseif ($currentSemester == 3)
                        Summer Class
                    @else
                        Not Set
                    @endif
                </strong></p>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Success Toast Notification -->
@if (session('success'))
<div id="successToast" class="position-fixed top-0 end-0 m-3 shadow-lg" style="
    z-index: 1050;
    min-width: 350px;
    background-color: #1E293B;
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 12px;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease-in-out;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
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



<!-- Delete Toast Notification -->
@if (session('deleted'))
<div id="deleteToast" class="position-fixed top-0 end-0 m-3 shadow-lg" style="
    z-index: 1050;
    min-width: 350px;
    background-color: #DC3545;
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 12px;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.5s ease-in-out;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
">
    <div style="font-size: 1.5rem;">
        <i class="bi bi-trash-fill"></i> <!-- Delete Icon -->
    </div>
    <div style="flex-grow: 1;">
        {{ session('deleted') }}
    </div>
    <button id="closeDeleteToast" style="
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
        const deleteToast = document.getElementById('deleteToast');
        const closeDeleteToast = document.getElementById('closeDeleteToast');

        // Show toast with slide-in effect
        setTimeout(() => {
            deleteToast.style.opacity = '1';
            deleteToast.style.transform = 'translateX(0)';
        }, 100);

        // Hide toast after 4 seconds automatically
        setTimeout(() => {
            hideDeleteToast();
        }, 4000);

        // Manually close toast
        closeDeleteToast.addEventListener('click', function() {
            hideDeleteToast();
        });

        // Hide function
        function hideDeleteToast() {
            deleteToast.style.opacity = '0';
            deleteToast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                deleteToast.remove(); // Remove element after hiding
            }, 500);
        }
    });
</script>
@endif

<!-- Ensure Chart.js is loaded -->
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row g-4" style="margin-top: 30px;">
    <!-- Student Enrollment Overview -->
    <div class="col-md-6">
        <div class="card shadow-lg rounded-4 p-4" style="background-color: #0F172A; color: white;">
            <h5 class="fw-bold mb-3">Student Enrollment Overview</h5>
            <canvas id="studentsChart" height="250"></canvas>
        </div>
    </div>

    <!-- Department Gender Chart -->
    <div class="col-md-6">
        <div class="card shadow-lg rounded-4 p-4" style="background-color: #0F172A; color: white;">
            <h5 class="fw-bold mb-3">Students per Department (Gender)</h5>
            <canvas id="departmentChart" height="250"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Student Enrollment Overview Chart
        const studentsCtx = document.getElementById('studentsChart').getContext('2d');
        new Chart(studentsCtx, {
            type: 'bar',
            data: {
                labels: ['Total Students', 'Enrolled', 'Not Enrolled'],
                datasets: [{
                    label: 'Number of Students',
                    data: [
                        {{ $totalStudents }},
                        {{ $enrolledStudents }},
                        {{ $notEnrolledStudents }}
                    ],
                    backgroundColor: [
                        '#3B82F6', // Total Students
                        '#22C55E', // Enrolled
                        '#EF4444'  // Not Enrolled
                    ],
                    borderRadius: 10,
                    barThickness: 50
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'white',
                            font: { weight: 'bold' }
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    }
                }
            }
        });

        // Department Gender Chart
        const deptCtx = document.getElementById('departmentChart').getContext('2d');
        const departmentNames = @json($departments->pluck('name'));
        const maleCounts = @json($departments->pluck('male_count'));
        const femaleCounts = @json($departments->pluck('female_count'));

        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: departmentNames,
                datasets: [
                    {
                        label: 'Male Students',
                        data: maleCounts,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderRadius: 10,
                    },
                    {
                        label: 'Female Students',
                        data: femaleCounts,
                        backgroundColor: 'rgba(236, 72, 153, 0.7)',
                        borderRadius: 10,
                    }
                ]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: { color: 'white', font: { weight: 'bold' } },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: { color: 'white' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'white',
                            font: { weight: 'bold' }
                        }
                    }
                }
            }
        });
    });
</script>


<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4 animate-modal">
            <!-- Modal Header -->
            <div class="modal-header text-white modal-header-custom">
                <h5 class="modal-title" id="settingsModalLabel">
                    <i class="fas fa-cogs me-1"></i> Account Settings
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4">
                <!-- ========================== -->
                <!-- Change Email Section -->
                <!-- ========================== -->
                <div id="email-section" class="transition-section d-none">
                    <!-- Change Email Form -->
                    <form action="{{ route('admin.change-email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password_email" class="form-label"><i class="fas fa-lock me-1"></i> Current Password</label>
                            <input type="password" name="current_password" id="current_password_email" class="form-control input-custom" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_email" class="form-label"><i class="fas fa-envelope me-1"></i> New Email</label>
                            <input type="email" name="new_email" id="new_email" class="form-control input-custom" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_email_confirmation" class="form-label"><i class="fas fa-envelope-open me-1"></i> Confirm New Email</label>
                            <input type="email" name="new_email_confirmation" id="new_email_confirmation" class="form-control input-custom" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 animate-btn">
                            <i class="fas fa-check-circle me-1"></i> Change Email
                        </button>
                    </form>
                    <button class="btn btn-outline-secondary w-100 switch-section mt-3 animate-btn" data-target="password">
                        <i class="fas fa-key me-1"></i> Switch to Change Password
                    </button>
                </div>

                <!-- ========================== -->
                <!-- Change Password Section -->
                <!-- ========================== -->
                <div id="password-section" class="transition-section">
                    <form action="{{ route('admin.password.update') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label"><i class="fas fa-lock me-1"></i> Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control input-custom" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label"><i class="fas fa-key me-1"></i> New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control input-custom" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label"><i class="fas fa-check me-1"></i> Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control input-custom" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 animate-btn">
                            <i class="fas fa-sync-alt me-1"></i> Update Password
                        </button>
                    </form>
                    <button class="btn btn-outline-secondary w-100 switch-section mt-3 animate-btn" data-target="email">
                        <i class="fas fa-envelope me-1"></i> Switch to Change Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    body {
        background-color: #f4f6f9;
    }

    /* ===== Dark Navy Blue Welcome Section ===== */
.profile-container {
    background-color: #0F172A !important;
    color: white;
}

/* ===== Dark Navy Blue Dashboard Cards ===== */
.dashboard-card {
    background-color: #0F172A !important;
    color: white;
    border: none;
}

/* Updated Icon Container */
.icon-container {
    background-color: #3B82F6 !important;
    padding: 12px;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Icon Color Update */
.icon {
    font-size: 1.8rem;
    color: #F8FAFC;
}

/* Hover Effect on Cards */
.dashboard-card:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.3);
    background-color: #1E293B;
}


/* ===== Modal Custom Header & Content ===== */
.modal-header-custom {
    background: linear-gradient(135deg, #0F172A, #1E293B);
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    color: #F8FAFC; /* Text color for header */
}

.modal-content {
    background-color: #0F172A;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
    color: #F8FAFC; /* Text color for body */
}

/* ===== Form Input Styles ===== */
.input-custom {
    background-color: #1E293B;
    border: 1px solid #3B82F6;
    color: #F8FAFC; /* Text color */
    border-radius: 8px;
    padding: 12px;
    transition: all 0.3s ease;
}

/* Placeholder text style for better visibility */
.input-custom::placeholder {
    color: #94A3B8; /* Light gray placeholder */
}

/* Input focus effects */
.input-custom:focus {
    border-color: #3B82F6;
    box-shadow: 0 0 12px rgba(59, 130, 246, 0.6);
}

/* ===== Button Animations ===== */
.animate-btn {
    transition: all 0.3s ease-in-out;
    border-radius: 12px;
    padding: 12px 20px;
    color: #F8FAFC; /* Button text color */
}

/* Button Hover Effect */
.animate-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
}

/* ===== Section Transition & Fade In Effect ===== */
.transition-section {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.4s ease-in-out;
}

.transition-section.show {
    opacity: 1;
    transform: translateY(0);
}

/* ===== Modal Animation on Open ===== */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    transition: transform 0.3s ease-out, opacity 0.3s ease-in-out;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

/* ===== Switch Button Custom Style ===== */
.switch-section {
    background-color: #1E293B;
    color: #F8FAFC;
    border: 1px solid #3B82F6;
}

.switch-section:hover {
    background-color: #3B82F6;
    color: white;
}

/* ===== Additional Text Fixes ===== */
label,
p,
h5,
h6 {
    color: #F8FAFC; /* Brighten labels and text */
}

.btn-close-white {
    filter: invert(1); /* Ensures close button remains visible */
}

/* Tooltip and Modal Animation Fix */
.tooltip-inner {
    background-color: #1E293B;
    color: #F8FAFC;
    border-radius: 5px;
}

</style>
<script>
    // Switch between Change Email and Change Password sections
document.querySelectorAll('.switch-section').forEach(btn => {
    btn.addEventListener('click', function () {
        const target = this.getAttribute('data-target');
        document.querySelectorAll('.transition-section').forEach(section => {
            section.classList.remove('show');
            setTimeout(() => {
                section.classList.add('d-none');
            }, 300);
        });

        const targetSection = document.getElementById(`${target}-section`);
        setTimeout(() => {
            targetSection.classList.remove('d-none');
            setTimeout(() => {
                targetSection.classList.add('show');
            }, 50);
        }, 300);
    });
});

// Show Password Section on Modal Open
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#password-section').classList.add('show');
});
</script>



@endsection
