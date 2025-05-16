@extends('layouts.app')

@section('content')
<div class="cont py-5">
    <h1 class="text-center mb-5 fw-bold" style="color: #1E293B; font-size: 2.8rem;">
        📅 School Calendar Management
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

    <!-- School Calendar Table -->
    <div class="card shadow-lg border-0 rounded-5 bg-white">
        <div class="card-header gradient-bg text-white fw-bold py-3">
            📅 School Calendar
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle text-center custom-table">
                    <thead class="bg-light text-success">
                        <tr>
                            {{-- <th><i class="bi bi-book-half me-1"></i>Semester</th> --}}
                            <th><i class="bi bi-calendar2 me-1"></i>School Year</th>
                            <th><i class="bi bi-image me-1"></i>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schoolCalendars as $schoolCalendar)
                        <tr>
                            {{-- <td class="fw-bold">{{ $schoolCalendar->semester }}</td> --}}
                            <td>{{ $schoolCalendar->sy }}</td>
                            <td>
                                @if ($schoolCalendar->image)
                                    <a href="{{ asset('storage/' . $schoolCalendar->image) }}" target="_blank">
                                        @if (in_array(pathinfo($schoolCalendar->image, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                            <!-- Display Image -->
                                            <img src="{{ asset('storage/' . $schoolCalendar->image) }}" alt="School Calendar File"
                                                class="rounded shadow-sm img-thumbnail modal-trigger" width="80" height="80"
                                                data-bs-toggle="modal" data-bs-target="#fileModal{{ $schoolCalendar->id }}">
                                        @elseif (pathinfo($schoolCalendar->image, PATHINFO_EXTENSION) == 'pdf')
                                            <!-- Display PDF Link -->
                                            <button class="btn btn-outline-primary btn-sm">
                                                📄 View PDF
                                            </button>
                                        @else
                                            <span class="text-muted"><i class="bi bi-file-earmark"></i> Unsupported File</span>
                                        @endif
                                    </a>
                                @else
                                    <span class="text-muted"><i class="bi bi-file-image"></i> No File</span>
                                @endif
                            </td>

                        </tr>

                        <!-- Image Modal -->
                        <div class="modal fade" id="imageModal{{ $schoolCalendar->id }}" tabindex="-1"
                            aria-labelledby="imageModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title">📸 School Calendar Image</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/' . $schoolCalendar->image) }}" alt="Class Image"
                                            class="img-fluid rounded shadow-lg modal-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Announcement Floating Button (Bottom Right) -->
<button type="button" class="btn btn-success floating-btn shadow-sm" data-bs-toggle="modal"
    data-bs-target="#addAnnouncementModal">
    ➕ Add Calendar
</button>

<!-- Add Announcement Modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-5 custom-modal-scale">
            <div class="modal-header gradient-bg text-white">
                <h5 class="modal-title fw-bold">➕ Add New Calendar</h5>
                <button type="button" class="btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('schoolCalendar.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Removed Semester Selection -->

                    <div class="mb-3">
                        <label class="form-label fw-bold">📅 School Year</label>
                        <input type="text" name="sy" class="form-control rounded-4 shadow-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">🖼️ Upload Image</label>
                        <input type="file" name="image" class="form-control rounded-4 shadow-sm">
                    </div>
                    <button type="submit" class="btn btn-success w-100 rounded-4 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript for Toast and Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(() => {
            document.querySelectorAll('.custom-toast').forEach(toast => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });
        }, 200);

        // Hide toast after 4 seconds
        setTimeout(() => {
            document.querySelectorAll('.custom-toast').forEach(toast => hideToast(toast.id));
        }, 4000);
    });

    function hideToast(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-50%)';
            setTimeout(() => toast.remove(), 500);
        }
    }
</script>

<!-- Custom CSS -->
<style>
/* Main Container */
.cont {
    background-color: #FFFFFF;
    color: #1E293B;
    min-height: 100vh;
}

/* Header Title */
h1.text-center {
    color: #1E293B;
    font-size: 2.8rem;
}

/* Card Design */
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
    font-size: 1.2rem;
    font-weight: bold;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

/* Table Design */
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
    cursor: pointer;
}

/* Floating Button (Bottom Right) */
.floating-btn {
    position: fixed;
    bottom: 20px;
    right: 30px;
    padding: 14px 28px;
    font-size: 1.25rem;
    font-weight: bold;
    border-radius: 50px;
    background-color: #1E293B;
    color: #FFFFFF;
    border: none;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
    z-index: 999;
}

.floating-btn:hover {
    background-color: #334155;
    transform: translateY(-4px);
}

/* Custom Toast Notification */
.custom-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    padding: 12px 20px;
    border-radius: 12px;
    background-color: #1E293B;
    color: #FFFFFF;
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

/* Modal Content */
.modal-content {
    background-color: #FFFFFF;
    border-radius: 18px;
    box-shadow: 0 10px 32px rgba(0, 0, 0, 0.2);
}

/* Modal Header */
.modal-header {
    background-color: #1E293B;
    color: #FFFFFF;
    font-size: 1.2rem;
    font-weight: bold;
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

/* Modal Image */
.modal-img {
    max-width: 100%;
    max-height: 75vh;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

/* Form Elements */
.form-label {
    color: #1E293B;
    font-weight: bold;
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
    background-color: #334155;
}

/* Modal Animation */
.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-out;
}
.modal.show .modal-dialog {
    transform: scale(1);
}

/* Modal Scale Animation */
.custom-modal-scale {
    transform: scale(0.8);
    transition: transform 0.3s ease-in-out;
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
