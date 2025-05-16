@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Subjects</h1>
        <!-- Add Subject Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add Subject</button>
    </div>

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

    <!-- Subjects Table -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Description</th>
            <th>Units</th>
            <th>Day</th>
            <th>Time</th>
            <th>Room</th>
            <th>Department</th>
            <th>Instructor</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Group by Department first, then Year, then Semester
            $groupedSubjects = $subjects->groupBy(['department.name', 'year', 'semester']);

            // Define the correct department order
            $departmentOrder = ['BSIT', 'BSA', 'BSBA', 'CRIM', 'MIDWIFERY'];

            // Sort the grouped subjects by department order
            $sortedSubjects = collect($groupedSubjects)->sortBy(function ($value, $key) use ($departmentOrder) {
                return array_search(strtoupper($key), $departmentOrder);
            });
        @endphp

        @foreach ($sortedSubjects as $department => $years)
            <tr class="table-dark">
                <th colspan="10" class="text-center py-3">
                    <!-- Clickable Department Name to Toggle Collapse -->
                    <a href="javascript:void(0);" class="department-toggle" data-department="{{ strtoupper($department) }}">
                        {{ strtoupper($department) }}
                    </a>
                </th>
            </tr>

            <tr id="collapse-{{ strtoupper($department) }}" class="collapse department-collapse">
                <td colspan="10">
                    <!-- Search Form for this Department -->
                    <form action="{{ route('subjects.index') }}" method="GET" class="d-flex mb-2 search-form">
                        <input type="hidden" name="department" value="{{ $department }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control search-input me-2" placeholder="Search subjects..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary search-btn">
                                <i class="bi bi-search search-icon"></i> Search
                            </button>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        @foreach (collect($years)->sortKeys() as $year => $semesters)  <!-- Sort Years in Ascending Order -->
                            <tr class="table-primary">
                                <th colspan="10" class="text-center py-3">
                                    {{ $year == 1 ? '1st Year' : ($year == 2 ? '2nd Year' : ($year == 3 ? '3rd Year' : ($year == 4 ? '4th Year' : 'Year Not Set'))) }}
                                </th>
                            </tr>

                            @foreach (collect($semesters)->sortKeys() as $semester => $subjects)  <!-- Sort Semesters in Ascending Order -->
                                <tr class="table-secondary">
                                    <th colspan="10" class="text-center py-2">
                                        @if ($semester == 1)
                                            1st Semester
                                        @elseif ($semester == 2)
                                            2nd Semester
                                        @elseif ($semester == 3)
                                            3rd Semester <!-- Added 3rd Semester -->
                                        @else
                                            Semester Not Set
                                        @endif
                                    </th>
                                </tr>

                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->name }}</td>
                                        <td>{{ $subject->description }}</td>
                                        <td>{{ $subject->units }}</td>
                                        <td>{{ $subject->day }}</td>
                                        <td>{{ $subject->time }}</td>
                                        <td>{{ $subject->room }}</td>
                                        <td>{{ $subject->department ? $subject->department->name : 'N/A' }}</td>
                                        <td>{{ $subject->teacher ? $subject->teacher->name : 'N/A' }}</td>
                                        <td>
                                            <!-- Edit Button -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">Edit</button>

                                            <!-- Delete Form -->
                                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Subject Modal -->
                                    <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Subject</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Code</label>
                                                            <input type="text" class="form-control" name="code" value="{{ $subject->code }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $subject->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Course Description</label>
                                                            <textarea class="form-control" name="description">{{ $subject->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Units</label>
                                                            <input type="number" class="form-control" name="units" value="{{ $subject->units }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Day</label>
                                                            <input type="text" class="form-control" name="day" value="{{ $subject->day }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Time</label>
                                                            <input type="text" class="form-control" name="time" value="{{ $subject->time }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Room</label>
                                                            <input type="text" class="form-control" name="room" value="{{ $subject->room }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="department_id" class="form-label">Department</label>
                                                            <select class="form-control" id="department_id" name="department_id" required onchange="filterTeachers()">
                                                                <option value="">Select Department</option>
                                                                @foreach($departments as $department)
                                                                    <option value="{{ $department->id }}"
                                                                        {{ $subject->department_id == $department->id ? 'selected' : '' }}>
                                                                        {{ $department->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="semester" class="form-label">Semester</label>
                                                            <select class="form-control" id="semester" name="semester" required>
                                                                <option value="" {{ is_null($subject->semester) ? 'selected' : '' }}>Not Set</option>
                                                                <option value="1" {{ $subject->semester == 1 ? 'selected' : '' }}>1st Semester</option>
                                                                <option value="2" {{ $subject->semester == 2 ? 'selected' : '' }}>2nd Semester</option>
                                                                <option value="3" {{ $subject->semester == 3 ? 'selected' : '' }}>3rd Semester</option> <!-- Added option for 3rd Semester -->
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="year" class="form-label">Year Level</label>
                                                            <select class="form-control" id="year" name="year" required>
                                                                <option value="" {{ is_null($subject->year) ? 'selected' : '' }}>Not Set</option>
                                                                <option value="1" {{ $subject->year == 1 ? 'selected' : '' }}>1st Year</option>
                                                                <option value="2" {{ $subject->year == 2 ? 'selected' : '' }}>2nd Year</option>
                                                                <option value="3" {{ $subject->year == 3 ? 'selected' : '' }}>3rd Year</option>
                                                                <option value="4" {{ $subject->year == 4 ? 'selected' : '' }}>4th Year</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="teacher_id" class="form-label">Instructor</label>
                                                            <select class="form-control" id="teacher_id" name="teacher_id">
                                                                @php
                                                                    $selectedTeacher = $subject->teacher;
                                                                    $selectedTeacherId = $subject->teacher_id;
                                                                    $selectedTeacherName = $selectedTeacher->name ?? 'Select Instructor';
                                                                    $selectedDept = $selectedTeacher && $selectedTeacher->department ? $selectedTeacher->department->name : 'N/A';
                                                                @endphp

                                                                <!-- Selected instructor displayed first -->
                                                                <option value="{{ $selectedTeacherId }}" selected>
                                                                    {{ $selectedTeacherName }} - {{ $selectedDept }}
                                                                </option>

                                                                @foreach($teachers as $dept_id => $teacherGroup)
                                                                    @foreach($teacherGroup as $teacher)
                                                                        @if($teacher->status == 'active' && $teacher->id != $selectedTeacherId)
                                                                            <option value="{{ $teacher->id }}">
                                                                                {{ $teacher->name }} - {{ $teacher->department->name ?? 'N/A' }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>


    .department-toggle {
        cursor: pointer;
        font-weight: bold;
        color: #17a2b8;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .department-toggle:hover {
        color: #0056b3;
    }

    .department-header {
        transition: background-color 0.3s ease;
    }

    .department-header:hover {
        background-color: #f8f9fa;
    }

    .subject-row {
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .subject-row:hover {
        background-color: #f1f1f1;
        transform: scale(1.02);
    }

    .action-column .btn {
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .action-column .btn:hover {
        transform: scale(1.1);
        background-color: #f1c40f;
    }

    .delete-btn {
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .delete-btn:hover {
        transform: scale(1.1);
        background-color: #e74c3c;
    }

    .search-form .search-input {
        transition: border 0.3s ease;
    }

    .search-form .search-input:focus {
        border-color: #17a2b8;
    }

    /* Collapse Animation */
    .department-collapse {
        transition: max-height 0.5s ease-out, opacity 0.3s ease-out;
    }

    .search-form .input-group {
        margin-bottom: 20px;
    }

    /* Modal Animation */
    .modal.fade {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal.show {
        opacity: 1;
    }

    /* Hover Effects for Department Row */
    .department-collapse:hover {
        background-color: #f8f9fa;
    }
</style>
<script>

    // Handle the department toggle behavior
document.querySelectorAll('.department-toggle').forEach(departmentToggle => {
    departmentToggle.addEventListener('click', function () {
        const department = departmentToggle.getAttribute('data-department');
        const collapseElement = document.getElementById(`collapse-${department}`);

        // Check if the department is already open
        const isOpen = collapseElement.classList.contains('show');

        // Close all departments
        document.querySelectorAll('.department-collapse').forEach(collapse => {
            collapse.classList.remove('show');
        });

        // If the clicked department wasn't open, open it
        if (!isOpen) {
            collapseElement.classList.add('show');
        }

        // Store the collapse state in localStorage so it persists across page reloads
        let openDepartments = JSON.parse(localStorage.getItem('openDepartments')) || [];
        if (!isOpen) {
            // Only store the currently opened department
            openDepartments = [department];
        } else {
            openDepartments = []; // If the clicked department was already open, close all
        }
        localStorage.setItem('openDepartments', JSON.stringify(openDepartments));
    });
});

// Preserve the department collapse state on page load
window.addEventListener('DOMContentLoaded', () => {
    const openDepartments = JSON.parse(localStorage.getItem('openDepartments')) || [];
    openDepartments.forEach(department => {
        const collapseElement = document.getElementById(`collapse-${department}`);
        if (collapseElement) {
            collapseElement.classList.add('show');
        }
    });
});

    // Prevent the collapse from closing when a subject is edited or deleted
    document.querySelectorAll('.delete-form, .modal').forEach(form => {
        form.addEventListener('submit', function(event) {
            const department = this.closest('tr').querySelector('.department-toggle').getAttribute('data-department');
            const collapseElement = document.getElementById(`collapse-${department}`);
            collapseElement.classList.add('show');
        });
    });
</script>



</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Code</label>
                        <input type="text" class="form-control" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>Course Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Units</label>
                        <input type="number" class="form-control" name="units">
                    </div>
                    <div class="mb-3">
                        <label>Day</label>
                        <input type="text" class="form-control" name="day">
                    </div>
                    <div class="mb-3">
                        <label>Time</label>
                        <input type="text" class="form-control" name="time">
                    </div>
                    <div class="mb-3">
                        <label>Room</label>
                        <input type="text" class="form-control" name="room">
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-control" id="department_id" name="department_id" required onchange="filterTeachers()">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1">1st Semester</option>
                            <option value="2">2nd Semester</option>
                            <option value="2">Summer Semester</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Year Level</label>
                        <select class="form-control" id="year" name="year" required>
                            <option value="">Select Year Level</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Instructor</label>
                        <select class="form-control" id="teacher_id" name="teacher_id">
                            @php
                                $selectedTeacherId = $subject->teacher_id;
                                $selectedTeacherName = $subject->teacher->name ?? 'Select Instructor';
                                $selectedTeacherDepartment = $subject->teacher->department->name ?? 'N/A'; // Default to 'N/A' if no department
                            @endphp

                               <!-- Show the selected instructor as the top option, with department on the left and name on the right -->
                                <option value="{{ $selectedTeacherId }}" selected>
                                    {{ $selectedTeacherDepartment }} - {{ $selectedTeacherName }}
                                </option>
                            @foreach($teachers as $dept_id => $teacherGroup)
                                @foreach($teacherGroup as $teacher)
                                    <!-- Check if the teacher's status is active, then show the option -->
                                    @if($teacher->status == 'active')
                                        @if($teacher->id != $selectedTeacherId)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <script>
                        // Function to filter teachers based on selected department
                        function filterTeachers() {
                            let departmentId = document.getElementById('department_id').value;
                            let teacherDropdown = document.getElementById('teacher_id');
                            let options = teacherDropdown.getElementsByTagName('option');

                            // Loop through each option and toggle visibility based on department
                            for (let i = 0; i < options.length; i++) {
                                let option = options[i];
                                option.style.display = option.getAttribute('data-department') == departmentId || departmentId == "" ? 'block' : 'none';
                            }
                        }

                        // Ensure that teacher filtering works when the page is loaded
                        document.addEventListener('DOMContentLoaded', function () {
                            // Apply filter immediately when page is loaded
                            if (document.getElementById('department_id')) {
                                filterTeachers(); // Call filter on page load for both edit and add modals
                            }
                        });

                        // When Add Subject Modal is opened, call the filter function
                        $('#addSubjectModal').on('shown.bs.modal', function () {
                            filterTeachers(); // Ensure teachers are filtered based on selected department
                        });

                        // When Edit Subject Modal is opened, call the filter function for each subject
                        @foreach ($subjects as $subject)
                            $('#editSubjectModal{{ $subject->id }}').on('shown.bs.modal', function () {
                                filterTeachers(); // Ensure teachers are filtered based on selected department
                            });
                        @endforeach
                    </script>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
       /* ===== New Search Form Styling ===== */
    .search-form-2 {
        width: 100%;
        max-width: 500px;
        background: linear-gradient(to right, #0F172A, #1E293B); /* Dark navy gradient */
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 6px 25px rgba(15, 23, 42, 0.5);
        transition: all 0.4s ease-in-out;
    }

    /* ===== Input Field ===== */
    .search-input-2 {
        background-color: #1E293B;
        color: #E2E8F0;
        border: 2px solid #1E293B;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease-in-out;
        width: 100%;
        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.3);
    }

    .search-input-2:focus {
        background-color: #0F172A;
        border-color: #3B82F6; /* Vibrant blue focus effect */
        outline: none;
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        transform: scale(1.02);
    }

    /* ===== Button Styling ===== */
    .search-btn-2 {
        background-color: #1E40AF; /* Deep blue button */
        color: #F9FAFB;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease-in-out;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    }

    .search-btn-2:hover {
        background-color: #3B82F6;
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
        transform: translateY(-3px) scale(1.05);
    }

    /* ===== Search Icon Animation ===== */
    .search-icon-2 {
        transition: transform 0.3s ease-in-out;
        margin-right: 8px;
        font-size: 1.2rem;
    }

    .search-btn-2:hover .search-icon-2 {
        transform: rotate(15deg) scale(1.1);
    }

    /* ===== Input Group Border Animation ===== */
    .input-group {
        border-radius: 12px;
        overflow: hidden;
        background-color: #0F172A;
        border: 2px solid transparent;
        transition: all 0.3s ease-in-out;
    }

    .search-form-2:hover {
        border-color: #3B82F6;
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 768px) {
        .search-form-2 {
            max-width: 100%;
        }

        .search-input-2 {
            font-size: 0.9rem;
        }

        .search-btn-2 {
            padding: 10px 15px;
        }
    }

/* Modal backdrop and content */
.modal-content {
        background-color: #1e293b; /* Dark navy blue */
        border-radius: 10px;
        color: #ffffff;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .modal-header {
        background-color: #334155; /* Slightly lighter navy */
        border-bottom: 1px solid #455060;
    }

    .modal-title {
        font-weight: 600;
        color: #ffffff;
    }

    .btn-close {
        color: #ffffff;
        opacity: 0.5;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Body styling */
    .modal-body {
        padding: 2rem;
    }

    /* Improved label styling */
    .modal-body label {
        font-size: 1rem;
        font-weight: 500;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
        display: inline-block;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    /* Input styling */
    .form-control {
        background-color: #2a3d54;
        border: 1px solid #4b5c69;
        border-radius: 8px;
        color: #fff;
        padding: 10px;
        font-size: 1rem;
        transition: border 0.3s ease, box-shadow 0.3s ease;
    }

    /* Focus state */
    .form-control:focus {
        border-color: #3b9bff;
        box-shadow: 0 0 5px rgba(59, 155, 255, 0.7);
    }

    .form-control:focus + label {
        color: #3b9bff;
        transform: translateY(-15px);
        font-size: 0.9rem;
    }

    /* Select input styling */
    select.form-control {
        background-color: #2a3d54;
        border: 1px solid #4b5c69;
        padding: 10px;
        border-radius: 8px;
    }

    select.form-control:focus {
        border-color: #3b9bff;
        box-shadow: 0 0 5px rgba(59, 155, 255, 0.7);
    }

    /* Adding smooth transitions for modal and buttons */
    .modal-footer {
        border-top: 1px solid #455060;
        padding: 1rem;
    }

    .btn-primary {
        background-color: #3b9bff;
        border-color: #3b9bff;
        padding: 0.8rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #287ac0;
        border-color: #287ac0;
        transform: translateY(-2px);
    }

    /* Adjusting the width of the modal */
    .modal-dialog {
        max-width: 70%; /* Set to a more balanced width */
        width: 70%; /* Set to 70% of the parent container */
        max-height: 80%; /* Keep the max height constraint */
        overflow-y: auto; /* Allow vertical scrolling if content overflows */
        animation: modalFadeIn 0.5s ease-out;
    }

    /* Transition effect for the modal opening */
    @keyframes modalFadeIn {
        0% {
            opacity: 0;
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Margin for form groups */
    .mb-3 {
        margin-bottom: 1.5rem;
    }

    /* Modal backdrop animation */
    .modal-backdrop.show {
        opacity: 0.7 !important;
        transition: opacity 0.3s ease;
    }
</style>
@endsection
