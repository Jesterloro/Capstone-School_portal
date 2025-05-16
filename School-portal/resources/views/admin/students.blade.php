@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom flex-wrap">
            <!-- Left Section: Page Title -->
            <h1 class="h2 mb-3 mb-md-0">Students</h1>

            <!-- Right Section: Action Buttons -->
            <div class="d-flex gap-2 flex-wrap">
                <button style="background-color: #1E293B;" class="btn text-white" data-bs-toggle="modal"
                    data-bs-target="#addStudentModal">
                    Add Student
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#graduatedModal">
                    🎓 View Graduated Students
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentsModal">
                    Print Students
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#notEnrolledStudentModal">
                    ❌ View Not Enrolled Students
                </button>
            </div>
        </div>

     <!-- Not Enrolled Students Modal -->
<div class="modal fade" id="notEnrolledStudentModal" tabindex="-1" aria-labelledby="notEnrolledStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="notEnrolledStudentModalLabel">Not Enrolled Students</h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="filterDepartment" class="form-label">Filter by Department</label>
                        <select id="filterDepartment" class="form-select">
                            <option value="">All</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="filterYearLevel" class="form-label">Filter by Year Level</label>
                        <select id="filterYearLevel" class="form-select">
                            <option value="">All</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">{{ $i }} Year</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <form action="{{ route('students.bulkEnroll') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle" id="notEnrolledTable">
                            <thead class="table-success sticky-top">
                                <tr>
                                    <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Department</th>
                                    <th>Year Level</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notEnrolledStudents as $student)
                                    <tr data-department="{{ $student->department->name }}" data-year="{{ $student->year_level }}">
                                        <td>
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->student_id }}" class="student-checkbox form-check-input">
                                        </td>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->last_name }}, {{ $student->first_name }}</td>
                                        <td>{{ $student->department->name }}</td>
                                        <td>{{ $student->year_level }}</td>
                                        <td>{{ $student->cell_no }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Bulk Enroll Button -->
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Enroll Selected Students
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
       // Select All functionality
       document.getElementById('selectAll').addEventListener('change', function(e) {
        var checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = e.target.checked;
        });
    });
       document.addEventListener('DOMContentLoaded', function () {
        const departmentFilter = document.getElementById('filterDepartment');
        const yearFilter = document.getElementById('filterYearLevel');
        const tableRows = document.querySelectorAll('#notEnrolledTable tbody tr');

        function applyFilters() {
            const selectedDept = departmentFilter.value.toLowerCase();
            const selectedYear = yearFilter.value;

            tableRows.forEach(row => {
                const rowDept = row.getAttribute('data-department').toLowerCase();
                const rowYear = row.getAttribute('data-year');

                const matchesDept = selectedDept === '' || rowDept === selectedDept;
                const matchesYear = selectedYear === '' || rowYear === selectedYear;

                row.style.display = (matchesDept && matchesYear) ? '' : 'none';
            });
        }

        departmentFilter.addEventListener('change', applyFilters);
        yearFilter.addEventListener('change', applyFilters);
    });
</script>

       <!-- Success Toast Notification -->
    @if (session('success'))
    <div id="successToast" class="custom-toast bg-success text-white">
        <i class="bi bi-check-circle-fill me-2"></i>
        <span>{{ session('success') }}</span>
        <button class="btn-close-toast" onclick="hideToast('successToast')">&times;</button>
    </div>
    @endif

    <!-- Delete Toast Notification -->
    @if (session('deleted'))
    <div id="deleteToast" class="custom-toast bg-danger text-white">
        <i class="bi bi-trash-fill me-2"></i>
        <span>{{ session('deleted') }}</span>
        <button class="btn-close-toast" onclick="hideToast('deleteToast')">&times;</button>
    </div>
    @endif
    <style>
        /* Custom Toast Design */
.custom-toast {
    background: #1E293B;
    color: #FFFFFF;
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    padding: 12px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 1050;
    opacity: 0;
    transform: translateY(-50%);
    transition: all 0.5s ease-in-out;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
}

/* Toast Close Button */
.btn-close-toast {
    background: transparent;
    border: none;
    color: #FFFFFF;
    font-size: 1.25rem;
    cursor: pointer;
}

    </style>
    <script>
          document.addEventListener('DOMContentLoaded', function() {
        // Show and hide toast
        setTimeout(() => {
            document.querySelectorAll('.custom-toast').forEach(toast => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });
        }, 200);

        // Hide after 4 seconds
        setTimeout(() => {
            document.querySelectorAll('.custom-toast').forEach(toast => hideToast(toast.id));
        }, 4000);
    });

    // Hide toast manually
    function hideToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-50%)';
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    }

       // Modal animation
       document.getElementById('addClassModal').addEventListener('shown.bs.modal', function() {
        document.querySelector('#addClassModal .modal-content').style.transform = 'scale(1)';
    });
    document.getElementById('addClassModal').addEventListener('hidden.bs.modal', function() {
        document.querySelector('#addClassModal .modal-content').style.transform = 'scale(0.8)';
    });
    </script>


        {{-- <!-- Search Form: Positioned to the right with modern aesthetics -->
    <div class="row mb-2">
        <div class="col-12 d-flex justify-content-end">
            <form action="{{ route('students.index') }}" method="GET" class="d-flex w-auto">
                <div class="input-group">
                    <input type="text" name="search" class="form-control shadow-lg rounded-start border-light" placeholder="Search by name or student ID" value="{{ request('search') }}">
                    <button type="submit" class="btn  rounded-end shadow-lg" style="background-color: #16C47F; color: white; font-weight:600;">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

        <!-- Modern Minimalist Search Form with White Background & Dark Navy Blue Button -->
        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('students.index') }}" method="GET" id="custom-search-form"
                    class="d-flex w-100 align-items-center shadow-lg search-container"
                    style="
                background: #FFFFFF; /* White Background */
                padding: 12px;
                gap: 10px;
                border: 1px solid #E0E0E0;
                border-radius: 50px;
                transition: box-shadow 0.3s ease, transform 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            ">

                    <!-- Animated Search Icon -->
                    <div class="ps-3 d-flex align-items-center search-icon-container"
                        style="
                    color: #3B82F6;
                    font-size: 1.3rem;
                    transition: all 0.3s ease;
                ">
                        <i class="bi bi-search search-icon"></i>
                    </div>

                    <!-- Search Input with Smooth Transition -->
                    <input type="text" name="search" id="search-input" class="form-control search-input"
                        placeholder="Search by name or student ID..." value="{{ request('search') }}"
                        style="
                    background: transparent;
                    font-size: 1rem;
                    padding: 12px 15px;
                    border-radius: 50px;
                    outline: none;
                    color: #1E293B; /* Dark text for contrast */
                    border: none;
                    transition: all 0.3s ease-in-out;
                "
                        onfocus="this.parentNode.classList.add('focus-effect')"
                        onblur="this.parentNode.classList.remove('focus-effect')">

                    <!-- Stylish Search Button with Dark Navy Blue Gradient -->
                    <button type="submit" class="btn search-btn text-white shadow-sm"
                        style="
                    background: linear-gradient(135deg, #1E40AF, #3B82F6); /* Dark Navy Blue */
                    font-size: 0.95rem;
                    padding: 10px 20px;
                    border-radius: 50px;
                    transition: all 0.3s ease-in-out;
                    border: none;
                "
                        onmouseover="this.style.transform='translateY(-3px) scale(1.05)'; this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.5)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.1)';">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Inline CSS for Additional Effects -->
        <style>
            /* Focus Glow Effect on Search Input */
            .focus-effect {
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
                transform: translateY(-5px);
            }

            /* Search Icon Rotation and Scale on Focus */
            #search-input:focus+.search-icon-container .search-icon {
                color: #38BDF8;
                transform: scale(1.2) rotate(10deg);
            }

            /* Input Placeholder Color */
            #search-input::placeholder {
                color: #94A3B8;
            }

            /* Mobile Friendly Adjustments */
            @media (max-width: 768px) {
                .search-container {
                    flex-direction: column;
                    gap: 8px;
                    padding: 10px;
                    border-radius: 12px;
                }

                .search-btn {
                    width: 100%;
                    border-radius: 12px;
                }

                #search-input {
                    padding: 10px;
                    border-radius: 12px;
                }
            }
        </style>




        <!-- Modal for Graduated Students -->
        <div class="modal fade" id="graduatedModal" tabindex="-1" aria-labelledby="graduatedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content shadow">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="graduatedModalLabel">🎓 Graduated Students</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($graduates->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-success">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Full Name</th>
                                            <th>Department</th>
                                            <th>School Year</th>
                                            <th>Subjects and Grades</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($graduates as $student)
                                            <tr>
                                                <td>{{ $student->student_id }}</td>
                                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td>{{ $student->department->name ?? 'N/A' }}</td>
                                                <td>{{ $student->school_year }}</td>
                                                <td>
                                                    <!-- View Grades Button for each student -->
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewGradesModal{{ $student->student_id }}">
                                                        View Grades
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">No graduated students found yet.</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Template for Viewing Grades (Single Modal, Dynamic Content) -->
        @foreach ($graduates as $student)
            <div class="modal fade" id="viewGradesModal{{ $student->student_id }}" tabindex="-1"
                aria-labelledby="viewGradesModalLabel{{ $student->student_id }}" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content shadow-lg rounded-4">
                        <div class="modal-header text-white" style="background-color: #1E293B;">
                            <h5 class="modal-title fw-bold" id="viewGradesModalLabel{{ $student->student_id }}">
                                🎓 {{ $student->first_name }} {{ $student->last_name }}'s Grade Summary
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            @php
                                $groupedGrades = $student->grades->groupBy(['year_level', 'semester']);
                            @endphp

                            @forelse ($groupedGrades as $yearLevel => $semesters)
                                <h5 class="fw-bold text-white py-2 px-3 rounded mb-3" style="background-color: #334155;">
                                    📘 Year Level {{ $yearLevel }}
                                </h5>

                                <div class="row">
                                    <!-- First Semester -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-header fw-bold bg-primary text-white">
                                                📖 First Semester
                                            </div>
                                            <div class="card-body p-0">
                                                <table class="table table-bordered table-hover mb-0 text-center">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Subject</th>
                                                            <th>Grade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($semesters[1] ?? [] as $grade)
                                                            <tr>
                                                                <td>{{ $grade->subject->name }}</td>
                                                                <td>{{ $grade->grade ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                        @if (empty($semesters[1]))
                                                            <tr>
                                                                <td colspan="2" class="text-muted">No grades</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Second Semester -->
                                    <div class="col-md-6 mb-4">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-header fw-bold bg-primary text-white">
                                                📘 Second Semester
                                            </div>
                                            <div class="card-body p-0">
                                                <table class="table table-bordered table-hover mb-0 text-center">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Subject</th>
                                                            <th>Grade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($semesters[2] ?? [] as $grade)
                                                            <tr>
                                                                <td>{{ $grade->subject->name }}</td>
                                                                <td>{{ $grade->grade ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                        @if (empty($semesters[2]))
                                                            <tr>
                                                                <td colspan="2" class="text-muted">No grades</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summer -->
                                    @if (!empty($semesters[3]))
                                        <div class="col-md-12 mb-4 d-flex justify-content-center">
                                            <div class="card shadow-sm border-0" style="width: 50%;">
                                                <div class="card-header fw-bold bg-warning text-dark">
                                                    ☀️ Summer Class
                                                </div>
                                                <div class="card-body p-0">
                                                    <table class="table table-bordered table-hover mb-0 text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Subject</th>
                                                                <th>Grade</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($semesters[3] as $grade)
                                                                <tr>
                                                                    <td>{{ $grade->subject->name }}</td>
                                                                    <td>{{ $grade->grade ?? 'N/A' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="alert alert-info text-center">No grades available for this student.</div>
                            @endforelse
                        </div>

                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- Modal to Display Students -->
        <div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content shadow-sm animate__animated animate__fadeInDown">

                    <!-- Header -->
                    <div class="modal-header bg-light border-bottom">
                        <h5 class="modal-title fw-bold text-dark" id="studentsModalLabel">📋 All Active Students</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body px-4 py-3" style="max-height: 600px; overflow-y: auto;">

                        <!-- Department Filter Dropdown -->
                        <div class="mb-4">
                            <label for="departmentFilter" class="form-label">🎓 Select Department</label>
                            <select id="departmentFilter" class="form-select shadow-sm border-secondary"
                                onchange="filterStudents()">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Table to Display Students -->
                        <div class="table-responsive rounded">
                            <table class="table table-striped table-hover align-middle mb-0" id="studentTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>👤 Name</th>
                                        <th>🏫 Department</th>
                                        <th>🎯 Year Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allStudents as $student)
                                        <tr data-department="{{ $student->department->id }}">
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td>{{ $student->department->name }}</td>
                                            <td>{{ $student->year_level }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer bg-light border-top">
                        <button onclick="printStudentList()" class="btn btn-outline-primary px-4">
                            🖨️ Print Student List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Minimal styling -->
        <style>
            #studentsModal .modal-content {
                border-radius: 10px;
            }

            #studentTable tbody tr:hover {
                background-color: #f1f5f9;
                transition: background-color 0.3s ease;
                cursor: pointer;
            }
        </style>

        <!-- Animate.css CDN for fadeIn effect -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


        <!-- Add Bootstrap JS and Modal -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

        <script>
            // Filter students based on selected department
            function filterStudents() {
                var departmentId = document.getElementById('departmentFilter').value;
                var rows = document.querySelectorAll('#studentTable tbody tr');

                rows.forEach(function(row) {
                    if (departmentId === "" || row.getAttribute('data-department') === departmentId) {
                        row.style.display = ''; // Show the row
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            }

            // Print the filtered student list
            function printStudentList() {
                // Get the filtered table content inside the modal
                var tableContent = document.querySelector('#studentTable').outerHTML;

                // Open a new print window
                var printWindow = window.open('', '', 'height=600,width=800');

                // Write the content into the print window
                printWindow.document.write('<html><head><title>Print Student List</title>');
                printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; }');
                printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<h2>Student List</h2>');
                printWindow.document.write(tableContent); // Insert the table into the print window
                printWindow.document.write('</body></html>');

                // Close the document to complete the setup
                printWindow.document.close();

                // Trigger the print dialog
                printWindow.print();
            }
        </script>



        <!-- Table displaying student records -->
        <div class="row">
            <div class="col">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; border-bottom: 1px solid gray;">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="text-center"
                            style="background: linear-gradient(135deg, #0F172A, #1E293B); color: white; position: sticky; top: 0; z-index:1000;">
                            <tr>
                                <th style="padding: 15px; border-radius: 10px 0 0 0; font-weight: 800;">Student ID</th>
                                <th style="padding: 15px;font-weight: 800;">Last Name</th>
                                <th style="padding: 15px;font-weight: 800;">First Name</th>
                                <th style="padding: 15px;font-weight: 800;">Department</th>
                                <th style="padding: 15px;font-weight: 800;">Year Level</th>
                                <th style="padding: 15px;font-weight: 800;">Phone</th>
                                <th style="padding: 15px; font-weight: 800;">Enrollment Status</th>
                                <th style="border-radius: 0 10px 0 0;font-weight: 800;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="text-center" style="background-color: #f8f9fa; transition: all 0.3s;">
                                    <td style="padding: 15px; font-weight: 600;">{{ $student->student_id }}</td>
                                    <td style="padding: 15px; font-weight: 600;">{{ $student->last_name }}</td>
                                    <td style="padding: 15px; font-weight: 600;">{{ $student->first_name }}</td>
                                    <td style="padding: 15px; font-weight: 600;">{{ $student->department->name }}</td>
                                    <td style="padding: 15px; font-weight: 600;">
                                        {{ $student->year_level }}
                                        @if (!$student->regular && $student->year_level > 1)
                                            <span class="badge bg-danger">Irregular</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px; font-weight: 600;">{{ $student->cell_no }}</td>
                                    <td>{{ $student->is_enrolled ? 'Enrolled' : 'Not Enrolled' }}</td>
                                    <td>
                                        <!-- View Button -->
                                        <!-- Enrollment Toggle Button -->
                                        <form action="{{ route('students.toggleEnrollment', $student->student_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            @if ($student->is_enrolled)
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    style="border-radius: 8px;">
                                                    <i class="bi bi-check-circle"></i> Enrolled
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-secondary btn-sm"
                                                    style="border-radius: 8px;">
                                                    <i class="bi bi-x-circle"></i> Not Enrolled
                                                </button>
                                            @endif
                                        </form>

                                        <button class="btn btn-warning btn-sm"
                                            style="background-color: #1E293B; color: white; border-radius: 8px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewStudentModal{{ $student->student_id }}">
                                            <i class="bi bi-eye"></i> View Grades
                                        </button>
                                            <!-- Tutorial Subjects Button -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#tutorialModal{{ $student->student_id }}">
                                            📘 Tutorial
                                        </button>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm"
                                            style="background-color: #FFC107; color: white; border-radius: 8px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStudentModal{{ $student->student_id }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <!-- Delete Button -->
                                        <form action="{{ route('students.destroy', $student->student_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this student?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                        @php
                                            $groupedSubjects = $student->subjects
                                                ->sortBy(['pivot.year_level', 'pivot.semester'])
                                                ->groupBy(['pivot.year_level', 'pivot.semester']);
                                        @endphp

                                        <div class="modal fade" id="viewStudentModal{{ $student->student_id }}"
                                            tabindex="-1"
                                            aria-labelledby="viewStudentModalLabel{{ $student->student_id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content rounded-4 shadow-lg"
                                                    style="background-color: #ffffff; color: #1E293B; border: 1px solid #E0E0E0; animation: fadeIn 0.4s ease-out;">

                                                    <!-- Modal Header with Print Button -->
                                                    <div class="modal-header text-white"
                                                        style="background-color: #1E293B; position: relative; border-radius: 12px 12px 0 0;">
                                                        <h5 class="modal-title fw-bold"
                                                            id="viewStudentModalLabel{{ $student->student_id }}">
                                                            🎓 {{ $student->first_name }} {{ $student->last_name }} -
                                                            Grades
                                                        </h5>

                                                        <!-- Print Button -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-light shadow-sm position-absolute top-0 end-0 m-2"
                                                            onclick="openSemesterSelection('{{ $student->student_id }}')"
                                                            style="z-index: 1050; background-color: #FACC15; color: #1E293B; border-radius: 8px; transition: all 0.3s ease;">
                                                            <i class="bi bi-printer"></i> Print
                                                        </button>

                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="modal-body p-4"
                                                        id="printContent{{ $student->student_id }}"
                                                        style="background-color: #ffffff; border-radius: 0 0 12px 12px;">
                                                        <form
                                                            action="{{ route('admin.update.student.grades', $student->student_id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            @forelse ($groupedSubjects as $yearLevel => $semesters)
                                                                <!-- Year Level Header with Dark Navy Blue -->
                                                                <h4 class="fw-bold text-white year-level-heading mb-3"
                                                                    style="background-color: #1E293B; padding: 12px 15px; border-radius: 8px;">
                                                                    📚 Year Level: {{ $yearLevel }}
                                                                </h4>

                                                                <div class="semester-container">
                                                                    @foreach ($semesters as $semester => $subjects)
                                                                        <!-- Semester Card with Print Target -->
                                                                        <div class="semester-card {{ $semester == 3 ? 'summer' : '' }} mb-4 p-3 shadow-sm"
                                                                            id="year-{{ $yearLevel }}-semester-{{ $semester }}-{{ $student->student_id }}"
                                                                            style="background-color: #F9FAFB; color: #1E293B; border: 1px solid #E5E7EB; border-radius: 12px; transition: transform 0.3s ease, box-shadow 0.3s ease;">

                                                                            <!-- Semester Header in Dark Navy Blue -->
                                                                            <div class="semester-header fw-bold mb-2 text-white"
                                                                                style="background-color: #1E293B; padding: 10px 15px; border-radius: 8px;">
                                                                                @if ($semester == 3)
                                                                                    ☀️ Summer Class
                                                                                @else
                                                                                    📘 Semester {{ $semester }}
                                                                                @endif
                                                                            </div>

                                                                            <div class="table-responsive">
                                                                                <table
                                                                                    class="table custom-table table-bordered"
                                                                                    style="color: #1E293B; border-color: #E5E7EB;">
                                                                                    <thead>
                                                                                        <tr
                                                                                            style="background-color: #F3F4F6; color: #1E293B;">
                                                                                            <th>Code</th>
                                                                                            <th>Subject</th>
                                                                                            <th>Grade</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @foreach ($subjects as $subject)
                                                                                            @php
                                                                                                $grade =
                                                                                                    $subject->pivot
                                                                                                        ->grade ?? null;

                                                                                                // If grade is 0, show INC regardless of subject ID
                                                                                                if (
                                                                                                    !is_null($grade) &&
                                                                                                    $grade == 0
                                                                                                ) {
                                                                                                    $gradeValue = 'INC';
                                                                                                } else {
                                                                                                    // Show original grade if not zero/null
                                                                                                    $gradeValue = $grade;
                                                                                                }
                                                                                            @endphp
                                                                                            <tr>
                                                                                                <td>{{ $subject->code }}
                                                                                                </td>
                                                                                                <td>{{ $subject->name }}
                                                                                                </td>
                                                                                                <td>
                                                                                                    @php
                                                                                                        $borderColor =
                                                                                                            '#E5E7EB'; // Default border
                                                                                                        if (
                                                                                                            $gradeValue ===
                                                                                                            'INC'
                                                                                                        ) {
                                                                                                            $borderColor =
                                                                                                                '#FACC15'; // Yellow for INC
                                                                                                        } elseif (
                                                                                                            is_numeric(
                                                                                                                $gradeValue,
                                                                                                            ) &&
                                                                                                            $gradeValue <=
                                                                                                                74.49
                                                                                                        ) {
                                                                                                            $borderColor =
                                                                                                                '#EF4444'; // Red for failing grades
                                                                                                        } elseif (
                                                                                                            is_numeric(
                                                                                                                $gradeValue,
                                                                                                            ) &&
                                                                                                            $gradeValue >=
                                                                                                                74.5
                                                                                                        ) {
                                                                                                            $borderColor =
                                                                                                                '#10B981'; // Green for passing grades
                                                                                                        }
                                                                                                    @endphp

                                                                                                    @if ($gradeValue === 'INC' || $gradeValue === 'N/A')
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            class="form-control grade-input"
                                                                                                            value="{{ $gradeValue }}"
                                                                                                            style="background-color: #F9FAFB; color: #1E293B; border: 2px solid {{ $borderColor }}; border-radius: 8px; padding: 5px 8px;">
                                                                                                    @else
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            name="subjects[{{ $subject->id }}][grade]"
                                                                                                            class="form-control grade-input"
                                                                                                            value="{{ $gradeValue }}"
                                                                                                            placeholder="N/A"
                                                                                                            step="0.01"
                                                                                                            style="background-color: #F9FAFB; color: #1E293B; border: 2px solid {{ $borderColor }}; border-radius: 8px; padding: 5px 8px;">
                                                                                                    @endif
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @empty
                                                                <p class="text-muted text-center">No grades available.</p>
                                                            @endforelse

                                                            <!-- Save Button -->
                                                            <div class="text-end mt-4">
                                                                <button type="submit" class="btn fw-bold shadow-sm px-4"
                                                                    style="background-color: #1E293B; color: white; border-radius: 8px; transition: all 0.3s ease;">
                                                                    💾 Save Grades
                                                                </button>
                                                            </div>
                                                        </form>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

<!-- Tutorial Modal -->
<div class="modal fade" id="tutorialModal{{ $student->student_id }}" tabindex="-1"
    aria-labelledby="tutorialModalLabel{{ $student->student_id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header text-white" style="background-color: #1E293B;">
                <h5 class="modal-title fw-bold" id="tutorialModalLabel{{ $student->student_id }}">
                    📘 Failed Subjects – Assign for Tutorial
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php
                $tutorialSubjects = [];

                foreach ($student->subjects as $subject) {
                    $subjectId = $subject->id;
                    $grade = $subject->pivot->grade;
                    $semester = $subject->pivot->semester;
                    $yearLevel = $subject->pivot->year_level;

                    // Only handle failed grades
                    if (is_numeric($grade) && $grade <= 74.49) {
                        // Check if this subject already has a newer re-enrollment
                        $newGrade = null;
                        foreach ($student->subjects as $check) {
                            if (
                                $check->id == $subjectId &&
                                ($check->pivot->year_level > $yearLevel ||
                                ($check->pivot->year_level == $yearLevel && $check->pivot->semester > $semester)) &&
                                is_numeric($check->pivot->grade)
                            ) {
                                $newGrade = $check->pivot->grade;
                                break; // stop checking once a retake is found
                            }
                        }

                        // 🛑 Skip subject if already retaken
                        if ($newGrade !== null) {
                            continue;
                        }

                        // Prepare re-enrollment semester/year
                        $nextSemester = $semester == 1 ? 2 : 1;
                        $nextYear = $semester == 1 ? $yearLevel : $yearLevel + 1;

                        $tutorialSubjects[$subject->id] = [
                            'id' => $subjectId,
                            'code' => $subject->code,
                            'name' => $subject->name,
                            'original_grade' => $grade,
                            'reEnrollSemester' => $nextSemester,
                            'reEnrollYear' => $nextYear,
                        ];
                    }
                }
                @endphp



                @if (count($tutorialSubjects))
                    <form action="{{ route('admin.assign.tutorial', $student->student_id) }}" method="POST">
                        @csrf
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Select</th>
                                    <th>Code</th>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Re-Enroll Year</th>
                                    <th>Re-Enroll Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tutorialSubjects as $sub)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="subjects[]" value="{{ $sub['id'] }}">
                                            <input type="hidden" name="re_enroll_data[{{ $sub['id'] }}][year_level]" value="{{ $sub['reEnrollYear'] }}">
                                            <input type="hidden" name="re_enroll_data[{{ $sub['id'] }}][semester]" value="{{ $sub['reEnrollSemester'] }}">
                                        </td>
                                        <td>{{ $sub['code'] }}</td>
                                        <td>{{ $sub['name'] }}</td>
                                        <td style="color: #EF4444; font-weight: bold;">{{ $sub['original_grade'] }}</td>
                                        <td>{{ $sub['reEnrollYear'] }}</td>
                                        <td>{{ $sub['reEnrollSemester'] == 1 ? '1st' : '2nd' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-warning fw-bold px-4 rounded-3 shadow">
                                ➕ Assign Selected to Tutorial
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-success text-center fw-bold">
                        🎉 No failed subjects for tutorial.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


                                        <!-- Semester & Year Selection Custom Modal -->
                                        <div class="custom-modal-overlay" id="customModalOverlay">
                                            <div class="custom-modal-content shadow-lg">
                                                <div class="custom-modal-header">
                                                    <h5 class="modal-title text-white fw-bold">🖨️ Select Year & Semester
                                                        to Print</h5>
                                                    <button type="button" class="btn-close-white"
                                                        onclick="closeCustomModal()"></button>
                                                </div>
                                                <div class="custom-modal-body">
                                                    <!-- Year Level Selection -->
                                                    <select id="yearSelect" class="form-select mb-3">
                                                        <option value="all">📚 Print All Years</option>
                                                        <option value="1">1st Year</option>
                                                        <option value="2">2nd Year</option>
                                                        <option value="3">3rd Year</option>
                                                        <option value="4">4th Year</option>
                                                    </select>

                                                    <!-- Semester Selection -->
                                                    <select id="semesterSelect" class="form-select">
                                                        <option value="all">📚 Print All Semesters</option>
                                                        <option value="1">📘 Semester 1</option>
                                                        <option value="2">📘 Semester 2</option>
                                                        <option value="3">☀️ Summer Class</option>
                                                    </select>
                                                </div>
                                                <div class="custom-modal-footer">
                                                    <button type="button" class="btn btn-success shadow-sm"
                                                        onclick="printSelectedSemester()">Print</button>
                                                    <button type="button" class="btn btn-secondary shadow-sm"
                                                        onclick="closeCustomModal()">Cancel</button>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- JavaScript for Custom Modal and Print -->
                                        <script>
                                            let selectedStudentId = null;

                                            function openSemesterSelection(studentId) {
                                                selectedStudentId = studentId;
                                                document.getElementById('customModalOverlay').style.display = 'flex';
                                            }

                                            function closeCustomModal() {
                                                document.getElementById('customModalOverlay').style.display = 'none';
                                            }

                                            function printSelectedSemester() {
                                                const selectedYear = document.getElementById('yearSelect').value;
                                                const selectedSemester = document.getElementById('semesterSelect').value;
                                                const studentId = selectedStudentId;

                                                let printContent = '';

                                                if (selectedYear === 'all' && selectedSemester === 'all') {
                                                    const allCards = document.querySelectorAll(`#printContent${studentId} .semester-card`);
                                                    allCards.forEach(card => {
                                                        const clonedCard = card.cloneNode(true);
                                                        applyGradeConversion(clonedCard); // ✅ Apply conversion only for print
                                                        printContent += clonedCard.outerHTML;
                                                    });
                                                } else {
                                                    const semesterCards = document.querySelectorAll(`#printContent${studentId} .semester-card`);
                                                    semesterCards.forEach(card => {
                                                        const yearMatches = selectedYear === 'all' || card.id.includes(`year-${selectedYear}`);
                                                        const semesterMatches = selectedSemester === 'all' || card.id.includes(
                                                            `semester-${selectedSemester}`);
                                                        if (yearMatches && semesterMatches) {
                                                            const clonedCard = card.cloneNode(true);
                                                            applyGradeConversion(clonedCard); // ✅ Apply conversion only for print
                                                            printContent += clonedCard.outerHTML;
                                                        }
                                                    });

                                                    if (printContent === '') {
                                                        alert('No content found for the selected Year Level and Semester!');
                                                        return;
                                                    }
                                                }

                                                // 📅 Get Current Date in "Month DD, YYYY" format
                                                const currentDate = new Date().toLocaleDateString('en-US', {
                                                    year: 'numeric',
                                                    month: 'long',
                                                    day: '2-digit',
                                                });

                                                // 🎯 Open new print window with converted grades and footer
                                                const printWindow = window.open('', '', 'width=800,height=600');
                                                printWindow.document.write(`
                                        <html>
                                            <head>
                                                <title>Student Grades</title>
                                                <style>
                                                    body {
                                                        font-family: 'Arial', sans-serif;
                                                        margin: 20px;
                                                        background-color: #fff;
                                                    }
                                                    .header-section {
                                                        text-align: center;
                                                        margin-bottom: 10px;
                                                    }
                                                    .header-section h3 {
                                                        margin: 0;
                                                        font-size: 20px;
                                                        font-weight: bold;
                                                    }
                                                    .header-section p {
                                                        margin: 2px 0;
                                                        font-size: 12px;
                                                        color: #6C757D;
                                                    }
                                                    .student-info {
                                                        margin-bottom: 10px;
                                                        font-size: 12px;
                                                        text-align: left;
                                                    }
                                                    /* 🎯 Stacked Layout: First Semester at Top, Second Below */
                                                    .semester-wrapper {
                                                        display: flex;
                                                        flex-direction: column;
                                                        gap: 10px;
                                                        margin-bottom: 10px;
                                                    }
                                                    .semester-card {
                                                        border: 1px solid #ddd;
                                                        padding: 12px;
                                                        border-radius: 8px;
                                                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
                                                        break-inside: avoid;
                                                        width: 100%;
                                                        margin-bottom: 10px;
                                                    }
                                                    .semester-header {
                                                        background-color: #1E293B;
                                                        color: white;
                                                        padding: 8px;
                                                        text-align: center;
                                                        font-size: 14px;
                                                        font-weight: bold;
                                                    }
                                                    .custom-table th,
                                                    .custom-table td {
                                                        padding: 6px;
                                                        border: 1px solid #ddd;
                                                        text-align: center;
                                                    }
                                                    /* ✅ Remove styles from original grades during print */
                                                    .grade-input {
                                                        background-color: transparent !important;
                                                        color: #1E293B !important;
                                                        border: none !important;
                                                        box-shadow: none !important;
                                                    }
                                                    /* 🎯 Preserve Final Grade colors */
                                                    .converted-grade span.text-success {
                                                        color: #10B981 !important;
                                                        font-weight: bold;
                                                    }
                                                    .converted-grade span.text-danger {
                                                        color: #EF4444 !important;
                                                        font-weight: bold;
                                                    }
                                                    .converted-grade span.text-warning {
                                                        color: #FACC15 !important;
                                                        font-weight: bold;
                                                    }
                                                    .converted-grade span.text-muted {
                                                        color: #6C757D !important;
                                                    }
                                                    /* 📝 Footer Styles */
                                                    .footer {
                                                        display: flex;
                                                        justify-content: space-between;
                                                        margin-top: 40px; /* ⬇️ Moved down for better spacing */
                                                        font-size: 12px;
                                                    }
                                                    .footer-left {
                                                        text-align: left;
                                                    }
                                                    .footer-right {
                                                        text-align: right;
                                                    }
                                                    .registrar-name {
                                                        font-weight: bold;
                                                        margin-top: 6px;
                                                    }
                                                    .registrar-title {
                                                        font-size: 12px;
                                                        color: #6C757D;
                                                        margin-top: -9px;
                                                    }
                                                    @media print {
                                                        body {
                                                            font-size: 12px;
                                                            margin: 10px;
                                                        }
                                                        .semester-wrapper {
                                                            display: flex;
                                                            flex-direction: column;
                                                            gap: 5px;
                                                        }
                                                        .semester-card {
                                                            page-break-inside: avoid;
                                                            margin-bottom: 5px;
                                                        }
                                                        @page {
                                                            size: Legal portrait; /* 📚 Legal size (8.5 x 14 inches) */
                                                            margin: 15px;
                                                        }
                                                    }
                                                </style>
                                            </head>
                                            <body>
                                                <!-- 🏫 Header Section -->
                                                <div class="header-section">
                                                    <h3>Institute of Business Science and Medical Arts</h3>
                                                    <p>Francisco St., Marfrancisco, Pinamalayan, Or. Mindoro</p>
                                                </div>

                                                <!-- 📚 Student Information -->
                                                <div class="student-info">
                                                    <p><strong>Student Name:</strong> ${document.querySelector(`#viewStudentModalLabel${studentId}`).innerText.replace(' - Grades', '')}</p>
                                                    <p><strong>Course:</strong> ${getStudentCourse(studentId)}</p>
                                                    <p><strong>Year Level:</strong> ${getStudentYearLevel(studentId)}</p>
                                                </div>

                                                <!-- 📊 Grades Table with Semester on Top and Bottom -->
                                                <div class="semester-wrapper">
                                                    ${printContent}
                                                </div>

                                                <!-- 📄 Footer Section -->
                                                <div class="footer">
                                                    <div class="footer-left">
                                                        <p><strong>Date:</strong> ${currentDate}</p>
                                                    </div>
                                                    <div class="footer-right">
                                                        <p class="registrar-name">MICHELLE F. CAHILIG</p>
                                                        <p class="registrar-title">Registrar</p>
                                                    </div>
                                                </div>
                                            </body>
                                        </html>
                                    `);

                                                printWindow.document.close();
                                                printWindow.print();
                                                closeCustomModal();
                                            }

                                            // 🎯 Apply Grade Conversion ONLY for Print
                                            function applyGradeConversion(card) {
                                                const gradeCells = card.querySelectorAll('td input.grade-input');
                                                const headerRow = card.querySelector('thead tr');

                                                // ✅ Add "Final Grade" header only if not already added
                                                if (!headerRow.querySelector('.final-grade-header')) {
                                                    const finalGradeHeader = document.createElement('th');
                                                    finalGradeHeader.innerText = 'Final Grade';
                                                    finalGradeHeader.className = 'final-grade-header text-center';
                                                    headerRow.appendChild(finalGradeHeader);
                                                }

                                                gradeCells.forEach(input => {
                                                    const gradeValue = input.value.trim();
                                                    let convertedGrade, gradeClass;

                                                    // ✅ Handle INC as original and final grade
                                                    if (gradeValue === 'INC' || gradeValue === 'N/A') {
                                                        convertedGrade = 'INC';
                                                        gradeClass = 'text-warning fw-bold';
                                                    } else {
                                                        const parsedGrade = parseFloat(gradeValue);
                                                        if (!isNaN(parsedGrade)) {
                                                            const roundedGrade = (parsedGrade - Math.floor(parsedGrade) >= 0.50) ? Math.ceil(
                                                                parsedGrade) : Math.floor(parsedGrade);

                                                            // Conversion Logic
                                                            if (roundedGrade === 0) {
                                                                convertedGrade = 'INC';
                                                                gradeClass = 'text-warning fw-bold';
                                                            } else if (roundedGrade >= 75) {
                                                                const gradeConversion = {
                                                                    100: '1.0',
                                                                    99: '1.1',
                                                                    98: '1.2',
                                                                    97: '1.3',
                                                                    96: '1.4',
                                                                    95: '1.5',
                                                                    94: '1.6',
                                                                    93: '1.7',
                                                                    92: '1.8',
                                                                    91: '1.9',
                                                                    90: '2.0',
                                                                    89: '2.1',
                                                                    88: '2.2',
                                                                    87: '2.3',
                                                                    86: '2.4',
                                                                    85: '2.5',
                                                                    84: '2.6',
                                                                    83: '2.7',
                                                                    82: '2.8',
                                                                    81: '2.9',
                                                                    80: '3.0',
                                                                    79: '3.1',
                                                                    78: '3.2',
                                                                    77: '3.3',
                                                                    76: '3.4',
                                                                    75: '3.5'
                                                                };
                                                                convertedGrade = gradeConversion[roundedGrade] || 'N/A';
                                                                gradeClass = 'text-success fw-bold';
                                                            } else {
                                                                convertedGrade = '5.0';
                                                                gradeClass = 'text-danger fw-bold';
                                                            }
                                                        } else {
                                                            convertedGrade = 'N/A';
                                                            gradeClass = 'text-muted';
                                                        }
                                                    }

                                                    // Create a new cell with converted grade
                                                    const convertedCell = document.createElement('td');
                                                    convertedCell.innerHTML = `<span class="${gradeClass}">${convertedGrade}</span>`;
                                                    convertedCell.className = 'converted-grade text-center';

                                                    // Add converted grade cell after the original grade cell
                                                    input.closest('tr').appendChild(convertedCell);
                                                });
                                            }

                                            // 🎓 Get Student Course (You can update this logic based on your DB structure)
                                            function getStudentCourse(studentId) {
                                                // Example: Replace this with your backend logic
                                                return 'Bachelor of Science in Information Technology'; // Default value for demo
                                            }

                                            // 📚 Get Student Year Level
                                            function getStudentYearLevel(studentId) {
                                                // Example: Replace this with your backend logic
                                                return '4th Year'; // Default value for demo
                                            }
                                        </script>










                                        <style>
                                            /* Modal Content */
                                            #viewStudentModal .modal-dialog {
                                                max-width: 95vw;
                                            }

                                            #viewStudentModal .modal-content {
                                                border-radius: 14px;
                                                overflow: hidden;
                                                background-color: #fff;
                                            }

                                            #viewStudentModal .modal-body {
                                                max-height: 80vh;
                                                overflow-y: auto;
                                                padding: 20px;
                                                background-color: #f1f8f9;
                                            }

                                            /* Year Level Heading */
                                            .year-level-heading {
                                                font-size: 26px;
                                                font-weight: bold;
                                                color: #333;
                                                margin-top: 25px;
                                                padding-bottom: 10px;
                                            }

                                            /* Semester Container */
                                            .semester-container {
                                                display: grid;
                                                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                                                gap: 20px;
                                                margin-top: 20px;
                                            }

                                            /* Semester Card */
                                            .semester-card {
                                                background: #fff;
                                                border: 1px solid #ddd;
                                                border-radius: 12px;
                                                overflow: hidden;
                                                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
                                                transition: transform 0.3s ease, box-shadow 0.3s ease;
                                            }

                                            .semester-card:hover {
                                                transform: translateY(-5px);
                                                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
                                            }

                                            /* Semester Header */
                                            .semester-header {
                                                background-color: #1E293B;
                                                color: white;
                                                padding: 14px 20px;
                                                font-size: 18px;
                                                font-weight: bold;
                                                text-align: center;
                                            }

                                            /* Custom Table for Subjects */
                                            .custom-table {
                                                width: 100%;
                                                border-collapse: collapse;
                                            }

                                            .custom-table th,
                                            .custom-table td {
                                                padding: 14px;
                                                text-align: center;
                                                border-bottom: 1px solid #ddd;
                                            }

                                            .custom-table thead {
                                                background: #e8f5e9;
                                                font-size: 1rem;
                                                color: #333;
                                            }

                                            .custom-table tbody tr:hover {
                                                background: #f1f8f9;
                                            }

                                            /* Grade Input Field */
                                            .grade-input {
                                                width: 85px;
                                                padding: 8px;
                                                font-size: 1rem;
                                                text-align: center;
                                                border: 2px solid #ddd;
                                                border-radius: 8px;
                                                transition: all 0.3s ease-in-out;
                                            }

                                            .grade-input:focus {
                                                border-color: #4CAF50;
                                                outline: none;
                                                box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
                                            }

                                            /* Highlight Missing Grades */
                                            .table-danger td {
                                                background: #ffebee !important;
                                                color: #d32f2f;
                                            }

                                            /* Save Button with Glow */
                                            .custom-save-btn {
                                                background: linear-gradient(135deg, #4CAF50, #1E88E5);
                                                border: none;
                                                padding: 12px 24px;
                                                font-weight: bold;
                                                color: white;
                                                border-radius: 50px;
                                                transition: all 0.3s ease;
                                                box-shadow: 0 4px 12px rgba(22, 196, 127, 0.2);
                                            }

                                            .custom-save-btn:hover {
                                                background: linear-gradient(135deg, #1E88E5, #4CAF50);
                                                transform: translateY(-2px);
                                                box-shadow: 0 6px 15px rgba(66, 165, 245, 0.2);
                                            }

                                            /* Summer Class Center Alignment */
                                            .semester-card.summer {
                                                grid-column: span 2;
                                                margin: 0 auto;
                                                width: 60%;
                                            }

                                            /* Responsive Design for Small Screens */
                                            @media (max-width: 768px) {
                                                .semester-container {
                                                    grid-template-columns: 1fr;
                                                }

                                                .semester-card.summer {
                                                    grid-column: span 1;
                                                    width: 100%;
                                                }

                                                .year-level-heading {
                                                    font-size: 20px;
                                                }
                                            }

                                            /* Clean Print Optimization */
                                            @media print {
                                                body {
                                                    background: white !important;
                                                }

                                                .no-print {
                                                    display: none !important;
                                                }

                                                .semester-card {
                                                    border: 1px solid #ddd;
                                                    box-shadow: none;
                                                }

                                                .semester-header {
                                                    background-color: #4CAF50 !important;
                                                }
                                            }



                                            .custom-modal-overlay {
                                                position: fixed;
                                                top: 0;
                                                left: 0;
                                                width: 100vw;
                                                height: 100vh;
                                                background: rgba(0, 0, 0, 0.6);
                                                z-index: 1055;
                                                display: none;
                                                justify-content: center;
                                                align-items: center;
                                            }

                                            /* Modal Content */
                                            .custom-modal-content {
                                                background: #fff;
                                                width: 450px;
                                                border-radius: 12px;
                                                overflow: hidden;
                                                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
                                                animation: fadeIn 0.3s ease-out;
                                            }

                                            /* Modal Header */
                                            .custom-modal-header {
                                                background-color: #1E293B;
                                                color: white;
                                                padding: 14px 20px;
                                                font-size: 18px;
                                                font-weight: bold;
                                                display: flex;
                                                justify-content: space-between;
                                                align-items: center;
                                            }

                                            /* Modal Body */
                                            .custom-modal-body {
                                                padding: 20px;
                                            }

                                            /* Modal Footer */
                                            .custom-modal-footer {
                                                padding: 14px 20px;
                                                background-color: #f1f8f9;
                                                display: flex;
                                                justify-content: space-between;
                                            }

                                            /* Close Button */
                                            .btn-close-white {
                                                background: none;
                                                border: none;
                                                color: white;
                                                font-size: 18px;
                                                cursor: pointer;
                                            }

                                            /* Modal Animation */
                                            @keyframes fadeIn {
                                                from {
                                                    opacity: 0;
                                                    transform: translateY(-20px);
                                                }

                                                to {
                                                    opacity: 1;
                                                    transform: translateY(0);
                                                }
                                            }

                                            /* Responsive Modal for Mobile */
                                            @media (max-width: 576px) {
                                                .custom-modal-content {
                                                    width: 95%;
                                                }
                                            }
                                        </style>








                                        <!-- Edit Modal for each student -->
                                        <div class="modal fade" id="editStudentModal{{ $student->student_id }}"
                                            tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content shadow-lg border-1 edit-student-modal">
                                                    <div class="modal-header"
                                                        style="background-color: #1E293B; color: white;">
                                                        <h5 class="modal-title" id="editStudentModalLabel">
                                                            <i class="bi bi-pencil-square"></i> Edit Student Details
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('students.update', $student->student_id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row g-3">
                                                                <h4>Student's Information</h4>
                                                                <!-- Student ID Field -->
                                                                <div class="col-md-6">
                                                                    <label for="student_id"
                                                                        class="form-label fw-semibold">Student ID</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="student_id" name="student_id"
                                                                        value="{{ old('student_id', $student->student_id) }}"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="section"
                                                                        class="form-label fw-semibold">Section</label>
                                                                    <select class="form-control shadow-sm" id="section"
                                                                        name="section" required>
                                                                        {{-- <option value="" disabled selected>Select Section</option> --}}
                                                                        <option value="A"
                                                                            {{ old('section', $student->section) == 'A' ? 'selected' : '' }}>
                                                                            A</option>
                                                                        <option value="B"
                                                                            {{ old('section', $student->section) == 'B' ? 'selected' : '' }}>
                                                                            B</option>
                                                                        <option value="C"
                                                                            {{ old('section', $student->section) == 'C' ? 'selected' : '' }}>
                                                                            C</option>
                                                                        <option value="D"
                                                                            {{ old('section', $student->section) == 'D' ? 'selected' : '' }}>
                                                                            D</option>
                                                                    </select>
                                                                </div>
                                                                <!-- Last Name Field -->
                                                                <div class="col-md-6">
                                                                    <label for="last_name"
                                                                        class="form-label fw-semibold">Last Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="last_name" name="last_name"
                                                                        value="{{ old('last_name', $student->last_name) }}"
                                                                        required>
                                                                </div>
                                                                <!-- First Name Field -->
                                                                <div class="col-md-6">
                                                                    <label for="first_name"
                                                                        class="form-label fw-semibold">First Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="first_name" name="first_name"
                                                                        autocomplete="given-name"
                                                                        value="{{ old('first_name', $student->first_name) }}"
                                                                        required="">
                                                                </div>
                                                                <!-- Middle Name Field -->
                                                                <div class="col-md-6">
                                                                    <label for="middle_name"
                                                                        class="form-label fw-semibold">Middle Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="middle_name" name="middle_name"
                                                                        value="{{ old('middle_name', $student->middle_name) }}"
                                                                        required>
                                                                </div>
                                                                <!-- Suffix -->
                                                                <div class="col-md-6">
                                                                    <label for="middle_name"
                                                                        class="form-label fw-semibold">Suffix</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="middle_name" name="middle_name"
                                                                        value="{{ old('middle_name', $student->middle_name) }}"
                                                                        required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="age"
                                                                        class="form-label fw-semibold">Age</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="age" name="age"
                                                                        value="{{ old('age', $student->age) }}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="sex"
                                                                        class="form-label fw-semibold">Sex</label>
                                                                    <select class="form-control shadow-sm" id="sex"
                                                                        name="sex" required>
                                                                        {{-- <option value="" disabled selected>Select Gender</option> --}}
                                                                        <option value="Male"
                                                                            {{ old('sex', $student->sex) == 'Male' ? 'selected' : '' }}>
                                                                            Male</option>
                                                                        <option value="Female"
                                                                            {{ old('sex', $student->sex) == 'Female' ? 'selected' : '' }}>
                                                                            Female</option>
                                                                    </select>
                                                                </div>
                                                                <!-- Date of Birth Field -->
                                                                <div class="col-md-6">
                                                                    <label for="bdate"
                                                                        class="form-label fw-semibold">Date of
                                                                        Birth</label>
                                                                    {{-- <input type="date" class="form-control shadow-sm" id="bdate" name="bdate" value="{{ old('bdate', date('Y-m-d', strtotime($student->bdate))) }}" required> --}}
                                                                    <input type="date" class="form-control shadow-sm"
                                                                        id="bdate" name="bdate"
                                                                        value="{{ old('bdate', $student->bdate ? \Carbon\Carbon::createFromFormat('m-d-Y', $student->bdate)->format('Y-m-d') : '') }}"
                                                                        required>
                                                                    {{-- <input type="date" class="form-control shadow-sm" id="bdate" name="bdate" value="{{ old('bdate', $student->bdate) }}" required> --}}
                                                                </div>
                                                                <!-- Place of Birth Field -->
                                                                <div class="col-md-6">
                                                                    <label for="bplace"
                                                                        class="form-label fw-semibold">Place of
                                                                        Birth</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="bplace" name="bplace"
                                                                        value="{{ old('bplace', $student->bplace) }}"
                                                                        required>
                                                                </div>
                                                                <!-- Civil Status Field -->
                                                                <div class="col-md-6">
                                                                    <label for="civil_status"
                                                                        class="form-label fw-semibold">Civil Status</label>
                                                                    <select class="form-control shadow-sm"
                                                                        id="civil_status" name="civil_status" required>
                                                                        {{-- <option value="" disabled selected>Select Status</option> --}}
                                                                        {{-- <option value="statusOpt">Select Status</option> --}}
                                                                        <option value="Single"
                                                                            {{ old('civil_status', $student->civil_status) == 'Single' ? 'selected' : '' }}>
                                                                            Single</option>
                                                                        <option value="Married"
                                                                            {{ old('civil_status', $student->civil_status) == 'Married' ? 'selected' : '' }}>
                                                                            Married</option>
                                                                        <option value="Widowed"
                                                                            {{ old('civil_status', $student->civil_status) == 'Widowed' ? 'selected' : '' }}>
                                                                            Widowed</option>
                                                                        <option value="Divorced"
                                                                            {{ old('civil_status', $student->civil_status) == 'Divorced' ? 'selected' : '' }}>
                                                                            Divorced</option>
                                                                    </select>
                                                                </div>
                                                                <!-- Address Fields -->
                                                                <div class="col-md-6">
                                                                    <label for="address"
                                                                        class="form-label fw-semibold">Home Address</label>
                                                                    <input type="text" class="form-control"
                                                                        id="address" name="address"
                                                                        value="{{ old('address', $student->address) }}"
                                                                        autocomplete="street-address" required>
                                                                    {{-- <input type="text" class="form-control shadow-sm" id="address" name="address" value="{{ old('address', $student->address) }}" required> --}}
                                                                </div>
                                                                <!-- Phone Field -->
                                                                <div class="col-md-6">
                                                                    <label for="cell_no"
                                                                        class="form-label fw-semibold">Phone Number</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="cell_no" name="cell_no"
                                                                        value="{{ old('cell_no', $student->cell_no) }}"
                                                                        required>
                                                                </div>
                                                                <!-- Email Field -->
                                                                <div class="col-md-6">
                                                                    <label for="email"
                                                                        class="form-label fw-semibold">Email
                                                                        Address</label>
                                                                    <input type="email" class="form-control shadow-sm"
                                                                        id="email" name="email"
                                                                        autocomplete="email"
                                                                        value="{{ old('email', $student->email) }}"
                                                                        required="">
                                                                    {{-- <input type="email" class="form-control shadow-sm" id="email" name="email" required> --}}
                                                                </div>
                                                                {{-- Password Field --}}
                                                                <div class="mb-3">
                                                                    <label for="password"
                                                                        class="form-label fw-semibold">Password</label>
                                                                    <input type="password" class="form-control"
                                                                        id="password" name="password"
                                                                        placeholder="Leave blank to keep the current password">
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 mt-3">
                                                                <h4>Parent's Information</h4>
                                                                <!-- Father's Information Field -->
                                                                <div class="col-md-6">
                                                                    <h5>Father</h5>
                                                                    <label for="father_last_name"
                                                                        class="form-label fw-semibold">Last Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="father_last_name" name="father_last_name"
                                                                        value="{{ old('father_last_name', $student->father_last_name) }}"
                                                                        required>

                                                                    <label for="father_first_name"
                                                                        class="form-label fw-semibold">First Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="father_first_name" name="father_first_name"
                                                                        value="{{ old('father_first_name', $student->father_first_name) }}"
                                                                        required>

                                                                    <label for="father_middle_name"
                                                                        class="form-label fw-semibold">Middle Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="father_middle_name" name="father_middle_name"
                                                                        value="{{ old('father_middle_name', $student->father_middle_name) }}"
                                                                        required>
                                                                </div>
                                                                <!-- Mother's Information Field -->
                                                                <div class="col-md-6">
                                                                    <h5>Mother's Maiden Name</h5>
                                                                    <label for="mother_last_name"
                                                                        class="form-label fw-semibold">Last Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="mother_last_name" name="mother_last_name"
                                                                        value="{{ old('mother_last_name', $student->mother_last_name) }}"
                                                                        required>

                                                                    <label for="mother_first_name"
                                                                        class="form-label fw-semibold">First Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="mother_first_name" name="mother_first_name"
                                                                        value="{{ old('mother_first_name', $student->mother_first_name) }}"
                                                                        required>

                                                                    <label for="mother_middle_name"
                                                                        class="form-label fw-semibold">Middle Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="mother_middle_name" name="mother_middle_name"
                                                                        value="{{ old('mother_middle_name', $student->mother_middle_name) }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="row g-3 mt-3">
                                                                <h4>Educational Background</h4>
                                                                <!-- Elementary -->
                                                                <div class="col-md-6">
                                                                    <h5>Elementary</h5>
                                                                    <label for="elem_school_name"
                                                                        class="form-label fw-semibold">School Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="elem_school_name" name="elem_school_name"
                                                                        value="{{ old('elem_school_name', $student->elem_school_name) }}"
                                                                        required>

                                                                    <label for="elem_grad_year"
                                                                        class="form-label fw-semibold">Year
                                                                        Graduated</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="elem_grad_year" name="elem_grad_year"
                                                                        value="{{ old('elem_grad_year', $student->elem_grad_year) }}"
                                                                        required>
                                                                </div>
                                                                <!-- High School/Secondary -->
                                                                <div class="col-md-6">
                                                                    <h5>Secondary</h5>
                                                                    <label for="hs_school_name"
                                                                        class="form-label fw-semibold">School Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="hs_school_name" name="hs_school_name"
                                                                        value="{{ old('hs_school_name', $student->hs_school_name) }}"
                                                                        required>

                                                                    <label for="hs_grad_year"
                                                                        class="form-label fw-semibold">Year
                                                                        Graduated</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="hs_grad_year" name="hs_grad_year"
                                                                        value="{{ old('hs_grad_year', $student->hs_grad_year) }}"
                                                                        required>
                                                                </div>
                                                                <!-- Tertiary -->
                                                                <div class="col-md-6">
                                                                    <h5>Tertiary</h5>
                                                                    <label for="tertiary_school_name"
                                                                        class="form-label fw-semibold">School Name</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="tertiary_school_name"
                                                                        name="tertiary_school_name"
                                                                        value="{{ old('tertiary_school_name', $student->tertiary_school_name) }}"
                                                                        required>

                                                                    <label for="tertiary_grad_year"
                                                                        class="form-label fw-semibold">Year
                                                                        Graduated</label>
                                                                    <input type="text" class="form-control shadow-sm"
                                                                        id="tertiary_grad_year" name="tertiary_grad_year"
                                                                        value="{{ old('tertiary_grad_year', $student->tertiary_grad_year) }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="mt-4 d-flex justify-content-end">
                                                                <button type="button" class="btn btn-secondary me-2"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn"
                                                                    style="background-color: #1E293B;color: white; font-weight:600;">
                                                                    <i class="bi bi-save"></i> Save Changes
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                <tr>
                                    <td colspan="5" class="text-center">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination with custom styling --}}
                @if ($students->hasPages())
                    <ul class="custom-pagination">
                        {{-- Previous Page Link --}}
                        @if ($students->onFirstPage())
                            <li class="disabled"><span>«</span></li>
                        @else
                            <li><a href="{{ $students->previousPageUrl() }}" rel="prev">«</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                            <li class="{{ $page == $students->currentPage() ? 'active' : '' }}">
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($students->hasMorePages())
                            <li><a href="{{ $students->nextPageUrl() }}" rel="next">»</a></li>
                        @else
                            <li class="disabled"><span>»</span></li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
        <style>
            /* Scoped only for Edit Student Modal */
            .edit-student-modal h4 {
                font-size: 1.25rem;
                font-weight: 600;
                background-color: #f1f5f9;
                /* light slate background */
                padding: 10px 15px;
                border-left: 5px solid #1E293B;
                margin-top: 20px;
                margin-bottom: 10px;
                border-radius: 4px;
                color: #1E293B;
                transition: background-color 0.3s ease, border-color 0.3s ease;
            }

            .edit-student-modal .modal-body {
                max-height: 75vh;
                overflow-y: auto;
                padding-right: 10px;
            }

            /* Optional: Hide scrollbar in WebKit browsers */
            .edit-student-modal .modal-body::-webkit-scrollbar {
                width: 6px;
            }

            .edit-student-modal .modal-body::-webkit-scrollbar-thumb {
                background-color: #cbd5e1;
                border-radius: 10px;
            }
        </style>

        <!-- Modal for Adding Student -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white" style="background-color: #1E293B;">
                        <h5 class="modal-title" id="addStudentModalLabel">
                            <i class="bi bi-plus-square"></i> Add New Student
                        </h5>
                        <button type="button" class="btn-close btn-close-success" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('students.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <h4>Student's Information</h4>
                                <!-- Student ID Field -->
                                <div class="col-md-6">
                                    <label for="student_id" class="form-label fw-semibold">Student ID</label>
                                    <input type="text" class="form-control shadow-sm" id="student_id"
                                        name="student_id" required>
                                </div>
                                <!-- Last Name Field -->
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-semibold">Last Name</label>
                                    <input type="text" class="form-control shadow-sm" id="last_name" name="last_name"
                                        required>
                                </div>
                                <!-- First Name Field -->
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-semibold">First Name</label>
                                    <input type="text" class="form-control shadow-sm" id="first_name"
                                        name="first_name" autocomplete="given-name" required="">
                                </div>
                                <!-- Middle Name Field -->
                                <div class="col-md-6">
                                    <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                                    <input type="text" class="form-control shadow-sm" id="middle_name"
                                        name="middle_name" required>
                                </div>
                                <!-- Suffix -->
                                {{-- <div class="col-md-6">
                                <label for="middle_name" class="form-label fw-semibold">Suffix</label>
                                <input type="text" class="form-control shadow-sm" id="middle_name" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}" required>
                            </div> --}}
                                <div class="col-md-6">
                                    <label for="age" class="form-label fw-semibold">Age</label>
                                    <input type="text" class="form-control shadow-sm" id="age" name="age"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sex" class="form-label fw-semibold">Sex</label>
                                    <select class="form-control shadow-sm" id="sex" name="sex" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <!-- Date of Birth Field -->
                                <div class="col-md-6">
                                    <label for="bdate" class="form-label fw-semibold">Date of Birth</label>
                                    <input type="date" class="form-control shadow-sm" id="bdate" name="bdate"
                                        required>
                                </div>
                                <!-- Place of Birth Field -->
                                <div class="col-md-6">
                                    <label for="bplace" class="form-label fw-semibold">Place of Birth</label>
                                    <input type="text" class="form-control shadow-sm" id="bplace" name="bplace"
                                        required>
                                </div>
                                <!-- Civil Status Field -->
                                <div class="col-md-6">
                                    <label for="civil_status" class="form-label fw-semibold">Civil Status</label>
                                    <select class="form-control shadow-sm" id="civil_status" name="civil_status"
                                        required>
                                        <option value="" disabled selected>Select Status</option>
                                        {{-- <option value="statusOpt">Select Status</option> --}}
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                </div>
                                <!-- Address Fields -->
                                <div class="col-md-6">
                                    <label for="address" class="form-label fw-semibold">Home Address</label>
                                    <input type="text" class="form-control shadow-sm" id="address" name="address"
                                        autocomplete="street-address" required>
                                </div>
                                <!-- Phone Field -->
                                <div class="col-md-6">
                                    <label for="cell_no" class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" class="form-control shadow-sm" id="cell_no" name="cell_no"
                                        required>
                                </div>
                                <!-- Email Field -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control shadow-sm" id="email" name="email"
                                        autocomplete="email" required="">
                                    {{-- <input type="email" class="form-control shadow-sm" id="email" name="email" required> --}}
                                </div>
                                {{-- Password Field --}}
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <input type="text" class="form-control shadow-sm" id="password" name="password"
                                        required>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <h4>Parent's Information</h4>
                                <!-- Father's Information Field -->
                                <div class="col-md-6">
                                    <h5>Father</h5>
                                    <label for="father_last_name" class="form-label fw-semibold">Last Name</label>
                                    <input type="text" class="form-control shadow-sm" id="father_last_name"
                                        name="father_last_name" required>

                                    <label for="father_first_name" class="form-label fw-semibold">First Name</label>
                                    <input type="text" class="form-control shadow-sm" id="father_first_name"
                                        name="father_first_name" required>

                                    <label for="father_middle_name" class="form-label fw-semibold">Middle Name</label>
                                    <input type="text" class="form-control shadow-sm" id="father_middle_name"
                                        name="father_middle_name" required>
                                </div>
                                <!-- Mother's Information Field -->
                                <div class="col-md-6">
                                    <h5>Mother's Maiden Name</h5>
                                    <label for="mother_last_name" class="form-label fw-semibold">Last Name</label>
                                    <input type="text" class="form-control shadow-sm" id="mother_last_name"
                                        name="mother_last_name" required>

                                    <label for="mother_first_name" class="form-label fw-semibold">First Name</label>
                                    <input type="text" class="form-control shadow-sm" id="mother_first_name"
                                        name="mother_first_name" required>

                                    <label for="mother_middle_name" class="form-label fw-semibold">Middle Name</label>
                                    <input type="text" class="form-control shadow-sm" id="mother_middle_name"
                                        name="mother_middle_name" required>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <h4>Educational Background</h4>
                                <!-- Elementary -->
                                <div class="col-md-6">
                                    <h5>Elementary</h5>
                                    <label for="elem_school_name" class="form-label fw-semibold">School Name</label>
                                    <input type="text" class="form-control shadow-sm" id="elem_school_name"
                                        name="elem_school_name" required>

                                    <label for="elem_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                    <input type="text" class="form-control shadow-sm" id="elem_grad_year"
                                        name="elem_grad_year" required>
                                </div>
                                <!-- High School/Secondary -->
                                <div class="col-md-6">
                                    <h5>Secondary</h5>
                                    <label for="hs_school_name" class="form-label fw-semibold">School Name</label>
                                    <input type="text" class="form-control shadow-sm" id="hs_school_name"
                                        name="hs_school_name" required>

                                    <label for="hs_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                    <input type="text" class="form-control shadow-sm" id="hs_grad_year"
                                        name="hs_grad_year" required>
                                </div>
                                <!-- Tertiary -->
                                <div class="col-md-6">
                                    <h5>Tertiary</h5>
                                    <label for="tertiary_school_name" class="form-label fw-semibold">School Name</label>
                                    <input type="text" class="form-control shadow-sm" id="tertiary_school_name"
                                        name="tertiary_school_name" required>

                                    <label for="tertiary_grad_year" class="form-label fw-semibold">Year Graduated</label>
                                    <input type="text" class="form-control shadow-sm" id="tertiary_grad_year"
                                        name="tertiary_grad_year" required>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <h4>Department</h4>
                                <div class="form-group">
                                    <label for="department_id">Department</label>
                                    <select name="department_id" id="department_id" class="form-control" required>
                                        <option value="">Select a Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="year_level" class="form-label">Year Level</label>
                                    <select class="form-control" id="year_level" name="year_level">
                                        <option value="">Select Year</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </select>
                                </div>

                                {{-- <div class="mb-3">
                                <label for="section" class="form-label">Section</label>
                                <select class="form-control shadow-sm" id="sex" name="sex" required>
                                    <option value="" disabled selected>Select Section</option>
                                    <option value="A" {{ old('section', $student->section) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('section', $student->section) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('section', $student->section) == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('section', $student->section) == 'D' ? 'selected' : '' }}>D</option>
                                </select>
                            </div> --}}

                                <div class="mb-3">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-control" id="semester" name="semester">
                                        <option value="">Select Semester</option>
                                        <option value="1">1st Semester</option>
                                        <option value="2">2nd Semester</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn text-white" style="background-color: #1E293B;">
                                    <i class="bi bi-save"></i> Add Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <style>
            /* Add section title styling */
            #addStudentModal h4 {
                font-size: 1.25rem;
                font-weight: 600;
                background-color: #f1f5f9;
                /* light slate bg */
                padding: 10px 15px;
                border-left: 5px solid #1E293B;
                /* dark slate border */
                margin-top: 20px;
                margin-bottom: 10px;
                border-radius: 4px;
                color: #1E293B;
                transition: background-color 0.3s ease, border-color 0.3s ease;
            }

            /* Optional subtle animation on hover (nice touch) */
            #addStudentModal h4:hover {
                background-color: #e2e8f0;
                border-color: #0f172a;
            }

            /* Also style mini titles like "Father", "Elementary" */
            #addStudentModal h5 {
                font-size: 1.1rem;
                font-weight: 600;
                color: white;
                margin-top: 10px;
                margin-bottom: 5px;
            }

            /* Only target the Add Student modal */
            #addStudentModal .modal-dialog {
                animation: slideInUp 0.4s ease;
                transition: transform 0.4s ease-in-out;
            }

            @keyframes slideInUp {
                from {
                    transform: translateY(50px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            /* Make the modal-body scrollable with a nice look */
            #addStudentModal .modal-body {
                max-height: 70vh;
                overflow-y: auto;
                padding-right: 10px;
                scrollbar-width: thin;
            }

            /* Chrome scrollbar styling */
            #addStudentModal .modal-body::-webkit-scrollbar {
                width: 6px;
            }

            #addStudentModal .modal-body::-webkit-scrollbar-thumb {
                background-color: #94a3b8;
                /* Soft slate color */
                border-radius: 10px;
            }

            #addStudentModal .modal-body::-webkit-scrollbar-track {
                background: transparent;
            }

            /* Optional: Add subtle shadow to sticky modal header */
            #addStudentModal .modal-header {
                position: sticky;
                top: 0;
                z-index: 10;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        </style>

    </div>
@endsection
