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
<link rel="stylesheet" href="{{asset ('css/login.css')}}">
</head>
<body>
    <!-- Success Notification -->
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <i class="fa fa-check-circle"></i> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Notification -->
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                let alert = document.querySelector(".alert");
                if (alert) {
                    alert.style.transition = "opacity 0.5s";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 500);
                }
            }, 2000);
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

                <a href="#"><strong>ABOUT US</strong></a>
                </div>
            </div>

           <!-- Login Button (Triggers Modal) -->
<button type="button" class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#customAdminLoginModal">
    <i class="fa fa-sign-in-alt"></i> Login
</button>


<!-- Custom Admin Login Modal -->
<div class="modal fade admin-login-modal" id="customAdminLoginModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customAdminLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="customAdminLoginModalLabel">
                    <i class="fa fa-user-lock"></i> Admin Login
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Login Form) -->
            <div class="modal-body p-4">
                @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="admin-username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="admin-username" class="form-control shadow-sm rounded-end" placeholder="Enter username" required autocomplete="username">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="admin-password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password" id="admin-password" name="password" class="form-control shadow-sm" placeholder="Enter password" required>
                            <button type="button" class="toggle-admin-password input-group-text bg-light" onclick="togglePassword()">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm mt-3">
                        <i class="fa fa-sign-in-alt"></i> Login
                    </button>

                    <!-- Forgot Password Link -->
                    <div class="text-center mt-3">
                        <a href="#" class="text-decoration-none text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            Forgot Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" data-bs-backdrop="false" data-bs-keyboard="false" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <!-- Modal Header -->
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fa fa-key"></i> Reset Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Reset Password Form) -->
            <div class="modal-body p-4">
                @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
                @endif

                <form action="{{ route('admin.password.reset') }}" method="POST">
                    @csrf
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="reset-username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-user"></i></span>
                            <input type="text" name="username" id="reset-username" class="form-control shadow-sm rounded-end" placeholder="Enter username" required autocomplete="username">
                        </div>
                    </div>

                    <!-- New Password Input -->
                    <div class="mb-3">
                        <label for="new-password" class="form-label fw-semibold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password" id="new-password" class="form-control shadow-sm rounded-end" placeholder="Enter new password" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success text-white"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password_confirmation" id="confirm-password" class="form-control shadow-sm rounded-end" placeholder="Confirm new password" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn w-100 fw-bold shadow-sm mt-3" style="background-color: #16C47F; color: white;">
                        <i class="fa fa-key"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>







    </section>
{{-- INAYOS MARCH 23 --}}
    <!-- Mission Modal -->
<div id="mission-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Mission</h2>
        <p>Institute of  Business, Science, and Medical Arts exists to develop well-rounded professionals with desirable traits excelling in leadership in education, business, medical, and technical fields through competent and relevant instruction, research, and the creation of center of knowledge for their chosen fields.</p>
    </div>
</div>

<!-- Vision Modal -->
<div id="vision-modal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal">&times;</span>
        <h2>Our Vision</h2>
        <p>Institute of Business, Science, and Medical Arts envision to sustain her leadership in health science, business, computer education whose graduates are exposed to holistic education, technology-based instruction, and vigorously pursue through research, the discovery of new knowledge responsive to the needs of the global community.</p>
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

    {{-- SECTION FOR COURSES --}}
    <section id="course" class="courses" style="min-height: 100vh;">
       <!-- Courses Section Title -->
<div class="title">
    <h1 class="courses-title" style="margin-top: 100px;">Featured Courses</h1>
</div>
{{-- INAYOS MARCH 23 --}}
<!-- Courses Grid Container -->
<div class="courses-container" style="">
    <!-- Course 1: BS in Information Technology -->
    <div class="course-card">
        <img src="{{ asset('image/IT.jpg') }}" alt="IT Course Icon" class="course-icon">
        <h2>Bachelor of Science in Information Technology</h2>
        <p>Develop skills in software development, cybersecurity, networking, and database management.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-it">View Course</button>
    </div>

    <!-- Course 2: Accountancy -->
    <div class="course-card">
        {{-- <i class="fa-solid fa-calculator course-icon"></i> --}}
        <img src="{{ asset('image/bsa.jpg') }}" alt="BSA Course Icon" class="course-icon">
        <h2>Accountancy</h2>
        <p>Master financial accounting, auditing, taxation, and financial management principles.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-accountancy">View Course</button>
    </div>

    <!-- Course 3: Business Administration -->
    <div class="course-card">
        {{-- <i class="fa-solid fa-briefcase course-icon"></i> --}}
        <img src="{{ asset('image/bsba.jpg') }}" alt="BSBA Course Icon" class="course-icon">
        <h2>Business Administration</h2>
        <p>Learn the fundamentals of management, finance, and marketing for business success.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-business">View Course</button>
    </div>

    <!-- Course 4: Criminology -->
    <div class="course-card">
        {{-- <i class="fa-solid fa-user-shield course-icon"></i> --}}
        <img src="{{ asset('image/crim.jpg') }}" alt="CRIM Course Icon" class="course-icon">
        <h2>Criminology</h2>
        <p>Study law enforcement, criminal justice, forensic science, and crime prevention techniques.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-criminology">View Course</button>
    </div>

    <!-- Course 5: Diploma in Midwifery -->
    {{-- <div class="course-card">
        <i class="fa-solid fa-heart-pulse course-icon"></i>
        <h2>Diploma in Midwifery</h2>
        <p>Gain expertise in maternal care, childbirth assistance, and postnatal support.</p>
        <button class="btn-custom open-course-modal" data-modal="course-modal-midwifery">View Course</button>
    </div> --}}
</div>
{{-- INAYOS MARCH 23 --}}
{{-- CUSTOM MODAL FOR BSIT --}}
<!-- Course Modals (Custom for Courses Only) -->
<div id="course-modal-it" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <!-- Right Side: Course Logo & Name -->
            <div class="modal-right">
                {{-- <i class="fa-solid fa-laptop-code course-modal-icon"></i> --}}
                <img src="{{ asset('image/IT.jpg') }}" alt="IT Course Icon" class="course-icon">
                <h2>Bachelor of Science in Information Technology</h2>
            </div>
            <!-- Left Side: Course Description -->
            <div class="modal-left">
                <p>This course prepares students with advanced skills in software engineering, web development, cybersecurity, data science, and network administration.</p>
            </div>
        </div>
    </div>
</div>
{{-- INAYOS MARCH 23 --}}
{{-- CUSTOM MODAL FOR BSA --}}
<div id="course-modal-accountancy" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                {{-- <i class="fa-solid fa-calculator course-modal-icon"></i> --}}
                <img src="{{ asset('image/bsa.jpg') }}" alt="BSA Course Icon" class="course-icon">
                <h2>Bachelor of Science in Accountancy</h2>
            </div>
            <div class="modal-left">
                <p>Gain proficiency in financial analysis, auditing, tax accounting, and business law, preparing you for a successful career in accounting and finance.</p>
            </div>
        </div>
    </div>
</div>
{{-- INAYOS MARCH 23 --}}
{{-- CUSTOM MODAL FOR BSBA --}}
<div id="course-modal-business" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                {{-- <i class="fa-solid fa-briefcase course-modal-icon"></i> --}}
                <img src="{{ asset('image/bsba.jpg') }}" alt="BSBA Course Icon" class="course-icon">
                <h2>Bachelor of Science in Business Administration</h2>
            </div>
            <div class="modal-left">
                <p>Learn effective leadership, marketing strategies, business operations, and financial management to excel in corporate or entrepreneurial roles.</p>
            </div>
        </div>
    </div>
</div>

{{-- INAYOS MARCH 23 --}}
{{-- CUSTOM MODAL FOR CRIM --}}
<div id="course-modal-criminology" class="course-modal">
    <div class="course-modal-content">
        <span class="close-course-modal">&times;</span>
        <div class="modal-container">
            <div class="modal-right">
                {{-- <i class="fa-solid fa-user-shield course-modal-icon"></i> --}}
                <img src="{{ asset('image/crim.jpg') }}" alt="CRIM Course Icon" class="course-icon">
                <h2>Bachelor of Science in Criminology</h2>
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

{{-- /* BAGONG LAGAY MARCH 23 */ --}}
{{-- SECTION FOR MISSION AND VISION --}}
<section class="mission-vision-section pt-5"> <!-- Added pt-5 for top margin -->
    <div class="container">
        <h1 class="mb-5 fw-bold display-4">MISSION and VISION</h1>
        <div class="mission-vision-container">
            <div class="mission-vision-box">
                <h2 class="fw-bold">Our Mission</h2>
                <p>Institute of Business, Science, and Medical Arts exists to develop well-rounded professionals with desirable traits excelling in leadership in education, business, medical, and technical fields through competent and relevant instruction, research, and the creation of a center of knowledge for their chosen fields.</p>
            </div>
            <div class="mission-vision-box">
                <h2 class="fw-bold">Our Vision</h2>
                <p>Institute of Business, Science, and Medical Arts envisions sustaining its leadership in health science, business, and computer education whose graduates are exposed to holistic education, technology-based instruction, and vigorously pursue through research, the discovery of new knowledge responsive to the needs of the global community.</p>
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

    {{-- FOOTER SECTION --}}
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
    <p><i class="fa-solid fa-location-dot"></i> Address: Francisco St., Marfrancisco, Pinamalayan, Oriental Mindoro</p>
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
                    {{-- <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a> --}}
                </div>
            </div>
        </div>

        <!-- Footer Bottom Text -->
        <div class="footer-bottom">
            <p>&copy; 2024 IBSMA. All Rights Reserved.</p>
        </div>
    </section>


    <!-- Bootstrap JS Bundle (Popper.js included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    const togglePassword = document.querySelector(".toggle-admin-password");
    const passwordField = document.getElementById("admin-password");

    if (!togglePassword || !passwordField) {
        console.error("Toggle button or password field not found!");
        return;
    }

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


</script>
</body>
</html>

