@extends('layouts.instructor')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 fw-bold text-center text-primary">📅 Instructor Department</h2>

    <div class="card shadow-sm border-0 rounded-4 bg-white">
        <div class="card-header bg-navy-gradient text-white fw-bold rounded-top-4">
            <i class="fas fa-images me-2"></i> Schedule Images
        </div>
        <div class="card-body p-4">
            @if ($scheduleImages->isEmpty())
            <div class="alert alert-info text-center py-4 bg-light text-dark">
                <i class="fas fa-info-circle me-2"></i> No schedule images found.
            </div>
            @else
            <div class="row g-4">
                @foreach ($scheduleImages as $schedule)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center position-relative">
                            <h5 class="card-title text-primary fw-bold mb-3">{{ $schedule->name }}</h5>

                            @if ($schedule->image)
                            <!-- Image Container with Zoom Effect -->
                            <div class="schedule-img-container position-relative" onclick="openModal('{{ asset('storage/' . $schedule->image) }}')">
                                <img src="{{ asset('storage/' . $schedule->image) }}" class="img-fluid rounded-3 schedule-img shadow" alt="Schedule Image">

                                <!-- Hover Overlay -->
                                <div class="img-overlay">
                                    <i class="fas fa-eye fa-2x text-white"></i>
                                </div>
                            </div>
                            @else
                            <p class="text-danger fw-bold">No Image Available</p>
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

<!-- Modal for Larger Image -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header bg-navy-gradient text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i> Schedule Image
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="modalImage" src="" class="img-fluid rounded-4 shadow-sm zoom-effect" alt="Schedule Image">
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

    /* Schedule Image Container */
    .schedule-img-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .schedule-img {
        width: 100%;
        height: auto;
        border-radius: 12px;
        transition: transform 0.4s ease-in-out;
    }

    .schedule-img-container:hover .schedule-img {
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

    .schedule-img-container:hover .img-overlay {
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

    /* Responsive Design */
    @media (max-width: 768px) {
        h2 {
            font-size: 1.8rem;
        }

        .schedule-img {
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

        .schedule-img-container {
            margin-bottom: 1rem;
        }

        .modal-img {
            max-height: 50vh;
        }
    }
</style>

<!-- JavaScript to Load Image in Modal -->
<script>
    function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
        modal.show();
    }
</script>

@endsection
