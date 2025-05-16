@extends('layouts.app')

@section('content')
<div class="cont py-5">
    <h1 class="text-center mb-5 fw-bold" style="color: #1E293B; font-size: 2.8rem;">
        📚 Manage Schedule
    </h1>

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

    <!-- Class Table Card -->
    <div class="card border-0 shadow-lg rounded-5 overflow-hidden mb-5 bg-white">
        <div class="card-header text-white fw-bold py-3 gradient-bg d-flex justify-content-between align-items-center">
            <span>📚 Available Classes</span>
            <!-- Add Class Button on Top Right -->
            <button type="button" style="background-color: white; color: black;" class="btn  add-btn" data-bs-toggle="modal" data-bs-target="#addClassModal">
                ➕ Add Schedule
            </button>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle text-center custom-table">
                    <thead class="bg-light" style="color: #16C47F;">
                        <tr>
                            <th><i class="bi bi-tag me-1"></i>ID</th>
                            <th><i class="bi bi-book me-1"></i>Schedule Name</th>
                            <th><i class="bi bi-building me-1"></i>Department</th>
                            <th><i class="bi bi-image me-1"></i>Image</th>
                            <th><i class="bi bi-gear me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                        <tr>
                            <td class="fw-bold"><i class="bi bi-tag-fill me-1 text-success"></i>{{ $class->id }}</td>
                            <td><i class="bi bi-book-fill me-1 text-success"></i>{{ $class->name }}</td>
                            <td>
                                <i class="bi bi-building-fill me-1 text-success"></i>
                                {{ $class->department ? $class->department->name : 'No Department' }}
                            </td>
                            <td>
                                @if ($class->image)
                                <img src="{{ asset('storage/' . $class->image) }}" alt="Class Image" class="rounded-circle shadow-sm" width="50" height="50" style="object-fit: cover;">
                                @else
                                <span class="text-muted"><i class="bi bi-file-image"></i> No Image</span>
                                @endif
                            </td>
                            <td>
                                {{-- <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a> --}}
                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-5 custom-modal-scale">
                <div class="modal-header text-white gradient-bg">
                    <h5 class="modal-title fw-bold">
                        ➕ Add a New Schedule
                    </h5>
                    <button type="button" class="btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('classes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">
                                📚 Schedule Name
                            </label>
                            <input type="text" name="name" class="form-control rounded-4" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                🏢 Department
                            </label>
                            <select name="department_id" class="form-select rounded-4" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🖼️ Upload Image</label>
                            <input type="file" name="image" class="form-control rounded-4">
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-4">
                            <i class="bi bi-check-circle me-1"></i>Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript for Modals and Toasts -->
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

<!-- Custom CSS -->
<style>
//* Page Container */
.cont {
    background-color: #FFFFFF; /* Plain White */
    color: #1E293B; /* Dark Navy Blue Text */
    min-height: 100vh;
}

/* Header Title */
h1.text-center {
    color: #1E293B;
    font-size: 2.8rem;
}

/* Add Button */
.add-btn {
    background-color: #1E293B;
    color: #FFFFFF;
    padding: 8px 18px;
    font-size: 1rem;
    border-radius: 30px;
    border: none;
    transition: all 0.3s ease-in-out;
}
.add-btn:hover {
    background-color: #334155;
    transform: scale(1.05);
}

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

/* Card for Class List */
.card {
    background: #FFFFFF;
    color: #1E293B;
    border-radius: 18px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}

/* Card Header */
.card-header {
    background-color: #1E293B;
    color: #FFFFFF;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

/* Table Styles */
.table {
    color: #1E293B;
}
.table thead {
    background-color: #F8F9FA;
}
.table th {
    padding: 12px;
    font-weight: bold;
    color: #1E293B;
}
.table tbody tr:nth-child(even) {
    background-color: #F9F9F9;
}
.table tbody tr:hover {
    background-color: #E9F7F4;
}

/* Class Image */
img.rounded-circle {
    border: 2px solid #1E293B;
}

/* Action Buttons */
.btn-outline-danger,
.btn-outline-primary {
    border-color: #1E293B;
    color: #1E293B;
    transition: all 0.3s ease-in-out;
}
.btn-outline-danger:hover {
    background-color: #E53E3E;
    color: #FFFFFF;
}
.btn-outline-primary:hover {
    background-color: #3182CE;
    color: #FFFFFF;
}

/* Add Class Modal */
.modal-content {
    background-color: #FFFFFF;
    border-radius: 18px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 10px 32px rgba(0, 0, 0, 0.2);
}

/* Modal Header */
.modal-header {
    background-color: #1E293B;
    color: #FFFFFF;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

/* Modal Close Button */
.btn-close-white {
    background-color: transparent;
    border: none;
    color: #FFFFFF;
    font-size: 1.25rem;
    cursor: pointer;
}

/* Form Elements */
.form-label {
    color: #1E293B;
}
.form-control,
.form-select {
    background-color: #FFFFFF;
    color: #1E293B;
    border: 1px solid rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    padding: 10px;
    transition: all 0.3s ease-in-out;
}
.form-control:focus,
.form-select:focus {
    border-color: #1E293B;
    box-shadow: 0 0 12px rgba(30, 41, 59, 0.2);
}

/* Submit Button */
.btn-success {
    background-color: #1E293B;
    color: #FFFFFF;
    transition: all 0.3s ease-in-out;
}
.btn-success:hover {
    background-color: #1E293B;
}

/* Modal Scale Animation */
.custom-modal-scale {
    transform: scale(0.8);
    transition: transform 0.3s ease-in-out;
}
#addClassModal.show .modal-content {
    transform: scale(1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .cont {
        padding: 20px;
    }
    .table-responsive {
        overflow-x: auto;
    }
}

/* Toast Animation */
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

</style>
@endsection
