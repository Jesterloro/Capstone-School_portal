<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Portal - IBSMA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400..900;1,400..900&family=Jaini&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/login2.css')}}">
</head>

<!-- Loading Screen Container -->
<div id="loading-screen">
    <div class="logo-container">
        <!-- Spinning Logo -->
        <img src="{{ asset('image/ibsmalogo.png') }}" alt="Loading Logo" class="logo-spinner">
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let loader = document.getElementById("loading-screen");

        // Ensure the loader is visible on page load
        loader.style.display = "flex";

        // Hide loader when the page fully loads
        window.addEventListener("load", function () {
            setTimeout(() => {
                loader.style.display = "none";
            }, 1000); // 1-second delay for a smooth effect
        });

        // Show loader on form submission
        let form = document.querySelector("form");
        if (form) {
            form.addEventListener("submit", function () {
                loader.style.display = "flex";
            });
        }
    });
</script>
<body class="bg-light">

   <!-- ====================== -->
<!-- Unified Responsive Navbar -->
<!-- ====================== -->
<nav class="custom-navbar navbar navbar-expand-lg shadow-sm">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <!-- Logo & School Name for Mobile View -->
        <div class="d-flex align-items-center d-lg-none">
            <img src="{{ asset('image/logo ibs,a.png') }}" alt="School Logo" class="navbar-logo me-2">
            <span class="school-name fw-bold text-white" style="font-size: 15px; ">Institute of Business, <br>Science, And Medical Arts, Inc.</span>
        </div>

        <!-- School Name and Logo for Larger Screens -->
        <a href="#home" class="navbar-brand d-flex align-items-center d-none d-lg-flex">
            <img src="{{ asset('image/logo ibs,a.png') }}" alt="School Logo" class="navbar-logo me-2">
            <span class="school-name fw-bold text-white">Institute of Business, Science, and Medical Arts, Inc.</span>
        </a>

        <!-- Burger Menu Icon for Mobile (Right Side) -->
        <div class="burger-menu d-lg-none ms-auto" id="burgerMenu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

        <!-- Navbar Links for Desktop and for mobile-->
        <div class="collapse navbar-collapse navbar-links" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a href="#home" class="nav-link" style="margin-right: 20px; font-size: 17px;"><i class="fas fa-home me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="nav-link" style="margin-right: 20px; font-size: 17px;"><i class="fas fa-info-circle me-1"></i> About</a>
                </li>
                <li class="nav-item">
                    <a href="#courses" class="nav-link" style="margin-right: 20px; font-size: 17px;"><i class="fas fa-book me-1"></i> Courses</a>
                </li>
                <li class="nav-item">
                    <a href="#activities" class="nav-link" style="margin-right: 20px;font-size: 17px;"><i class="fas fa-phone-alt me-1"></i> Activities</a>
                </li>

                <!-- Login Button for Desktop -->
                <li class="nav-item">
                    <!-- Login Button (Triggers Modal) -->
<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#customStudentLoginModal"
style="background-color: white; color: black; transition: all 0.3s ease-in-out;"
onmouseover="this.style.backgroundColor='#1E293B'; this.style.color='white';"
onmouseout="this.style.backgroundColor='black'; this.style.color='white';">
<i class="fas fa-arrow-right-to-bracket me-1"></i> Login
</button>
                </li>
            </ul>
        </div>

        <!-- Mobile Menu for Smaller Screens -->
        <div id="mobileMenu" class="d-lg-none">
            <a href="#home" class="nav-link"><i class="fas fa-home me-1"></i> Home</a>
            <a href="#about" class="nav-link"><i class="fas fa-info-circle me-1"></i> About</a>
            <a href="#courses" class="nav-link"><i class="fas fa-book me-1"></i> Courses</a>
            <a href="#activities" class="nav-link"><i class="fas fa-phone-alt me-1"></i> Activities</a>
            <!-- Mobile Login Button -->
            <!-- Login Button (Triggers Modal) -->
<button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#customStudentLoginModal"
style="background-color: white; color: black; transition: all 0.3s ease-in-out;"
onmouseover="this.style.backgroundColor='#1E293B'; this.style.color='white';"
onmouseout="this.style.backgroundColor='black'; this.style.color='white';">
<i class="fas fa-arrow-right-to-bracket me-1"></i> Login
</button>
        </div>
    </div>
</nav>

<!-- Custom Student Login Modal -->
<div class="modal fade student-login-modal" id="customStudentLoginModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customStudentLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="customStudentLoginModalLabel">
                    <i class="fa fa-user-lock"></i>Login
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Login Form) -->
            <div class="modal-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <form action="{{ route('student.login.submit') }}" method="POST">
                    @csrf
                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="student-email" class="form-label fw-semibold">Email</label>
                        <div class="input-group">
                            <span style="background-color: #1E293B" class="input-group-text  text-white"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="student-email" class="form-control shadow-sm rounded-end" placeholder="Enter email" required autocomplete="email">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="student-password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span style="background-color: #1E293B" class="input-group-text  text-white"><i class="fas fa-lock"></i></span>
                            <input type="password" id="student-password" name="password" class="form-control shadow-sm rounded-end" placeholder="Enter password" required>
                            <button type="button" class="btn btn-outline-secondary toggle-student-password">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button style="background-color: #1E293B; color: white;" type="submit" class="btn w-100 fw-bold shadow-sm mt-3" onclick="showOtpModal(event)">
                        <i class="fa fa-sign-in-alt"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

 <!-- OTP Verification Modal -->
<!-- OTP Verification Modal -->
<div class="modal fade" id="otpVerificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div style="background-color: #1E293B;" class="modal-header  text-white border-0">
                <h5 class="modal-title" id="otpVerificationModalLabel">
                    <i class="bi bi-shield-lock"></i> OTP Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (OTP Form) -->
            <div class="modal-body p-4 text-center">
                <p>Please enter the OTP sent to your email.</p>

                <form action="{{ route('student.student-verify-otp') }}" method="POST">
                    @csrf
                    <input type="hidden" id="otp-email" name="email" value="{{ session('email') }}">
                    <div class="mb-3">
                        <label for="otp-input" class="form-label fw-semibold">Enter OTP</label>
                        <input type="text" id="otp-input" name="otp" class="form-control shadow-sm text-center" placeholder="Enter OTP" required maxlength="6">
                    </div>
                    <button style="background-color: #1E293B; color: white;" type="submit" class="btn w-100 fw-bold shadow-sm">
                        <i class="bi bi-check-circle"></i> Verify OTP
                    </button>
                </form>

                <!-- Resend OTP Button -->
                <div class="mt-3">
                    {{-- <button id="resend-otp-btn" class="btn btn-link text-primary fw-semibold">
                        <i class="bi bi-arrow-repeat"></i> Resend OTP
                    </button> --}}
                    <p id="resend-status" class="text-muted small"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // for login
document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.querySelector(".toggle-student-password");
    const passwordField = document.getElementById("student-password");

    togglePassword.addEventListener("click", function () {
        const icon = this.querySelector("i");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            passwordField.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    });
});

// OTP Modal Auto-show based on Session
document.addEventListener("DOMContentLoaded", function () {
        @if(session('show_otp_modal'))
            var otpModal = new bootstrap.Modal(document.getElementById('otpVerificationModal'));
            otpModal.show();
        @endif
    });

    function showOtpModal(event) {
    event.preventDefault(); // Prevent form submission

    var loginForm = event.target.closest("form");
    var loader = document.getElementById("loading-screen");

    // Show loader instantly (no fade-in initially)
    loader.style.display = "flex";

    // Start fade-in/out effect
    loader.classList.add("fade-pulse");

    // Perform AJAX request to validate login before sending OTP
    fetch(loginForm.action, {
        method: "POST",
        body: new FormData(loginForm),
        headers: {
            "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If login is successful, store the email in session and show OTP modal and Send OTP Request
            fetch('/student/send-otp', {
                method: "POST",
                body: new FormData(loginForm), // Reuse the same form data
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value
                }
            })
            .then(response => response.json())
            .then(otpData => {
                if (otpData.message === "OTP sent successfully!") {
                    // Fade out loader before showing modals
                    fadeOutLoader(() => {
                        var loginModal = new bootstrap.Modal(document.getElementById("customStudentLoginModal"));
                        var otpModal = new bootstrap.Modal(document.getElementById("otpVerificationModal"));

                         // Pass the email directly to the OTP modal
                         document.getElementById('otp-email').value = data.email; // Ensure email is set here

                        loginModal.hide();
                        otpModal.show();
                    });
                } else {
                    fadeOutLoader(() => alert("Failed to send OTP. Please try again."));
                }
            })
            .catch(error => fadeOutLoader(() => console.error("OTP Error:", error)));
        } else {
            fadeOutLoader(() => alert("Invalid login credentials"));
        }
    })
    .catch(error => fadeOutLoader(() => console.error("Login Error:", error)));
}

// Function to fade out loader smoothly
function fadeOutLoader(callback) {
    var loader = document.getElementById("loading-screen");
    loader.classList.remove("fade-pulse"); // Stop pulsing effect
    loader.style.opacity = "0"; // Start fade-out transition
    setTimeout(() => {
        loader.style.display = "none"; // Hide after transition
        if (callback) callback(); // Execute callback function if provided
    }, 800); // Matches CSS transition duration
}

// Hide loader on page load (optional)
window.onload = function () {
    document.getElementById("loading-screen").style.display = "none";
};

$(document).ready(function () {
        // Check if there's an error message from the session
        @if(session('error'))
            $('#otpVerificationModal').modal('show'); // Show the modal again
        @endif

        $('#verifyOtpForm').on('submit', function (e) {
            e.preventDefault();

            let email = $('#otp-email').val();
            let otp = $('#otp-input').val();

            if (!email) {
                alert("Email is missing. Please refresh the page and try again.");
                return;
            }

            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                email: email,
                otp: otp
            };

            $.ajax({
                url: '/verify-otp',
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.href = response.redirect_url;
                    } else {
                        alert(response.message);
                        $('#otpVerificationModal').modal('show'); // Keep the modal open
                    }
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'OTP verification failed.');
                    $('#otpVerificationModal').modal('show'); // Keep modal open on error
                }
            });
        });
    });

    document.getElementById('resend-otp-btn').addEventListener('click', function() {
    var button = this;
    var statusText = document.getElementById('resend-status');
    var email = document.getElementById('otp-email').value;

    if (!email) {
        statusText.textContent = "Email is missing. Please refresh and try again.";
        return;
    }

    button.disabled = true;
    statusText.textContent = "Resending OTP...";

    fetch("{{ route('student.resend-otp') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}" // Use Blade to inject token
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusText.textContent = "A new OTP has been sent to your email.";
        } else {
            statusText.textContent = "Failed to resend OTP. Please try again.";
        }
        setTimeout(() => {
            button.disabled = false;
            statusText.textContent = "";
        }, 60000); // 1-minute delay
    })
    .catch(error => {
        console.error("Resend OTP Error:", error);
        statusText.textContent = "An error occurred. Please try again.";
        button.disabled = false;
    });
});
</script>


    <!-- Auto Sliding Background -->
    <section id="home" class="slider-container">
        <div class="slider-img active" style="background-image: url({{ asset('image/background.jpg') }});"></div>
        <div class="slider-img" style="background-image: url({{ asset('image/background2.jpg') }});"></div>
        <div class="slider-img" style="background-image: url({{ asset('image/background3.jpg') }});"></div>

        <!-- School Name Overlay with Animation -->
<div class="school-name-overlay" id="animatedText">Institute of Business Science and Medical Arts</div>

        <!-- Overlay to Improve Readability -->
        <div class="slider-overlay"></div>


        <!-- Vision and Mission Section -->
        <section id="about" class="vision-mission-section d-flex justify-content-center align-items-center gap-3 flex-lg-row flex-column mt-5 mb-5">
            <div class="glass-box col-lg-5 p-4 text-center">
                <i class="fas fa-eye vision-icon"></i>
                <h4 class="mt-2">Our Vision</h4>
                <p>Our vision is to be a globally recognized institution for excellence in business, science, and medical education, fostering innovation and leadership.</p>
            </div>
            <div class="glass-box col-lg-5 p-4 text-center">
                <i class="fas fa-bullseye vision-icon"></i>
                <h4 class="mt-2">Our Mission</h4>
                <p>Our mission is to provide high-quality education and training in business and medical sciences, empowering students to become industry leaders.</p>
            </div>
        </section>
    </section>

   <!-- Course Section with Image Icons -->
<section id="courses" class="courses-section mt-5 mb-5">
    <div class="container">
        <h2 class="text-center text-green mb-5">Explore Our <span style="color:#16c47f">Featured Courses</span></h2>

        <!-- Course Grid Layout -->
        <div class="row justify-content-center g-4">

            <!-- Course 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/IT.jpg') }}" alt="IT Course" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Information Technology</h4>
                    <p>Develop skills in software development, cybersecurity, and database management.</p>
                </div>
            </div>

            <!-- Course 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/bsba.jpg') }}" alt="Business Admin" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Business Administration</h4>
                    <p>Gain insights into business strategies and leadership skills.</p>
                </div>
            </div>

            <!-- Course 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/midwife.jpg') }}" alt="Nursing" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">Diploma in Midewifery</h4>
                    <p>Prepare for a rewarding career in healthcare with clinical expertise.</p>
                </div>
            </div>

            <!-- Course 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/crim.jpg') }}" alt="Criminology" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Criminology</h4>
                    <p>Understand law enforcement and forensic science principles.</p>
                </div>
            </div>

            <!-- Course 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="course-card glass-card p-4 text-center">
                    <div class="course-icon">
                        <img src="{{ asset('image/bsa.jpg') }}" alt="Accountancy" class="course-img">
                    </div>
                    <h4 class="mt-3 course-title">BS in Accountancy</h4>
                    <p>Master accounting, auditing, and financial management.</p>
                </div>
            </div>



        </div>
    </div>
</section>

<!-- School Activities Section -->
<section id="activities" class="activities-section mt-5 mb-5">
    <div class="container">
        <h2 class="text-center text-green mb-5">Our Exciting <span style="color:#16c47f">School Activities</span></h2>

        <!-- Activities Grid Layout -->
        <div class="row justify-content-center g-4">

            <!-- Activity 1: Acquaintance Party -->
            <div class="col-lg-4 col-md-6">
                <div class="activity-card glass-card p-4 text-center">
                    <div id="acquaintanceSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('acc/acc1.jpg') }}" class="activity-img d-block w-100" alt="Acquaintance Party 1" onclick="openModal('{{ asset('acc/acc1.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('acc/acc2.jpg') }}" class="activity-img d-block w-100" alt="Acquaintance Party 2" onclick="openModal('{{ asset('acc/acc2.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('acc/acc3.jpg') }}" class="activity-img d-block w-100" alt="Acquaintance Party 3" onclick="openModal('{{ asset('acc/acc3.jpg') }}')">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3 activity-title">Acquaintance Party</h4>
                    <p>Welcome new students and build lasting friendships in a fun and exciting environment.</p>
                </div>
            </div>

            <!-- Activity 2: Intramurals -->
            <div class="col-lg-4 col-md-6">
                <div class="activity-card glass-card p-4 text-center">
                    <div id="intramuralsSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('intrams/intrams1.jpg') }}" class="activity-img d-block w-100" alt="Intramurals 1" onclick="openModal('{{ asset('intrams/intrams1.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('intrams/intrams2.jpg') }}" class="activity-img d-block w-100" alt="Intramurals 2" onclick="openModal('{{ asset('intrams/intrams2.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('intrams/intrams3.jpg') }}" class="activity-img d-block w-100" alt="Intramurals 3" onclick="openModal('{{ asset('intrams/intrams3.jpg') }}')">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3 activity-title">Intramurals</h4>
                    <p>Experience thrilling competitions and showcase your athletic skills.</p>
                </div>
            </div>

            <!-- Activity 3: Foundation Day -->
            <div class="col-lg-4 col-md-6">
                <div class="activity-card glass-card p-4 text-center">
                    <div id="foundationSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('foundation/foundation1.jpg') }}" class="activity-img d-block w-100" alt="Foundation Day 1" onclick="openModal('{{ asset('foundation/foundation1.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('foundation/foundation2.jpg') }}" class="activity-img d-block w-100" alt="Foundation Day 2" onclick="openModal('{{ asset('foundation/foundation2.jpg') }}')">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('foundation/foundation3.jpg') }}" class="activity-img d-block w-100" alt="Foundation Day 3" onclick="openModal('{{ asset('foundation/foundation3.jpg') }}')">
                            </div>
                        </div>
                    </div>
                    <h4 class="mt-3 activity-title">Foundation Day</h4>
                    <p>Celebrate the legacy of our institution with exciting activities and ceremonies.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Image Modal for Zoom -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0 d-flex justify-content-center align-items-center">
                <img id="modalImage" class="img-fluid rounded" alt="Activity Image">
            </div>
        </div>
    </div>
</div>





<!-- Footer Section with Links -->
<section id="about" class="modern-footer mt-5">
    <div class="container">
        <div class="row gy-4 justify-content-between align-items-start text-white">

            <!-- About Section Link in Footer -->
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-title">About IBSMA</h5>
                <p class="footer-description">
                    IBSMA is committed to delivering world-class education, fostering critical thinking, and empowering future leaders with quality knowledge.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-4">
                <h5 class="footer-title">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="#home" class="footer-link">Home</a></li>
                    <li><a href="#about" class="footer-link">About Us</a></li>
                    <li><a href="#courses" class="footer-link">Courses</a></li>
                    <li><a href="#activities" class="footer-link">Activities</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-4">
                <h5 class="footer-title">Contact Info</h5>
                <ul class="footer-info">
                    <li><i class="fas fa-map-marker-alt"></i> Francisco Street, Marfrancisco, Pinamalayan, Oriental Mindoro</li>
                    <li><i class="fas fa-envelope"></i> ibsmainc2003@gmail.com
                    </li>
                    <li><i class="fas fa-phone-alt"></i> +63 998 305 2912</li>
                </ul>
            </div>

            <!-- Social Media Links -->
            <div class="col-lg-3 col-md-6 text-center">
                <h5 class="footer-title">Connect with Us</h5>
                <div class="footer-social d-flex justify-content-center gap-3">
                    <a href="https://www.facebook.com/IBSMAnianAKO" class="social-link" data-bs-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>

        <!-- Divider Line -->
        <hr class="footer-divider my-4">

        <!-- Footer Bottom Section -->
        <div class="row align-items-center text-center">

                <p class="mb-0">&copy; <span id="currentYear"></span> IBSMA. All Rights Reserved.</p>

        </div>
    </div>
</section>

</footer>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        // Toggle Burger Menu
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            const burgerIcon = document.getElementById('burgerMenu');

            menu.classList.toggle('show');
            burgerIcon.classList.toggle('active');
        }

        // JavaScript for Automatic Image Slider
        let slideIndex = 0;
        const slides = document.querySelectorAll('.slider-img');

        function showSlides() {
            slides.forEach((slide) => {
                slide.classList.remove('active');
            });

            slideIndex++;
            if (slideIndex >= slides.length) {
                slideIndex = 0;
            }

            slides[slideIndex].classList.add('active');
        }

        // Change image every 5 seconds
        setInterval(showSlides, 5000);


        let currentIndex = 0;
    const totalSlides = document.querySelectorAll('.course-card').length;
    const slidesToShow = 3;
    const slider = document.querySelector('.course-slider');

    function slideCourses() {
        currentIndex++;
        if (currentIndex >= totalSlides / slidesToShow) {
            currentIndex = 0;
        }
        slider.style.transform = `translateX(-${currentIndex * 100 / slidesToShow}%)`;
    }

    // Auto-slide every 6 seconds
    setInterval(slideCourses, 6000);

    document.getElementById('currentYear').textContent = new Date().getFullYear();

         // Open Modal and Zoom Image
    function openModal(imageSrc) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    }

       // Sticky Navbar on Scroll
       window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.custom-navbar');
        if (window.scrollY > 20) {
            navbar.classList.add('sticky');
        } else {
            navbar.classList.remove('sticky');
        }
    });




    </script>
</body>
</html>
