@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom flex-wrap flex-md-nowrap">
        <!-- Left Side: Title -->
        <h1 class="h2 mb-2 mb-md-0">Teachers</h1>

        <!-- Right Side: Buttons -->
        <div class="d-flex gap-2">
            <button style="background-color: #1E293B;" class="btn text-white" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                Add Teacher
            </button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#inactiveTeachersModal">
                View Inactive Teachers
            </button>
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#customPrintTeacherModal">
                <i class="bi bi-printer"></i> Print Teachers
            </button>
        </div>
    </div>

<!-- =================================== -->
<!-- Custom Modal: Print All Teachers    -->
<!-- =================================== -->
<div class="modal fade" id="customPrintTeacherModal" tabindex="-1" aria-labelledby="customPrintTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow border-0 rounded-3">
            <div class="modal-header bg-dark text-white d-print-none">
                <h5 class="modal-title" id="customPrintTeacherModalLabel"><i class="bi bi-printer"></i> Print Teacher List</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                <!-- Print Button & Filter -->
                <div class="d-flex justify-content-between align-items-center d-print-none mb-3">
                    <!-- Filter Dropdown -->
                    <div>
                        <label for="departmentFilter" class="form-label fw-semibold me-2 mb-0">Filter by Department:</label>
                        <select id="departmentFilter" class="form-select d-inline-block w-auto shadow-sm" onchange="filterTeachers()">
                            <option value="all">All Departments</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->name }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Print Button -->
                    <button onclick="window.print()" class="btn btn-dark shadow-sm">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>

                <!-- Teachers Table -->
                <div class="table-responsive print-section">
                    <table class="table table-bordered text-center align-middle shadow-sm" id="teacherTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allTeachers as $teacher)
                                <tr data-department="{{ $teacher->department->name ?? 'N/A' }}">
                                    <td>{{ $teacher->name }}</td>
                                    <td>{{ $teacher->department->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer d-print-none">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ====== PRINT STYLES ====== -->
<style>
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 10mm 15mm 10mm;
        }

        body * {
            visibility: hidden !important;
        }

        .print-section,
        .print-section * {
            visibility: visible !important;
        }

        .modal {
            position: static !important;
            overflow: visible !important;
        }

        .modal-content {
            box-shadow: none !important;
            border: none !important;
        }

        .modal-footer,
        .modal-header,
        .btn,
        .d-print-none {
            display: none !important;
        }

        .modal-body {
            padding: 0 !important;
            overflow: visible !important;
            max-height: none !important;
        }

        .print-section table {
            width: 100% !important;
            font-size: 12pt;
            border-collapse: collapse;
        }

        .print-section th,
        .print-section td {
            border: 1px solid #333 !important;
            padding: 8px !important;
        }

        .print-section th {
            background-color: #f1f1f1 !important;
        }
    }
</style>

<!-- ====== FILTER SCRIPT ====== -->
<script>
    function filterTeachers() {
        const filter = document.getElementById('departmentFilter').value.toLowerCase();
        const rows = document.querySelectorAll('#teacherTable tbody tr');

        rows.forEach(row => {
            const dept = row.getAttribute('data-department').toLowerCase();
            if (filter === 'all' || dept === filter) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>



<!-- Modal -->
<div class="modal fade" id="inactiveTeachersModal" tabindex="-1" aria-labelledby="inactiveTeachersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inactiveTeachersModalLabel">Inactive Teachers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; border-bottom: 1px solid gray;">
                    <table class="table table-striped table-hover align-middle" style="border-bottom: 1px solid rgb(176, 175, 175)">
                        <thead class="text-center" style="background-color: #f8f9fa; color: black;">
                            <tr>
                                <th style="padding: 15px; border-radius: 10px 0 0 0;">Name</th>
                                <th style="padding: 15px;">Email</th>
                                <th style="padding: 15px;">Department</th>
                                <th style="padding: 15px;">Phone Number</th>
                                <th style="padding: 15px; border-radius: 0 10px 0 0;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $teacher)
                                @if ($teacher->status == 'inactive')
                                    <tr class="text-center" style="background-color: #f8f9fa; transition: all 0.3s;">
                                        <td style="padding: 15px; font-weight: 600;">{{ $teacher->name }}</td>
                                        <td style="padding: 15px; font-weight: 600;">{{ $teacher->email }}</td>
                                        <td style="padding: 15px; font-weight: 600;">{{ $teacher->department ? $teacher->department->name : 'N/A' }}</td>
                                        <td style="padding: 15px; font-weight: 600;">{{ $teacher->phoneNumber }}</td>
                                        <td>
                                            <!-- Action buttons for inactive teachers -->
                                            <form action="{{ route('teachers.updateStatus', $teacher->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-toggle-on"></i> Set Active
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
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


      {{-- <!-- Search Form -->
    <div class="mb-2">
        <div class="col-12 d-flex justify-content-end">
            <form action="{{ route('teachers.index') }}" method="GET" class="d-flex w-auto">
                <div class="input-group">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search by teacher name" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary" style="background-color: #16C47F; color: white; font-weight:600;">
                        <i class="bi bi-search"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div> --}}

     <!-- Modern Minimalist Search Form with White Background & Dark Navy Blue Button -->
<div class="row mb-4">
    <div class="col-12">
        <form action="{{ route('teachers.index') }}" method="GET" id="custom-search-form"
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
                placeholder="Search by name or student ID..."
                value="{{ request('search') }}"
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
    #search-input:focus + .search-icon-container .search-icon {
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




    <div class="row">
        <div class="col">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto; border-bottom: 1px solid gray;">
                <table class="table table-striped table-hover align-middle" style="border-bottom: 1px solid rgb(176, 175, 175)">
                    <thead class="text-center" style="background-color: #1E293B; color: white; position: sticky; top: 0; z-index:1000;">
                        <tr>
                            <th style="padding: 15px; border-radius: 10px 0 0 0;">Name</th>
                            <th style="padding: 15px;">Email</th>
                            <th style="padding: 15px;">Department</th>
                            <th style="padding: 15px;">Phone Number</th>
                            <th style=" padding: 15px; border-radius: 0 10px 0 0;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $teacher)
                         @if ($teacher->status == 'active')
                            <tr  class="text-center" style="background-color: #f8f9fa; transition: all 0.3s;">
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->name }}</td>
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->email }}</td>
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->department ? $teacher->department->name : 'N/A' }}</td>
                                <td style="padding: 15px; font-weight: 600;">{{ $teacher->phoneNumber }}</td>
                                <td>
                                    <!-- View Button -->
                                    <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTeacherModal{{ $teacher->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <!-- Delete Button -->
                                    <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this teacher?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                    <!-- Action buttons for active teachers -->
                                <form action="{{ route('teachers.updateStatus', $teacher->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="inactive">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-toggle-off"></i> Set Inactive
                                    </button>
                                </form>
                                </td>
                            </tr>

                           <!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="editTeacherModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centered and Wider Modal -->
        <div class="modal-content shadow-lg rounded-4">
            <!-- Modal Header -->
            <div class="modal-header text-white" style="background-color: #1E293B; padding: 15px 20px;">
                <h5 class="modal-title fw-bold" id="editTeacherModalLabel">
                    ✏️ Edit Teacher Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Form to Edit Teacher -->
            <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-white">
                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name_{{ $teacher->id }}" class="form-label fw-bold">👤 Name</label>
                            <input type="text" class="form-control custom-input" id="name_{{ $teacher->id }}" name="name"
                                value="{{ $teacher->name }}" placeholder="Enter teacher's name" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email_{{ $teacher->id }}" class="form-label fw-bold">📧 Email</label>
                            <input type="email" class="form-control custom-input" id="email_{{ $teacher->id }}" name="email"
                                value="{{ $teacher->email }}" placeholder="Enter valid email" required>
                        </div>

                        <!-- Department Dropdown -->
                        <div class="col-md-6">
                            <label for="department_id_{{ $teacher->id }}" class="form-label fw-bold">🏫 Department</label>
                            <select class="form-select custom-select" id="department_id_{{ $teacher->id }}" name="department_id" required>
                                <option value="" disabled>Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ $teacher->department_id == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label for="phoneNumber_{{ $teacher->id }}" class="form-label fw-bold">📞 Phone Number</label>
                            <input type="text" class="form-control custom-input" id="phoneNumber_{{ $teacher->id }}" name="phoneNumber"
                                value="{{ $teacher->phoneNumber }}" placeholder="Optional: Enter phone number">
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password_{{ $teacher->id }}" class="form-label fw-bold">🔒 Password</label>
                            <input type="password" class="form-control custom-input" id="password_{{ $teacher->id }}" name="password"
                                placeholder="Leave blank to keep current password">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">
                        ❌ Cancel
                    </button>
                    <button type="submit" class="btn custom-btn fw-bold px-4">
                        💾 Update Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- CSS for Custom Styling -->
<style>
    /* Custom Switch Styles */
.form-check-input {
    width: 45px;
    height: 24px;
    cursor: pointer;
}

.form-check-label {
    margin-left: 10px;
    font-weight: bold;
    color: #16C47F;
}

/* Change label color when inactive */
.form-check-input:not(:checked) + .form-check-label {
    color: #dc3545;
}
    /* Modal Centered with Max Width */
    .modal-dialog-centered {
        max-width: 700px;
    }

    /* Modal Header */
    .modal-header {
        background-color: #1E293B;
        color: white;
        border-radius: 12px 12px 0 0;
    }

    /* Custom Input and Select Design */
    .custom-input,
    .custom-select {
        border: 1px solid #94A3B8;
        border-radius: 8px;
        padding: 12px;
        font-size: 1rem;
        background-color: #F8FAFC;
        color: #1E293B;
        transition: all 0.3s ease;
    }

    /* Focus Effect */
    .custom-input:focus,
    .custom-select:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 8px rgba(79, 70, 229, 0.5);
        outline: none;
        background-color: #fff;
    }

    /* Button Customization */
    .custom-btn {
        background-color: #1E293B;
        color: white;
        border: none;
        transition: all 0.3s ease-in-out;
        border-radius: 50px;
        padding: 10px 24px;
    }

    .custom-btn:hover {
        background-color: #334155;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30, 41, 59, 0.2);
    }

    /* Cancel Button with Outline */
    .btn-outline-secondary {
        border: 2px solid #E2E8F0;
        color: #1E293B;
        transition: all 0.3s ease-in-out;
    }

    .btn-outline-secondary:hover {
        background-color: #E2E8F0;
        color: #1E293B;
    }

    /* Select Box Arrow Customization */
    .custom-select {
        appearance: none;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-chevron-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 5.854a.5.5 0 0 1 .708 0L8 11.5l5.646-5.646a.5.5 0 1 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>') no-repeat right 12px center;
        background-size: 12px;
        padding-right: 40px;
    }

    /* Modal Footer Button */
    .modal-footer {
        background-color: #F1F5F9;
        border-top: 1px solid #E2E8F0;
        border-radius: 0 0 12px 12px;
    }

    /* Responsive Modal Design */
    @media (max-width: 768px) {
        .modal-dialog-centered {
            max-width: 95%;
        }

        .modal-body {
            padding: 15px;
        }
    }

    /* Smooth Modal Fade Animation */
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

    /* Hover Effect for Inputs */
    .custom-input:hover,
    .custom-select:hover {
        background-color: #F1F5F9;
    }

    /* Input Shadow on Focus */
    .custom-input:focus,
    .custom-select:focus {
        box-shadow: 0 0 8px rgba(30, 41, 59, 0.2);
    }
</style>

                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             {{-- Pagination with custom styling --}}
@if ($teachers->hasPages())
<ul class="custom-pagination">
    {{-- Previous Page Link --}}
    @if ($teachers->onFirstPage())
        <li class="disabled"><span>«</span></li>
    @else
        <li><a href="{{ $teachers->previousPageUrl() }}" rel="prev">«</a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($teachers->getUrlRange(1, $teachers->lastPage()) as $page => $url)
        <li class="{{ $page == $teachers->currentPage() ? 'active' : '' }}">
            <a href="{{ $url }}">{{ $page }}</a>
        </li>
    @endforeach

    {{-- Next Page Link --}}
    @if ($teachers->hasMorePages())
        <li><a href="{{ $teachers->nextPageUrl() }}" rel="next">»</a></li>
    @else
        <li class="disabled"><span>»</span></li>
    @endif
</ul>
@endif
        </div>
    </div>
</div>

</div>
<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centered and Wider Modal -->
        <div class="modal-content shadow-lg rounded-4">
            <!-- Modal Header -->
            <div class="modal-header text-white" style="background-color: #1E293B; padding: 15px 20px;">
                <h5 class="modal-title fw-bold" id="addTeacherModalLabel">
                    🧑‍🏫 Add New Teacher
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form -->
            <form action="{{ route('teachers.store') }}" method="POST">
                @csrf
                <div class="modal-body bg-white p-4">
                    <div class="row g-3">
                        <!-- Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-bold">👤 Name</label>
                            <input type="text" class="form-control custom-input" id="name" name="name"
                                placeholder="Enter teacher's name" required>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">📧 Email</label>
                            <input type="email" class="form-control custom-input" id="email" name="email"
                                placeholder="Enter valid email" required>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold">🔒 Password</label>
                            <input type="password" class="form-control custom-input" id="password" name="password"
                                placeholder="Enter secure password" required>
                        </div>

                        <!-- Department Dropdown -->
                        <div class="col-md-6">
                            <label for="department_id" class="form-label fw-bold">🏫 Department</label>
                            <select class="form-select custom-select" id="department_id" name="department_id" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6">
                            <label for="phoneNumber" class="form-label fw-bold">📞 Phone Number</label>
                            <input type="number" class="form-control custom-input" id="phoneNumber" name="phoneNumber"
                                placeholder="Optional: Enter phone number">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary fw-bold" data-bs-dismiss="modal">
                        ❌ Cancel
                    </button>
                    <button type="submit" class="btn custom-btn fw-bold px-4">
                        ➕ Add Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS for Custom Styling -->
<style>
    /* Modal Centered and Width */
    .modal-dialog-centered {
        max-width: 700px;
    }

    /* Modal Header with Dark Navy Blue */
    .modal-header {
        background-color: #1E293B;
        color: white;
        border-radius: 12px 12px 0 0;
    }

    /* Custom Input and Select Design */
    .custom-input,
    .custom-select {
        border: 1px solid #94A3B8;
        border-radius: 8px;
        padding: 12px;
        font-size: 1rem;
        background-color: #F8FAFC;
        color: #1E293B;
        transition: all 0.3s ease;
    }

    /* Focus Effect */
    .custom-input:focus,
    .custom-select:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 8px rgba(79, 70, 229, 0.5);
        outline: none;
        background-color: #fff;
    }

    /* Button Customization */
    .custom-btn {
        background-color: #1E293B;
        color: white;
        border: none;
        transition: all 0.3s ease-in-out;
        border-radius: 50px;
        padding: 10px 24px;
    }

    .custom-btn:hover {
        background-color: #334155;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(30, 41, 59, 0.2);
    }

    /* Cancel Button with Outline */
    .btn-outline-secondary {
        border: 2px solid #E2E8F0;
        color: #1E293B;
        transition: all 0.3s ease-in-out;
    }

    .btn-outline-secondary:hover {
        background-color: #E2E8F0;
        color: #1E293B;
    }

    /* Department Dropdown Styling */
    .custom-select {
        appearance: none;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-chevron-down" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 5.854a.5.5 0 0 1 .708 0L8 11.5l5.646-5.646a.5.5 0 1 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>') no-repeat right 12px center;
        background-size: 12px;
        padding-right: 40px;
    }

    /* Modal Footer Styling */
    .modal-footer {
        background-color: #F1F5F9;
        border-top: 1px solid #E2E8F0;
        border-radius: 0 0 12px 12px;
    }

    /* Responsive Modal Design */
    @media (max-width: 768px) {
        .modal-dialog-centered {
            max-width: 95%;
        }

        .modal-body {
            padding: 15px;
        }
    }

    /* Smooth Modal Fade Animation */
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

    /* Hover Effect for Inputs */
    .custom-input:hover,
    .custom-select:hover {
        background-color: #F1F5F9;
    }

    /* Input Shadow on Focus */
    .custom-input:focus,
    .custom-select:focus {
        box-shadow: 0 0 8px rgba(30, 41, 59, 0.2);
    }
</style>



@endsection
