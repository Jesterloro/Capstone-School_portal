@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Profile Section -->
    <div
        class="profile-glass-card position-relative mb-5 p-5 rounded-4 shadow-lg d-flex align-items-center justify-content-between flex-wrap">
        <div class="profile-info d-flex flex-column justify-content-center text-white">
            <h1 class="fw-bold mb-2">{{ $teacher->name }}'s Profile</h1>
            <p class="text-light mb-2">Manage your subjects and monitor student progress</p>
        </div>
        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#printModal">
            <i class="fas fa-print me-2"></i>Print Subjects
        </button>
    </div>

    <!-- Subject List Grouped by Department -->
    @php
        $subjectsByDepartment = $teacher->subjects->groupBy('department_id');
    @endphp

    @forelse ($subjectsByDepartment as $departmentId => $subjects)
        <div class="glass-card rounded-4 shadow-sm mb-4">
            <!-- Department Header -->
            <div class="glass-header p-3 rounded-top">
                <h5 class="mb-0 text-light"><i class="fas fa-building me-2"></i>
                    {{ $subjects->first()->department->name }}</h5>
            </div>

            <!-- Subject List -->
            <div class="glass-body p-3">
                @foreach ($subjects as $subject)
                    @php
                        $studentsData = $subject->students
                            ->sortByDesc(fn($s) => $s->pivot->updated_at)
                            ->unique('id')
                            ->map(function ($student) {
                                $grade = $student->pivot->grade;
                                if (is_null($grade)) {
                                    $gradeText = 'No grades yet';
                                    $badgeClass = 'bg-secondary';
                                } elseif ($grade == 0) {
                                    $gradeText = 'INC';
                                    $badgeClass = 'bg-warning text-dark';
                                } elseif ($grade >= 74.50) {
                                    $gradeText = $grade;
                                    $badgeClass = 'bg-success';
                                } else {
                                    $gradeText = $grade;
                                    $badgeClass = 'bg-danger';
                                }
                                return [
                                    'student_id' => $student->id,
                                    'first_name' => $student->first_name,
                                    'last_name' => $student->last_name,
                                    'grade' => $grade,
                                    'grade_text' => $gradeText,
                                    'badge_class' => $badgeClass,
                                ];
                            })->values();
                    @endphp

                    <div class="glass-item d-flex justify-content-between align-items-center p-3 rounded-3 mb-3">
                        <div>
                            <h6 class="mb-1 text-white">{{ $subject->name }}</h6>
                            <small class="text-secondary">{{ $subject->code }}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary view-students-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#studentsModal"
                            data-subject-name="{{ $subject->subject_name }}"
                            data-students='@json($studentsData)'>
                            View Students
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center">
            <p class="text-muted">No subjects assigned.</p>
        </div>
    @endforelse
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="printModalLabel">
                    <i class="fas fa-print me-2"></i> Print Subjects
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="printArea">
                <!-- Print content goes here -->
                <h3 class="mb-3">{{ $teacher->name }}</h3>
                <h5>List Of Subjects</h5>

                @foreach ($subjectsByDepartment as $departmentId => $subjects)
                    <div class="mb-4">
                        <h5>{{ $subjects->first()->department->name }}</h5>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->students->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button onclick="printDiv('printArea')" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i> Print
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Print Script -->
<script>
    function printDiv(divId) {
        const printContents = document.getElementById(divId).innerHTML;

        const printWindow = window.open('', '', 'height=800,width=1000');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Subjects</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                        th { background-color: #f0f0f0; }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>



<!-- JavaScript for Department Filter and Printing -->
<script>
    document.getElementById('departmentSelect').addEventListener('change', function() {
        var selectedDepartmentId = this.value;

        // Show all departments if "all" is selected
        if (selectedDepartmentId === 'all') {
            document.querySelectorAll('.department').forEach(function(department) {
                department.style.display = 'block';
            });
        } else {
            // Hide all departments first
            document.querySelectorAll('.department').forEach(function(department) {
                department.style.display = 'none';
            });

            // Show the selected department
            document.querySelector('.department[data-department-id="' + selectedDepartmentId + '"]').style.display = 'block';
        }
    });

    document.getElementById('printBtn').addEventListener('click', function() {
        var printContent = document.getElementById('printContent').innerHTML;
        var printWindow = window.open('', '', 'height=800,width=1200');
        printWindow.document.write('<html><head><title>Teacher Subjects</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; font-size: 14px; padding: 20px; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    });
</script>

        <!-- Subject List Grouped by Department -->
        @php
        $subjectsByDepartment = $teacher->subjects->groupBy('department_id');
    @endphp

        @forelse ($subjectsByDepartment as $departmentId => $subjects)
            <div class="glass-card rounded-4 shadow-sm mb-4">
                <!-- Department Header -->
                <div class="glass-header p-3 rounded-top">
                    <h5 class="mb-0 text-light"><i class="fas fa-building me-2"></i>
                        {{ $subjects->first()->department->name }}</h5>
                </div>

                <!-- Subject List -->
                <div class="glass-body p-3">
                    @foreach ($subjects as $subject)
                        <div class="glass-item d-flex justify-content-between align-items-center p-3 rounded-3 mb-3">
                            <div>
                                <h6 class="mb-1 text-white">{{ $subject->name }}</h6>
                                <small class="text-secondary">{{ $subject->code }}</small>
                            </div>
                            @php
                            $studentsData = $subject->students
                                ->sortByDesc(fn($s) => $s->pivot->updated_at) // Or use 'created_at'
                                ->unique('id') // Only latest record per student remains
                                ->map(function ($student) {
                                    $grade = $student->pivot->grade;

                                    if (is_null($grade)) {
                                        $gradeText = 'No grades yet';
                                        $badgeClass = 'bg-secondary';
                                    } elseif ($grade == 0) {
                                        $gradeText = 'INC';
                                        $badgeClass = 'bg-warning text-dark';
                                    } elseif ($grade >= 74.50) {
                                        $gradeText = $grade;
                                        $badgeClass = 'bg-success';
                                    } else {
                                        $gradeText = $grade;
                                        $badgeClass = 'bg-danger';
                                    }

                                    return [
                                        'student_id' => $student->id,
                                        'first_name' => $student->first_name,
                                        'last_name' => $student->last_name,
                                        'grade' => $grade,
                                        'grade_text' => $gradeText,
                                        'badge_class' => $badgeClass,
                                        // Optional: 'is_retaking' => is_null($grade) || $grade == 0,
                                    ];
                                })
                                ->values();
                            @endphp
                            <button class="btn btn-sm btn-outline-primary view-students-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#studentsModal"
                            data-subject-name="{{ $subject->subject_name }}"
                            data-students='@json($studentsData)'>
                            View Students
                        </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center">
                <p class="text-muted">No subjects assigned.</p>
            </div>
        @endforelse
    </div>

    <!-- Students Modal -->
    <div class="modal fade custom-modal" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 glass-modal">
                <div class="modal-header text-white" style="background: rgba(30, 41, 59, 0.95);">
                    <h5 class="modal-title" id="studentsModalLabel">Students</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Search and Filter Section -->
                    <div class="search-container mb-4 d-flex flex-column flex-md-row gap-2">
                        <input type="text" id="studentSearch" class="form-control glass-input"
                            placeholder="🔍 Search students...">
                        <select id="gradeFilter" class="form-select glass-select w-auto">
                            <option value="all">All Grades</option>
                            <option value="N/A">Grade: N/A</option>
                        </select>
                    </div>

                    <!-- Student List -->
                    <ul class="list-group" id="studentsList">
                        <!-- Students will be dynamically loaded -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentsModalLabel = document.getElementById('studentsModalLabel');
            const studentsList = document.getElementById('studentsList');
            const searchInput = document.getElementById('studentSearch');
            const gradeFilter = document.getElementById('gradeFilter');

            document.querySelectorAll('.view-students-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const subjectName = this.getAttribute('data-subject-name');
                    const students = JSON.parse(this.getAttribute('data-students'));

                    studentsModalLabel.textContent = `${subjectName} `;
                    renderStudents(students);

                    searchInput.addEventListener('input', () => filterStudents(students));
                    gradeFilter.addEventListener('change', () => filterStudents(students));
                });
            });

            function renderStudents(students) {
    studentsList.innerHTML = '';

    if (students.length > 0) {
        students.forEach(student => {
            let gradeText, gradeColor;

            if (student.grade === null) {
                gradeText = 'No grades yet';
                gradeColor = 'bg-secondary';
            } else if (student.grade == 0) {
                gradeText = 'INC';
                gradeColor = 'bg-warning text-dark';
            } else if (student.grade >= 74.50) {
                gradeText = student.grade;
                gradeColor = 'bg-success';
            } else {
                gradeText = student.grade;
                gradeColor = 'bg-danger';
            }

            let retakeBadge = student.is_retaking ?
                `<span class="badge bg-danger ms-2">Retaking</span>` : '';

            studentsList.innerHTML += `
                <li class="list-group-item glass-item d-flex justify-content-between align-items-center">
                    <strong>${student.first_name} ${student.last_name}</strong>
                    <div>
                        <span class="badge ${gradeColor} me-2">Grade: ${gradeText}</span>
                        ${retakeBadge}
                    </div>
                </li>
            `;
        });
    } else {
        studentsList.innerHTML = '<li class="list-group-item text-muted">No students enrolled.</li>';
    }
}

            function filterStudents(students) {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedGrade = gradeFilter.value;

                const filteredStudents = students.filter(student => {
                    const fullName = `${student.first_name} ${student.last_name}`.toLowerCase();
                    const grade = student.grade;

                    const matchesSearch = fullName.includes(searchTerm);
                    const matchesGrade = selectedGrade === 'all' || grade === selectedGrade;

                    return matchesSearch && matchesGrade;
                });

                renderStudents(filteredStudents);
            }
        });
    </script>

    <!-- Custom Styles -->
    <style>
        /* Profile Glass Card */
        .profile-glass-card {
            background: #1E293B;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease-in-out;
        }

        /* Profile Info Text */
        .profile-info h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #FFFFFF;
        }

        .profile-info p {
            font-size: 1.1rem;
            color: #E2E8F0;
        }

        /* Subject Cards */
        .glass-card {
            background: #1E293B;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease-in-out;
        }

        /* Subject Card Hover */
        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
        }

        /* Department Header */
        .glass-header {
            background: #1E293B;
            color: #FFFFFF;
            padding: 12px 20px;
            border-radius: 12px 12px 0 0;
        }

        /* Subject List */
        .glass-body {
            padding: 15px;
        }

        /* Individual Subject Item */
        .glass-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px;
            transition: all 0.3s ease-in-out;
        }

        /* Subject Item Hover */
        .glass-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Glass Button */
        .btn-glass {
            background: #1E293B;
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px 18px;
            border-radius: 12px;
            transition: all 0.3s ease-in-out;
        }

        /* Glass Button Hover */
        .btn-glass:hover {
            background: #334155;
            transform: translateY(-3px);
        }

        /* Modal Custom */
        .glass-modal {
            background: #1E293B;
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
        }

        /* Modal Header */
        .modal-header {
            background: #1E293B;
            color: #FFFFFF;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        /* Modal Body */
        .modal-body {
            background: rgba(255, 255, 255, 0.03);
            color: #FFFFFF;
        }

        /* Search and Filter */
        .glass-input,
        .glass-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #FFFFFF;
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }

        /* Search Focus */
        .glass-input:focus,
        .glass-select:focus {
            border-color: #FFFFFF;
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.2);
            outline: none;
        }

        /* List Group */
        .list-group-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        /* Hover Effect on List */
        .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                gap: 8px;
            }
        }

        /* Smooth Modal Animation */
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

        .modal-content {
            animation: fadeIn 0.4s ease-out;
        }

        @media print {
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .modal, .modal-footer, .modal-header {
            display: none !important;
        }

        table {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        h5 {
            font-size: 1.5rem;
            margin-top: 30px;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        table th, table td {
            font-size: 0.95rem;
            padding: 6px 10px;
        }

        .table-dark {
            background-color: #222 !important;
            color: white !important;
        }

        .no-print {
            display: none !important;
        }
    }
    </style>
@endsection
