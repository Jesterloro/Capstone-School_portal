@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <!-- Header Section -->
        <h1 class="fw-bold mb-4 text-center" style="color: #1E293B; font-size: 2.5rem;">
            📅 Manage Announcements
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

        <!-- Announcement Table Section -->
        <div class="card border-0 shadow-lg rounded-5 overflow-hidden mb-4">
            <div class="card-header gradient-bg text-white fw-bold py-3">
                📚 Existing Announcements+
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle custom-table">
                        <thead class="bg-light text-success">
                            <tr>
                                <th><i class="bi bi-card-heading me-1"></i> Title</th>
                                <th><i class="bi bi-file-text me-1"></i> Description</th>
                                <th><i class="bi bi-image me-1"></i> Image</th>
                                <th><i class="bi bi-gear me-1"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $announcement)
                                <tr>
                                    <td class="fw-bold">{{ $announcement->title }}</td>
                                    <td>{{ $announcement->description ?? 'No Description' }}</td>
                                    <td>
                                        @if ($announcement->image)
                                        <!-- Display image if available -->
                                        <img src="{{ asset('storage/' . $announcement->image) }}"
                                             alt="Announcement Image"
                                             class="rounded shadow-sm img-thumbnail modal-trigger" width="70"
                                             height="70" data-bs-toggle="modal"
                                             data-bs-target="#imageModal{{ $announcement->id }}">
                                    @elseif (str_ends_with($announcement->file, '.pdf'))
                                        <!-- Display PDF link -->
                                        <a href="{{ asset('storage/' . $announcement->file) }}" target="_blank" class="btn btn-info btn-sm pill">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> View PDF
                                        </a>
                                    @elseif (str_ends_with($announcement->file, '.docx') || str_ends_with($announcement->file, '.doc'))
                                        <!-- Display Word file link -->
                                        <a href="{{ asset('storage/' . $announcement->file) }}" target="_blank" class="btn btn-info btn-sm pill">
                                            <i class="bi bi-file-earmark-word me-1"></i> View Word Document
                                        </a>
                                    @else
                                        <span class="text-muted"><i class="bi bi-file-earmark"></i> No Image</span>
                                    @endif

                                    </td>
                                    <td>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal{{ $announcement->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal{{ $announcement->id }}" tabindex="-1"
                                    aria-labelledby="imageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded-5">
                                            <div class="modal-header gradient-bg text-white">
                                                <h5 class="modal-title">📸 Announcement Image</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $announcement->image) }}"
                                                    alt="Announcement Image" class="img-fluid rounded shadow-lg modal-img">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="confirmDeleteModal{{ $announcement->id }}" tabindex="-1"
                                    aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-5">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">❗ Confirm Deletion</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this announcement?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary rounded-pill"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('announcements.destroy', $announcement->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger rounded-pill">Delete</button>
                                                </form>
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

        <!-- Add Announcement Modal -->
        <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-5">
                    <div class="modal-header gradient-bg text-white">
                        <h5 class="modal-title">➕ Add New Announcement</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">📚 Title</label>
                                <input type="text" id="title" name="title" class="form-control rounded-pill shadow-sm" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">📝 Description</label>
                                <input type="text" id="description" name="description" class="form-control rounded-pill shadow-sm">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">🖼️ Upload Image (optional)</label>
                                <input type="file" id="image" name="image" class="form-control rounded-pill shadow-sm">
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label fw-bold">📄 Upload PDF/Word File (optional)</label>
                                <input type="file" id="file" name="file" class="form-control rounded-pill shadow-sm" accept=".pdf,.doc,.docx">
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Announcement Button - Fixed at Bottom Right -->
        <button type="button" class="btn btn-success btn-lg shadow-lg rounded-pill add-btn" data-bs-toggle="modal"
            data-bs-target="#addAnnouncementModal">
            ➕ Add Announcement
        </button>

    </div>

    <!-- JavaScript for Toast and Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.custom-toast').forEach(toast => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateX(0)';
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
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 500);
            }
        }
    </script>

    <!-- Custom CSS -->
    <style>
        /* Main Container */
        .container {
            background-color: #FFFFFF;
            color: #1E293B;
            min-height: 100vh;
        }

        /* Header Title */
        h1.fw-bold {
            color: #1E293B;
            font-size: 2.5rem;
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

        /* Toast Notification */
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
            transform: translateX(100%);
            transition: all 0.5s ease-in-out;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }

        /* Toast Close Button */
        .btn-close-white {
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

        /* Floating Add Button */
        .add-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            font-size: 1.2rem;
            z-index: 1050;
            background-color: #1E293B;
            color: white;
            transition: all 0.3s ease-in-out;
            border-radius: 50px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
        }

        /* Add Button Hover Effect */
        .add-btn:hover {
            background-color: #334155;
            transform: scale(1.1);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        /* Delete Button */
        .btn-outline-danger {
            border-color: #B91C1C;
            color: #B91C1C;
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-danger:hover {
            background-color: #B91C1C;
            color: #FFFFFF;
        }

        /* Modal Animation */
        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        /* Delete Modal Header */
        .modal-header.bg-danger {
            background-color: #B91C1C;
            color: #FFFFFF;
        }

        /* Button for Modal Actions */
        .btn-secondary,
        .btn-danger {
            border-radius: 12px;
            transition: all 0.3s ease-in-out;
        }

        .btn-secondary:hover,
        .btn-danger:hover {
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
@endsection
