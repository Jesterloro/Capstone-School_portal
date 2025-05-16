<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{asset ('css/student_login.css')}}">
</head>
<body>
    {{-- SCREEN LOADER --}}
    <div id="loading-screen">
        <img src="{{ asset('storage/ibsmalogo.png') }}" alt="Loading" class="loader-image">
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


    <section class="navbar">
        <div class="social">
            <a href="#"><i class="fa-brands fa-facebook"></i></a> |
            <a href="#"><i class="fa-brands fa-instagram"></i></a> |
            <a href="#"><i class="fa-brands fa-twitter"></i></a> |
            <a href="#"><i class="fa-brands fa-youtube"></i></a>
        </div>

            <div class="links">
                    <div class="left" style="display: flex; justify-content: space-between; gap: 20px; margin-right:30px;">
                        <a href="#"><strong>HOME</strong></a>
                        <a href="#course"><strong>COURSES</strong></a>
                        <a href="#activity"><strong>ACTIVITIES</strong></a>
                    </div>
                <div class="title">
                    <img src="{{ asset('image/logo ibs,a.png') }}" alt="" class="logos">
                    <h5 style="font-size: 20px;">
                        <span style="color: #16C47F">INSTITUTE</span> OF BUSINESS SCIENCE <br>
                        AND <span style="color: #16C47F">MEDICAL ARTS</span>
                    </h5>
                </div>
                <div class="right" style="display: flex; justify-content: space-between; gap: 20px; margin-left:30px;">
                    <!-- Mission Modal Trigger -->
                <a href="#" class="open-modal" data-modal="mission-modal"><strong>MISSION</strong></a>

                <!-- Vision Modal Trigger -->
                <a href="#" class="open-modal" data-modal="vision-modal"><strong>VISION</strong></a>

                <a href="#"><strong>ACTIVITIES</strong></a>
                </div>
            </div>

            <!-- Login Button (Triggers Modal) -->
            <button type="button" class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#customStudentLoginModal">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>


<!-- Custom Student Login Modal -->
<div class="modal fade student-login-modal" id="customStudentLoginModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customStudentLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="customStudentLoginModalLabel">
                    <i class="bi bi-person-lock"></i>Login
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
                            <span class="input-group-text bg-success text-white"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="student-email" class="form-control shadow-sm rounded-end" placeholder="Enter email" required autocomplete="email">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="student-password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="bi bi-lock"></i></span>
                            <input type="password" id="student-password" name="password" class="form-control shadow-sm rounded-end" placeholder="Enter password" required>
                            <button type="button" class="btn btn-outline-secondary toggle-student-password">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-3" onclick="showOtpModal(event)">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


    </section>

    <!-- Mission Modal -->
<div id="mission-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Mission</h2>
        <p>Our mission is to provide high-quality education and training in business and medical sciences, empowering students to become industry leaders.</p>
    </div>
</div>

<!-- Vision Modal -->
<div id="vision-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Vision</h2>
        <p>Our vision is to be a globally recognized institution for excellence in business, science, and medical education, fostering innovation and leadership.</p>
    </div>
</div>



    <section class="body" style="margin-top: 100px;">
        <!-- Background Section -->
<div class="background">
    <!-- Background Image Slider -->
    <div class="slider"></div>

    <!-- Dark Overlay -->
    <div class="overlay"></div>

         <!-- Aesthetic Heading in Top Left -->
    <h1 class="top-left-heading">Institute of Business, Science & Medical Arts</h1>

    <!-- Content Container Inside Background -->
    <div class="content-container">
        <!-- Content Box 1 -->
        <div class="content-box">
            <h2><i class="fa-solid fa-graduation-cap"></i> Learn with Experts</h2>
            <p>Gain knowledge from industry-leading instructors who provide hands-on training and real-world insights.</p>
            <a href="#" class="btn-custom">Explore Courses</a>
        </div>

        <!-- Content Box 2 -->
        <div class="content-box">
            <h2><i class="fa-solid fa-book-open"></i> Flexible Learning</h2>
            <p>Study at your own pace with our online and on-campus programs designed for working professionals.</p>
            <a href="#" class="btn-custom">View Programs</a>
        </div>

        <!-- Content Box 3 -->
        <div class="content-box">
            <h2><i class="fa-solid fa-users"></i> Join a Community</h2>
            <p>Connect with like-minded learners and professionals, participate in events, and expand your network.</p>
            <a href="#" class="btn-custom">Get Started</a>
        </div>
    </div>
</div>
    </section>

    <section id="course" class="courses" style="min-height: 100vh;">
       <!-- Courses Section Title -->
<div class="title">
    <h1 class="courses-title" style="margin-top: 100px;">Featured Courses</h1>
</div>

<!-- Courses Grid Container -->
<div class="courses-container" style="">
    <!-- Course 1: BS in Information Technology -->
    <div class="course-card">
        <i class="fa-solid fa-laptop-code course-icon"></i>
        <h2>Bachelor of Science in Information Technology</h2>
        <p>Develop skills in software development, cybersecurity, networking, and database management.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-it">View Course</button>
    </div>

    <!-- Course 2: Accountancy -->
    <div class="course-card">
        <i class="fa-solid fa-calculator course-icon"></i>
        <h2>Accountancy</h2>
        <p>Master financial accounting, auditing, taxation, and financial management principles.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-accountancy">View Course</button>
    </div>

    <!-- Course 3: Business Administration -->
    <div class="course-card">
        <i class="fa-solid fa-briefcase course-icon"></i>
        <h2>Business Administration</h2>
        <p>Learn the fundamentals of management, finance, and marketing for business success.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-business">View Course</button>
    </div>

    <!-- Course 4: Criminology -->
    <div class="course-card">
        <i class="fa-solid fa-user-shield course-icon"></i>
        <h2>Criminology</h2>
        <p>Study law enforcement, criminal justice, forensic science, and crime prevention techniques.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-criminology">View Course</button>
    </div>

    <!-- Course 5: Diploma in Midwifery -->
    <div class="course-card">
        <i class="fa-solid fa-heart-pulse course-icon"></i>
        <h2>Diploma in Midwifery</h2>
        <p>Gain expertise in maternal care, childbirth assistance, and postnatal support.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-midwifery">View Course</button>
    </div>
</div>

<!-- Course Modals (Custom for Courses Only) -->
<div id="course-modal-it" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <!-- Right Side: Course Logo & Name -->
            <div class="modal-right">
                <i class="fa-solid fa-laptop-code course-modal-icon"></i>
                <h2>Bachelor of Science in Information Technology</h2>
            </div>
            <!-- Left Side: Course Description -->
            <div class="modal-left">
                <p>This course prepares students with advanced skills in software engineering, web development, cybersecurity, data science, and network administration.</p>
            </div>
        </div>
    </div>
</div>

<div id="course-modal-accountancy" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                <i class="fa-solid fa-calculator course-modal-icon"></i>
                <h2>Accountancy</h2>
            </div>
            <div class="modal-left">
                <p>Gain proficiency in financial analysis, auditing, tax accounting, and business law, preparing you for a successful career in accounting and finance.</p>
            </div>
        </div>
    </div>
</div>

<div id="course-modal-business" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                <i class="fa-solid fa-briefcase course-modal-icon"></i>
                <h2>Business Administration</h2>
            </div>
            <div class="modal-left">
                <p>Learn effective leadership, marketing strategies, business operations, and financial management to excel in corporate or entrepreneurial roles.</p>
            </div>
        </div>
    </div>
</div>

<div id="course-modal-criminology" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                <i class="fa-solid fa-user-shield course-modal-icon"></i>
                <h2>Criminology</h2>
            </div>
            <div class="modal-left">
                <p>Study forensic science, criminal investigation, law enforcement, and correctional administration, equipping you for careers in public safety.</p>
            </div>
        </div>
    </div>
</div>

<div id="course-modal-midwifery" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                <i class="fa-solid fa-heart-pulse course-modal-icon"></i>
                <h2>Diploma in Midwifery</h2>
            </div>
            <div class="modal-left">
                <p>Gain hands-on training in maternal health, neonatal care, obstetric procedures, and community health education for a career in midwifery.</p>
            </div>
        </div>
    </div>
</div>


    </section>

    <section id="activity" class="school-activities" style="min-height: 100vh;">
<div class="title" >
      <!-- School Activities Section Title -->
<h1 class="school-activities-title" style="margin-top: 200px;">School Activities</h1>
</div>

<!-- Activities Section Wrapper -->
<div class="activities-wrapper">
    <div class="activities-container">

        <!-- Acquaintance Party -->
        <div class="activity-category">
            <h2 class="activities-subtitle">Acquaintance Party</h2>
            <div class="slider-container">
                <button class="prev-btn" onclick="prevSlide('acc')">&#10094;</button>
                <div class="activity-slider" id="acc">
                    <div class="activity">
                        <img src="{{ asset('acc/acc1.jpg') }}" alt="Welcome Night" class="clickable-image">
                        <p>Welcome Night</p>
                    </div>
                    <div class="activity">
                        <img src="{{ asset('acc/acc2.jpg') }}" alt="Dance Party" class="clickable-image">
                        <p>Dance Party</p>
                    </div>
                </div>
                <button class="next-btn" onclick="nextSlide('acc')">&#10095;</button>
            </div>
        </div>

        <!-- Intrams -->
        <div class="activity-category">
            <h2 class="activities-subtitle">Intrams</h2>
            <div class="slider-container">
                <button class="prev-btn" onclick="prevSlide('intrams')">&#10094;</button>
                <div class="activity-slider" id="intrams">
                    <div class="activity">
                        <img src="{{ asset('intrams/intrams1.jpg') }}" alt="Sports Event" class="clickable-image">
                        <p>Sports Competitions</p>
                    </div>
                    <div class="activity">
                        <img src="{{ asset('intrams/intrams2.jpg') }}" alt="Cheerdance" class="clickable-image">
                        <p>Cheerdance Competition</p>
                    </div>
                </div>
                <button class="next-btn" onclick="nextSlide('intrams')">&#10095;</button>
            </div>
        </div>

        <!-- Foundation Day -->
        <div class="activity-category">
            <h2 class="activities-subtitle">Foundation Day</h2>
            <div class="slider-container">
                <button class="prev-btn" onclick="prevSlide('foundation')">&#10094;</button>
                <div class="activity-slider" id="foundation">
                    <div class="activity">
                        <img src="{{ asset('foundation/foundation1.jpg') }}" alt="Parade" class="clickable-image">
                        <p>Grand Parade</p>
                    </div>
                    <div class="activity">
                        <img src="{{ asset('foundation/foundation2.jpg') }}" alt="Talent Show" class="clickable-image">
                        <p>Talent Show</p>
                    </div>
                </div>
                <button class="next-btn" onclick="nextSlide('foundation')">&#10095;</button>
            </div>
        </div>

    </div>
</div>

<!-- Image Viewer Modal -->
<div id="image-modal" class="image-modal">
    <span class="close-image-modal">&times;</span>
    <img class="modal-image" id="full-size-image">
</div>


    </section>
    <section class="footer">
        <div class="footer-container">
            <!-- School Info Section -->
            <!-- School Info Section -->
<div class="school">
    <div class="school-logo">
        <img src="{{ asset('image/logo ibs,a.png') }}" alt="IBSMA Logo">
        <h1>IBSMA</h1>
    </div>
    <p>Empowering students with the knowledge and skills to excel in the business and medical fields. Your success starts here!</p>
    <p><i class="fa-solid fa-phone"></i> Contact: +123 456 7890</p>
    <p><i class="fa-solid fa-envelope"></i> Email: info@ibsma.com</p>
    <p><i class="fa-solid fa-location-dot"></i> Address: 123 Main St, City, State, 12345</p>
</div>


            <!-- Quick Links Section -->
            <div class="quick-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Programs</a></li>
                    <li><a href="#">Admissions</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- Footer Social Media Section (Unique Class Name) -->
            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="footer-social-icons">
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Text -->
        <div class="footer-bottom">
            <p>&copy; 2024 IBSMA. All Rights Reserved.</p>
        </div>
    </section>

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpVerificationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
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
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                        <i class="bi bi-check-circle"></i> Verify OTP
                    </button>
                </form>

                <!-- Resend OTP Button -->
                <div class="mt-3">
                    <button id="resend-otp-btn" class="btn btn-link text-primary fw-semibold">
                        <i class="bi bi-arrow-repeat"></i> Resend OTP
                    </button>
                    <p id="resend-status" class="text-muted small"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle (Popper.js included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function () {
    // Array of image URLs
    const images = [
        "{{ asset('image/background.jpg') }}",
        "{{ asset('image/background2.jpg') }}",
        "{{ asset('image/background3.jpg') }}",
        "{{ asset('image/background4.jpg') }}"
    ];

    let currentIndex = 0;
    const slider = document.querySelector(".slider");

    function changeBackground() {
        // Add the slide-out animation
        slider.classList.add("slide-out");

        setTimeout(() => {
            // Change background image
            slider.style.backgroundImage = `url(${images[currentIndex]})`;

            // Reset animation and move to the next image
            slider.classList.remove("slide-out");
            slider.classList.add("slide-in");

            setTimeout(() => {
                slider.classList.remove("slide-in");
            }, 1000); // Ensure animation completes before removing class

            currentIndex = (currentIndex + 1) % images.length;
        }, 1000); // Match the animation duration
    }

    // Change the background every 5 seconds
    setInterval(changeBackground, 5000);

    // Initialize first image
    changeBackground();
});


// for activities-slider
document.addEventListener("DOMContentLoaded", function () {
    const sliders = document.querySelectorAll(".activity-slider");
    const modal = document.getElementById("image-modal");
    const modalImage = document.getElementById("full-size-image");
    const closeModal = document.querySelector(".close-image-modal");

    // Initialize sliders
    sliders.forEach(slider => {
        slider.dataset.index = 0; // Set initial index
    });

    window.nextSlide = function (id) {
        let slider = document.getElementById(id);
        let slides = slider.children.length;
        let index = parseInt(slider.dataset.index);
        index = (index + 1) % slides;
        slider.dataset.index = index;
        slider.style.transform = `translateX(-${index * 100}%)`;
    };

    window.prevSlide = function (id) {
        let slider = document.getElementById(id);
        let slides = slider.children.length;
        let index = parseInt(slider.dataset.index);
        index = (index - 1 + slides) % slides;
        slider.dataset.index = index;
        slider.style.transform = `translateX(-${index * 100}%)`;
    };

    // Open image modal on image click
    document.querySelectorAll(".clickable-image").forEach(image => {
        image.addEventListener("click", function () {
            modal.style.display = "flex";
            modalImage.src = this.src;
        });
    });

    // Close image modal
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Close modal when clicking outside the image
    modal.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});


// for course modals
document.addEventListener("DOMContentLoaded", function () {
    const openCourseButtons = document.querySelectorAll(".open-course-modal");
    const closeCourseButtons = document.querySelectorAll(".close-course-modal");
    const courseModals = document.querySelectorAll(".course-modal");

    // Open the course modal when button is clicked
    openCourseButtons.forEach(button => {
        button.addEventListener("click", function () {
            const modalId = this.getAttribute("data-modal");
            document.getElementById(modalId).style.display = "flex";
        });
    });

    // Close the course modal when close button is clicked
    closeCourseButtons.forEach(button => {
        button.addEventListener("click", function () {
            this.closest(".course-modal").style.display = "none";
        });
    });

    // Close the course modal when clicking outside modal content
    courseModals.forEach(modal => {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});


// for smooth scrolling
function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({
        behavior: "smooth"
    });
}

// open and closed modal for mission and vision
document.addEventListener("DOMContentLoaded", function () {
    const openModalButtons = document.querySelectorAll(".open-modal");
    const closeModalButtons = document.querySelectorAll(".close-modal");
    const modals = document.querySelectorAll(".custom-modal");

    // Open Modal
    openModalButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const modalId = this.getAttribute("data-modal");
            document.getElementById(modalId).style.display = "flex";
        });
    });

    // Close Modal
    closeModalButtons.forEach(button => {
        button.addEventListener("click", function () {
            this.closest(".custom-modal").style.display = "none";
        });
    });

    // Close Modal When Clicking Outside Content
    modals.forEach(modal => {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});

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
</script>

<script>
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
            // If login is successful, send OTP
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
</script>

<script>
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
</script>

{{-- FOR RESEND OTP --}}
<script>
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


</body>
</html>

