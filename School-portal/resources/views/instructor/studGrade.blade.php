@extends('layouts.instructor')

@section('content')
<div class="container my-5">
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

    <!-- Updated Page Title Section with Dark Navy Header -->
    <div class="rounded-4 shadow-sm text-center text-white p-4 mb-4" style="background-color: #1E293B;">
        <h1 class="fw-bold">
            <i class="fas fa-book-open me-2"></i> Student Grades
        </h1>
        <p class="text-white-75 mb-0">Manage grades efficiently with a streamlined design.</p>
    </div>
   {{-- <!-- Filter by Department -->
<form method="GET" id="departmentFilterForm" class="mb-4">
    <label for="departmentFilter" class="form-label fw-semibold">Filter by Department</label>
    <select id="departmentFilter" name="department" class="form-select shadow-sm"
        onchange="document.getElementById('departmentFilterForm').submit()">
        <option value="all" {{ request('department') == 'all' ? 'selected' : '' }}>All Departments</option>
        @foreach ($subjects->pluck('department.name')->unique()->filter()->sort() as $dept)
            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
        @endforeach
    </select>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const departmentFilter = document.getElementById('departmentFilter');
        const accordionItems = document.querySelectorAll('#subjectAccordion .accordion-item');

        departmentFilter.addEventListener('change', function () {
            const selectedDept = this.value.toLowerCase();

            accordionItems.forEach(item => {
                const deptSpan = item.querySelector('.accordion-button span');
                const deptName = deptSpan ? deptSpan.textContent.replace(/\[|\]/g, '').toLowerCase() : '';

                if (selectedDept === 'all' || deptName.includes(selectedDept)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script> --}}

<!-- Button to Open Tutorial Subjects Modal -->
<div class="mb-4 d-flex justify-content-end">
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tutorialSubjectsModal">
        <i class="fas fa-chalkboard-teacher me-2"></i> View Tutorial Subjects
    </button>
</div>


<form action="{{ route('instructor.updateTutorialGrades') }}" method="POST">
    @csrf
    <div class="modal fade" id="tutorialSubjectsModal" tabindex="-1" aria-labelledby="tutorialSubjectsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tutorialSubjectsModalLabel">Tutorial Subjects</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $tutorialGrades = $editableGrades->where('status', 'tutorial')->unique('subject_id');
                    @endphp

                    @if($tutorialGrades->isEmpty())
                        <div class="alert alert-info text-center">No tutorial subjects found.</div>
                    @else
                        @foreach ($tutorialGrades as $tutorialGrade)
                            @php
                                $subject = \App\Models\Subject::find($tutorialGrade->subject_id);
                            @endphp

                            @if ($subject)
                                <h4>{{ $subject->name }} ({{ $subject->code }})</h4>
                                <p><strong>Year:</strong> {{ $subject->year }} | <strong>Semester:</strong> {{ $subject->semester }}</p>

                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Grade</th>
                                            <th>Section</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $studentsInTutorial = $editableGrades->where('subject_id', $subject->id)->where('status', 'tutorial');
                                        @endphp

                                        @foreach ($studentsInTutorial as $grade)
                                            @php $student = $grade->student; @endphp

                                            @if ($student)
                                                <tr>
                                                    <td>{{ $student->student_id }}</td>
                                                    <td>{{ $student->last_name }}, {{ $student->first_name }}</td>
                                                    <td>
                                                        <input type="number" step="0.01" min="60" max="100"
                                                               name="grades[{{ $grade->id }}]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $grade->grade }}">
                                                    </td>
                                                    <td>{{ $student->section }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Grades</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>





  <!-- Year Level Filter Dropdown -->
  <div class="row mb-4">
    <!-- Year Level Filter -->
    <div class="col-md-6">
        <label for="yearLevelFilterSem" class="form-label fw-semibold text-dark">
            <i class="fas fa-filter me-1 text-primary"></i> Filter by Year Level
        </label>
        <select class="form-select shadow-sm border-0 rounded-3 px-3 py-2"
                id="yearLevelFilterSem"
                style="background-color: #1e70e3; font-weight: 500; color: white; font-weight: 600;">
            <option value="all" selected>All Year Levels</option>
            @foreach ($subjects->unique('year') as $unique)
                <option value="{{ $unique->year }}">{{ $unique->year }} Year</option>
            @endforeach
        </select>
    </div>

    <!-- Semester Filter -->
    <div class="col-md-6">
        <label for="semesterFilter" class="form-label fw-semibold text-dark">
            <i class="fas fa-filter me-1 text-primary"></i> Filter by Semester
        </label>
        <select class="form-select shadow-sm border-0 rounded-3 px-3 py-2"
                id="semesterFilter"
                style="background-color: #1e70e3; font-weight: 600; color: white;">
            @php
                $presentSemesters = $subjects->pluck('semester')->unique()->sort();
                $semLabels = [1 => 'First Semester', 2 => 'Second Semester', 3 => 'Summer'];
            @endphp
            @foreach ($presentSemesters as $sem)
                <option value="{{ $sem }}">{{ $semLabels[$sem] ?? 'Semester '.$sem }}</option>
            @endforeach
        </select>
    </div>
</div>


@php
    $semesters = [1 => 'First Semester', 2 => 'Second Semester', 3 => 'Summer'];
@endphp

@foreach ($semesters as $sem => $semTitle)
<div class="d-flex align-items-center gap-2 bg-light border-start border-4 border-primary rounded-3 px-3 py-2 shadow-sm mt-4 mb-3">
    <i class="fas fa-calendar-alt text-primary fs-5"></i>
    <h4 class="fw-bold text-primary mb-0">{{ $semTitle }}</h4>
</div>
    <div class="accordion" id="semesterAccordion{{ $sem }}">
        @foreach ($subjects->where('semester', $sem) as $subject)
        <div class="accordion-item mb-3 rounded-4 shadow-sm border-0" data-year-level="{{ $subject->year }}">
            <h2 class="accordion-header" id="heading{{ $subject->id }}">
                <button class="accordion-button fw-bold text-white" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $subject->id }}" aria-expanded="false"
                    aria-controls="collapse{{ $subject->id }}" style="background-color: #1E293B; border-radius: 12px;">
                    {{ $subject->name }} ({{ $subject->code }}) -
                    {{ $subject->year }} Year | Semester {{ $subject->semester }}
                    @if($subject->department)
                        <span class="ms-2 fw-normal text-white-50">[{{ $subject->department->name }}]</span>
                    @endif
                </button>
            </h2>
            <div id="collapse{{ $subject->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $subject->id }}"
                data-bs-parent="#semesterAccordion{{ $sem }}">
                <div class="accordion-body p-3">
                    @php
                    $allStudents = collect();
                    $studentIds = [];

                    $filteredStudents = $subject->students->filter(function ($student) use ($subject) {
                        return $student->year_level == $subject->year && $student->semester == $subject->semester;
                    });

                    foreach ($filteredStudents as $student) {
                        if (!in_array($student->student_id, $studentIds)) {
                            // Fetch grade
                            $grade = $editableGrades->where('student_id', $student->student_id)
                                                    ->where('subject_id', $subject->id)
                                                    ->sortByDesc('school_year')
                                                    ->first();

                            // Determine status based on grade
                            $status = 'Regular'; // Default status

                            if ($grade) {
                                if ($grade->grade === null) {
                                    $status = 'Regular'; // No grade, marked as regular
                                } elseif ($grade->grade <= 74.49) {
                                    $status = 'Re-taking'; // Grade is below 74.49
                                } elseif ($grade->grade >= 74.50) {
                                    $status = 'Regular'; // Passed with a grade of 74.50 or higher
                                }
                            }

                            $allStudents->push([
                                'student' => $student,
                                'status' => $status
                            ]);
                            $studentIds[] = $student->student_id;
                        }
                    }

                    foreach ($editableGrades->where('subject_id', $subject->id) as $repeat) {
                        if (!in_array($repeat->student->student_id, $studentIds)) {
                            // Fetch grade
                            $grade = $editableGrades->where('student_id', $repeat->student->student_id)
                                                    ->where('subject_id', $subject->id)
                                                    ->sortByDesc('school_year')
                                                    ->first();

                            // Determine status based on grade
                            $status = 'Regular'; // Default status

                            if ($grade) {
                                if ($grade->grade === null) {
                                    $status = 'Regular'; // No grade, marked as regular
                                } elseif ($grade->grade <= 74.49) {
                                    $status = 'Re-taking'; // Grade is below 74.49
                                } elseif ($grade->grade >= 74.50) {
                                    $status = 'Regular'; // Passed with a grade of 74.50 or higher
                                }
                            }

                            $allStudents->push([
                                'student' => $repeat->student,
                                'status' => $status
                            ]);
                            $studentIds[] = $repeat->student->student_id;
                        }
                    }

                    $allStudents = $allStudents->sortBy('student.last_name');
                @endphp



                    <!-- Search -->
                    <div class="mb-3">
                        <label for="studentSearch{{ $subject->id }}" class="form-label fw-semibold">Search Student</label>
                        <input type="text" class="form-control shadow-sm studentSearch" id="studentSearch{{ $subject->id }}" placeholder="Enter Student Name or ID">
                    </div>

                    <!-- Section Filter -->
                    <div class="mb-3">
                        <label for="sectionFilter{{ $subject->id }}" class="form-label fw-semibold">Filter by Section</label>
                        <select class="form-control shadow-sm sectionFilter" id="sectionFilter{{ $subject->id }}" data-subject-id="{{ $subject->id }}">
                            <option value="all" selected>All Sections</option>
                            @foreach ($allStudents->unique('student.section') as $sectionData)
                                <option value="{{ $sectionData['student']->section }}">Section {{ $sectionData['student']->section }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($allStudents->isEmpty())
                        <div class="alert alert-light border text-center p-3 rounded-3">
                            <i class="fas fa-info-circle me-1"></i> No students enrolled for this subject.
                        </div>
                    @else
                        <div class="table-responsive" style="max-height: 700px; overflow-y: auto; border-bottom: 1px solid gray;">
                            <table class="table table-hover align-middle mb-0 studentTable" id="studentTable{{ $subject->id }}">
                                <thead class="table-success">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Section</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allStudents as $data)
                                        @php
                                            $grade = $editableGrades->where('student_id', $data['student']->student_id)
                                                ->where('subject_id', $subject->id)
                                                ->sortByDesc('school_year')
                                                ->first();

                                            if (!$grade || $grade->grade === null) {
                                                $gradeValue = 'No grades yet';
                                                $gradeClass = 'bg-secondary';
                                            } elseif ($grade->grade == 0) {
                                                $gradeValue = 'INC';
                                                $gradeClass = 'bg-warning text-dark';
                                            } elseif ($grade->grade < 74.50) {
                                                $gradeValue = $grade->grade;
                                                $gradeClass = 'bg-danger';
                                            } else {
                                                $gradeValue = $grade->grade;
                                                $gradeClass = 'bg-success';
                                            }
                                        @endphp
                                        <tr class="student-row" data-section="{{ $data['student']->section }}">
                                            <td class="fw-bold">{{ $data['student']->student_id }}</td>
                                            <td>{{ $data['student']->last_name }}, {{ $data['student']->first_name }}</td>
                                            <td>{{ $data['student']->department->name ?? 'N/A' }}</td>
                                            <td><span class="badge {{ $gradeClass }}">{{ $gradeValue }}</span></td>
                                            <td>
                                                <span class="badge {{ $data['status'] === 'Regular' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $data['status'] }}
                                                </span>
                                            </td>
                                            <td>{{ $data['student']->section }}</td>
                                            <td>
                                                <button class="btn btn-outline-success btn-sm edit-grade-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editGradeModal"
                                                    data-student-id="{{ $data['student']->student_id }}"
                                                    data-subject-id="{{ $subject->id }}"
                                                    data-student-name="{{ $data['student']->last_name }}, {{ $data['student']->first_name }}"
                                                    data-grade="{{ $grade->grade ?? '' }}">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                                <form method="POST" action="{{ route('instructor.setIncomplete') }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $data['student']->student_id }}">
                                                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                                                    <input type="hidden" name="grade" value="0">
                                                    <button type="submit" class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Incomplete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endforeach

<!-- Filter by Year Level (JS) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const yearFilter = document.getElementById('yearLevelFilterSem');
        const allItems = document.querySelectorAll('.accordion-item');

        const semesterFilter = document.getElementById('semesterFilter');
        const allSemesters = document.querySelectorAll('[id^="semesterAccordion"]');

        // Restore Year Filter
        const savedYear = localStorage.getItem('selectedYearLevelSemester');
        if (savedYear) {
            yearFilter.value = savedYear;
            filterByYear(savedYear);
        }

        // Restore Semester Filter
        const savedSem = localStorage.getItem('selectedSemester');
        if (savedSem) {
            semesterFilter.value = savedSem;
            openSemesterBlock(savedSem);
        } else {
            const firstSemOption = semesterFilter.options[0];
            if (firstSemOption) {
                openSemesterBlock(firstSemOption.value);
            }
        }

        // Year Level Filter Change
        yearFilter.addEventListener('change', function () {
            const selectedYear = this.value;
            localStorage.setItem('selectedYearLevelSemester', selectedYear);
            filterByYear(selectedYear);
        });

        // Semester Filter Change
        semesterFilter.addEventListener('change', function () {
            const selectedSem = this.value;
            localStorage.setItem('selectedSemester', selectedSem);
            openSemesterBlock(selectedSem);
        });

        // Year Filter Logic
        function filterByYear(yearLevel) {
            allItems.forEach(item => {
                const year = item.getAttribute('data-year-level');
                item.style.display = (yearLevel === 'all' || year === yearLevel) ? 'block' : 'none';
            });
        }

        // Semester Open Logic
        function openSemesterBlock(semester) {
            allSemesters.forEach(section => {
                section.style.display = section.id === 'semesterAccordion' + semester ? 'block' : 'none';
            });

            // Auto-expand first accordion inside selected semester (optional)
            const targetAccordions = document.querySelectorAll(`#semesterAccordion${semester} .accordion-collapse`);
            if (targetAccordions.length > 0) {
                const firstCollapse = targetAccordions[0];
                if (firstCollapse && !firstCollapse.classList.contains('show')) {
                    new bootstrap.Collapse(firstCollapse, { toggle: true });
                }
            }
        }
    });
</script>




<script>
    document.querySelectorAll('.studentSearch').forEach(searchBox => {
        searchBox.addEventListener('input', function() {
            let searchTerm = this.value.toLowerCase();
            let subjectId = this.id.replace('studentSearch', '');
            document.querySelectorAll(`#studentTable${subjectId} .student-row`).forEach(row => {
                let name = row.cells[1].textContent.toLowerCase();
                let studentId = row.cells[0].textContent.toLowerCase();
                row.style.display = (name.includes(searchTerm) || studentId.includes(searchTerm)) ? '' : 'none';
            });
        });
    });

    document.querySelectorAll('.sectionFilter').forEach(filter => {
        filter.addEventListener('change', function() {
            let section = this.value;
            let subjectId = this.dataset.subjectId;
            document.querySelectorAll(`#studentTable${subjectId} .student-row`).forEach(row => {
                row.style.display = (section === 'all' || row.dataset.section === section) ? '' : 'none';
            });
        });
    });
</script>

    <!-- Edit Grade Modal -->
    <div class="modal fade" id="editGradeModal" tabindex="-1" aria-labelledby="editGradeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header text-white" style="background-color: #1E293B;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Grade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="updateGradeForm" method="POST" action="{{ route('instructor.updateGrade') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="student_id" id="modal-student-id">
                        <input type="hidden" name="subject_id" id="modal-subject-id">

                        <div class="mb-3">
                            <label for="student-name" class="form-label fw-bold">Student Name</label>
                            <input type="text" class="form-control" id="modal-student-name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label fw-bold">Grade</label>
                            <input type="number" class="form-control" name="grade" id="modal-grade" min="0" max="100" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn text-white" style="background-color: #1E293B;">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Fill Modal Data -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.edit-grade-btn').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modal-student-id').value = this.dataset.studentId;
                document.getElementById('modal-subject-id').value = this.dataset.subjectId;
                document.getElementById('modal-student-name').value = this.dataset.studentName;
                document.getElementById('modal-grade').value = this.dataset.grade || '';
            });
        });
    });


    // for not the dropdown closed again when chnaging the grade or data
    document.addEventListener('DOMContentLoaded', function () {
    let activeAccordion = null;

    // Store the opened accordion ID before submitting the form
    document.querySelectorAll('.edit-grade-btn').forEach(button => {
        button.addEventListener('click', function () {
            const targetAccordion = button.closest('.accordion-collapse');
            if (targetAccordion) {
                activeAccordion = targetAccordion.id;
                localStorage.setItem('activeAccordion', activeAccordion);
            }
        });
    });

    // After submitting, keep the accordion open after reload
    if (localStorage.getItem('activeAccordion')) {
        const savedAccordionId = localStorage.getItem('activeAccordion');
        const targetAccordion = document.getElementById(savedAccordionId);

        if (targetAccordion) {
            const bsCollapse = new bootstrap.Collapse(targetAccordion, {
                toggle: false
            });
            bsCollapse.show(); // Re-open the saved accordion after page reload
        }

        // Clear the stored value to avoid unnecessary re-opening
        localStorage.removeItem('activeAccordion');
    }

    // Close all accordions by default on initial load
    document.querySelectorAll('.accordion-collapse').forEach(collapse => {
        collapse.classList.remove('show'); // Ensure they are all closed initially
    });

    // Capture the current accordion when clicking
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function () {
            activeAccordion = document.querySelector(button.getAttribute('data-bs-target')).id;
            localStorage.setItem('activeAccordion', activeAccordion);
        });
    });
});

</script>

<!-- Custom CSS for Smooth Accordion Transition -->
<style>
   /* Accordion Styles */
.accordion-button {
    transition: background-color 0.3s ease-in-out, box-shadow 0.2s ease;
    padding: 16px 20px;
    background: linear-gradient(135deg, #1E293B, #334155);
    color: white;
    border-radius: 12px;
}

.accordion-button:hover {
    background-color: #475569;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Accordion Collapse Animation */
.accordion-collapse {
    transition: all 0.4s ease-in-out;
}

.accordion-body {
    opacity: 0;
    transform: scale(0.98);
    transition: opacity 0.4s ease, transform 0.4s ease;
}

.accordion-collapse.show .accordion-body {
    opacity: 1;
    transform: scale(1);
}

/* Accordion Header Styling */
.accordion-header {
    border-radius: 12px;
    overflow: hidden;
}

/* Accordion Item with Glow */
.accordion-item {
    border: none;
    transition: box-shadow 0.3s ease-in-out;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
}

.accordion-item.show {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* Improved Accordion Arrow */
.accordion-button::after {
    transition: transform 0.3s ease-in-out;
}

.accordion-button[aria-expanded="true"]::after {
    transform: rotate(180deg);
}

/* Modal Design */
.modal-content {
    background: #F9FAFB;
    border-radius: 12px;
    border: none;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transform: scale(0.95);
    transition: all 0.3s ease-out;
}

.modal.show .modal-content {
    opacity: 1;
    transform: scale(1);
}

/* Modal Header */
.modal-header {
    background: linear-gradient(135deg, #1E293B, #334155);
    color: white;
    padding: 16px 20px;
    border-radius: 12px 12px 0 0;
}

/* Toast Notification */
#successToast {
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
}

/* Success Icon */
#successToast i {
    font-size: 1.5rem;
    color: #10B981;
}

/* Table Design */
.table-hover tbody tr:hover {
    background-color: #f1f5f9;
    transition: background-color 0.3s ease;
}

.table th,
.table td {
    padding: 14px 18px;
    text-align: center;
    vertical-align: middle;
    font-size: 0.95rem;
}

/* Buttons */
.btn-outline-success:hover {
    background-color: #10B981;
    color: white;
}

/* Badge Design */
.badge {
    font-size: 0.85rem;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: bold;
}

/* Custom Button Hover Effect */
.btn-outline-warning:hover {
    background-color: #FF9800;
    color: white;
}

/* Card & Table Hover Effects */
.card:hover,
.table-hover tbody tr:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
    transition: all 0.3s ease-in-out;
}

/* Modal Button Effects */
.btn:hover {
    transform: translateY(-2px);
    transition: all 0.2s ease;
}

</style>
@endsection
