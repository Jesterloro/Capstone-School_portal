@extends('layouts.student')

@section('content')
<div class="container my-5">
    <!-- Page Title -->
    <h2 style="color: #1E293B;" class="text-center mb-3 fw-bold">📚 My Schedule</h2>


    <!-- Student Info -->
    <div class="text-center mb-4">
        <p class="mb-1"><strong>👤 Student:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
        <p><strong>📅 Year Level:</strong> {{ $student->year_level }} | <strong>📚 Semester:</strong> {{ $student->semester }}</p>
    </div>

    <!-- Loop through Semesters -->
    @foreach ($subjects as $semester => $semSubjects)
    <h3 class="mt-4 text-center text-success fw-bold">📚 Semester {{ $semester }}</h3>

    <!-- Table Wrapper -->
    <div class="table-responsive shadow-lg rounded-4 overflow-hidden" style="max-height: 450px; overflow-y: auto;">
        <table class="table table-hover table-bordered align-middle mb-0 schedule-table">
            <thead class="table-dark text-center">
                <tr>
                    <th>Code</th>
                    <th>Subject</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Room</th>
                    <th>Teacher</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semSubjects as $subject)
                <tr class="schedule-row">
                    <td class="text-center fw-bold">{{ $subject->code }}</td>
                    <td class="text-center">{{ $subject->name }}</td>
                    <td class="text-center">{{ $subject->day }}</td>
                    <td class="text-center">{{ $subject->time }}</td>
                    <td class="text-center">{{ $subject->room }}</td>
                    <td class="text-center">{{ $subject->teacher->name ?? 'TBA' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-danger fw-bold">No subjects scheduled for this semester.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View: Card Design for Small Devices -->
    <div class="d-block d-lg-none mt-4">
        @forelse ($semSubjects as $subject)
        <div class="card mb-3 shadow-sm border-0 rounded-4">
            <div class="card-body p-3">
                <h5 style="color:white;" class="fw-bold mb-2">{{ $subject->name }}</h5>
                <p class="mb-1"><strong>📄 Code:</strong> {{ $subject->code }}</p>
                <p class="mb-1"><strong>📅 Day:</strong> {{ $subject->day }}</p>
                <p class="mb-1"><strong>⏰ Time:</strong> {{ $subject->time }}</p>
                <p class="mb-1"><strong>🏫 Room:</strong> {{ $subject->room }}</p>
                <p class="mb-0"><strong>👩‍🏫 Teacher:</strong> {{ $subject->teacher ? $subject->teacher->first_name . ' ' . $subject->teacher->last_name : 'TBA' }}</p>
            </div>
        </div>
        @empty
        <div class="alert alert-danger text-center fw-bold">
            <i class="fas fa-exclamation-circle me-1"></i> No subjects scheduled for this semester.
        </div>
        @endforelse
    </div>
    @endforeach
</div>

<!-- Custom CSS -->
<style>
      /* Container Styling */
.container {
    max-width: 1100px;
}

/* Page Title Styling */
h2 {
    color: #1E3A8A; /* Dark navy blue */
    font-weight: bold;
}

h3 {
    color: #1E3A8A;
    font-weight: bold;
    background-color: #F1F5F9;
    padding: 12px 0;
    border-radius: 12px;
}

/* Table Design */
.table {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #E2E8F0; /* Soft border */
}

.table th {
    background-color: #1E293B; /* Dark navy blue */
    color: #fff;
    text-transform: uppercase;
    padding: 12px 16px;
}

.table td {
    background-color: #F8FAFC;
    padding: 12px 16px;
    vertical-align: middle;
}

/* Table Hover Effect */
.schedule-row:hover {
    background-color: #E2E8F0; /* Light hover */
    transition: all 0.3s ease-in-out;
}

/* Mobile Card Styling */
.card {
    background-color: #F8FAFC;
    border: 1px solid #E2E8F0;
    transition: all 0.5s ease-in-out; /* Smooth transition for card */
    opacity: 0;
    transform: translateY(20px); /* Initial position for animation */
}

.card.show {
    opacity: 1;
    transform: translateY(0); /* Final position */
}

.card:hover {
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-5px);
}

.card h5 {
    background-color: #1E293B;
    color: #fff;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 12px;
}

.card-body p {
    color: #334155;
    margin-bottom: 8px;
}

/* Triggering the animation when cards are loaded */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Apply animation to each card */
.card {
    animation: fadeInUp 0.5s ease-in-out forwards;
}

/* Alert Styling */
.alert {
    background-color: #FDF2F8;
    border-color: #FECACA;
    color: #B91C1C;
}

/* Hide table on mobile but keep semester visible */
@media (max-width: 768px) {
    h2 {
        font-size: 1.8rem;
    }

    h3 {
        font-size: 1.25rem;
        display: block; /* Make sure Semester Title is visible */
    }

    .table {
        display: none !important; /* Hide table only */
    }

    .table-responsive {
        display: none !important;
    }

    .card-body p {
        font-size: 0.95rem;
    }
}

@media (min-width: 769px) {
    .table {
        display: table;
    }

    .table-responsive {
        display: block;
    }
}

/* Mobile View Adjustments */
@media (max-width: 480px) {
    .table th,
    .table td {
        padding: 8px;
        font-size: 0.85rem;
    }

    .card-body p {
        font-size: 0.9rem;
    }

    .table-responsive {
        max-height: 350px;
    }
}

</style>
@endsection
