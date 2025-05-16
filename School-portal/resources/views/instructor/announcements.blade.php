@extends('layouts.instructor')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 fw-bold text-center text-primary">📢 Latest Announcements</h2>

    <div class="card shadow-sm border-0 rounded-4 bg-white">
        <div class="card-header bg-navy-gradient text-white fw-bold rounded-top-4">
            <i class="fas fa-bullhorn me-2"></i> Announcements
        </div>
        <div class="card-body p-4">
            @if ($announcements->isEmpty())
            <div class="alert alert-info text-center py-4 bg-light text-dark">
                <i class="fas fa-info-circle me-2"></i> No announcements available.
            </div>
            @else
            <div class="row g-4">
                @foreach ($announcements as $announcement)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center position-relative">
                            <h5 class="card-title text-primary fw-bold mb-3">{{ $announcement->title }}</h5>

                            @if ($announcement->image)
                            <!-- Image Container with Zoom Effect -->
                            <div class="announcement-img-container position-relative" onclick="openModal('{{ asset('storage/' . $announcement->image) }}', '{{ $announcement->title }}', '{{ $announcement->description }}')">
                                <img src="{{ asset('storage/' . $announcement->image) }}" class="img-fluid rounded-3 announcement-img shadow" alt="Announcement Image">

                                <!-- Hover Overlay -->
                                <div class="img-overlay">
                                    <i class="fas fa-eye fa-2x text-white"></i>
                                </div>
                            </div>
                            @else
                            <p class="text-danger fw-bold">No Image Available</p>
                            @endif

                            <!-- Announcement Description -->
                            <p class="text-muted mt-3 line-clamp-2">{{ Str::limit($announcement->description, 100, '...') }}</p>

                            <!-- Links for PDF and Word Files -->
                            @if ($announcement->file)
                            <div class="mt-3">
                                @php
                                    $fileExtension = strtolower(pathinfo($announcement->file, PATHINFO_EXTENSION));
                                @endphp
                                @if ($fileExtension === 'pdf')
                                    <a href="{{ asset('storage/announcements/files/' . basename($announcement->file)) }}" class="btn btn-outline-primary" target="_blank">
                                        <i class="fas fa-file-pdf me-2"></i> View PDF
                                    </a>
                                @elseif ($fileExtension === 'docx' || $fileExtension === 'doc')
                                    <a href="{{ asset('storage/announcements/files/' . basename($announcement->file)) }}" class="btn btn-outline-success" target="_blank">
                                        <i class="fas fa-file-word me-2"></i> View Word Document
                                    </a>
                                @else
                                    <p class="text-warning">Unsupported file type</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Larger Announcement Image -->
<div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-navy-gradient text-white">
                <h5 class="modal-title">
                    <i class="fas fa-bullhorn me-2"></i> Announcement Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h5 id="modalTitle" class="fw-bold text-primary mb-3"></h5>
                <img id="modalImage" src="" class="img-fluid rounded-4 shadow-sm zoom-effect mb-3" alt="Announcement Image">
                <p id="modalDescription" class="text-muted"></p>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    /* ============================
       General Container Styling
    ============================ */
    .container {
        max-width: 1200px;
    }

    /* Header Styling */
    h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #0f172a; /* Dark Navy Text */
        margin-bottom: 25px;
    }

    /* White Background for Main Card */
    .bg-white {
        background-color: #fff;
    }

    /* Navy Blue Header Gradient */
    .bg-navy-gradient {
        background: linear-gradient(to right, #0f172a, #1e293b);
    }

    /* Card Styling */
    .card {
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    /* Announcement Image Container */
    .announcement-img-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .announcement-img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        transition: transform 0.4s ease-in-out;
    }

    .announcement-img-container:hover .announcement-img {
        transform: scale(1.05);
    }

    /* Hover Overlay with Animation */
    .img-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.7); /* Dark Navy Overlay */
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        border-radius: 12px;
    }

    .announcement-img-container:hover .img-overlay {
        opacity: 1;
    }

    /* Modal Styling */
    .modal-img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    /* Modal Zoom Effect */
    .zoom-effect:hover {
        transform: scale(1.1);
        cursor: zoom-in;
    }

    /* Modal Content Animation */
    .modal-content {
        animation: fadeInUp 0.5s ease-in-out;
    }

    /* Keyframes for Fade-in */
    @keyframes fadeInUp {
        0% {
            transform: translateY(20px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Line Clamp for Description */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        h2 {
            font-size: 1.8rem;
        }

        .announcement-img {
            width: 100%;
            height: auto;
        }

        .modal-img {
            max-height: 60vh;
        }
    }

    @media (max-width: 576px) {
        h2 {
            font-size: 1.5rem;
        }

        .announcement-img-container {
            margin-bottom: 1rem;
        }

        .modal-img {
            max-height: 50vh;
        }
    }
</style>

<!-- JavaScript to Load Announcement in Modal -->
<script>
    function openModal(imageSrc, title, description) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('modalDescription').innerText = description;
        const modal = new bootstrap.Modal(document.getElementById('announcementModal'));
        modal.show();
    }
</script>

@endsection
