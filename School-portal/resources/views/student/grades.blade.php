@extends('layouts.student')

@section('content')
<div class="container my-5">
    <!-- Page Title -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-success">{{ $student->first_name }}'s Grades</h2>
        <h5 class="text-muted">Year Level: {{ $student->year_level }} | Semester: {{ $currentSemester }}</h5>
    </div>

    @php
        // Filter grades to only include the current semester and year level
        $filteredGrades = $grades->where('semester', $currentSemester)->where('year_level', $student->year_level);
    @endphp

    <!-- No Grades Available -->
    @if ($filteredGrades->isEmpty())
        <div class="alert alert-warning text-center rounded-3 shadow-sm">
            <i class="fas fa-info-circle me-1"></i> No grades available yet for Semester {{ $currentSemester }}.
        </div>
    @else
        <!-- Grades Table (PC/Laptop View) -->
        <div class="table-container table-responsive shadow-lg rounded-4 overflow-hidden" style="max-height: 900px; overflow-y: auto;">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Units</th>
                        <th>Grade</th>
                        <th>Year Level</th>
                        <th>Instructor</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                            @foreach ($filteredGrades as $grade)
                            @php
                            // Round grades correctly and apply conversion
                            if (!is_null($grade->grade)) {
                                $roundedGrade = ($grade->grade - floor($grade->grade) >= 0.50) ? ceil($grade->grade) : floor($grade->grade);

                                if ($roundedGrade == 0) {
                                    $gradeValue = 'INC';
                                } elseif ($roundedGrade >= 75) {
                                    // Convert the grade to the corresponding grade equivalent
                                    $gradeConversion = [
                                        100 => '1.0', 99 => '1.1', 98 => '1.2', 97 => '1.3', 96 => '1.4', 95 => '1.5',
                                        94 => '1.6', 93 => '1.7', 92 => '1.8', 91 => '1.9', 90 => '2.0', 89 => '2.1',
                                        88 => '2.2', 87 => '2.3', 86 => '2.4', 85 => '2.5', 84 => '2.6', 83 => '2.7',
                                        82 => '2.8', 81 => '2.9', 80 => '3.0', 79 => '3.1', 78 => '3.2', 77 => '3.3',
                                        76 => '3.4', 75 => '3.5',
                                    ];
                                    $gradeValue = $gradeConversion[$roundedGrade] ?? 'N/A';
                                } else {
                                    $gradeValue = '5.0'; // Fail if below 75
                                }
                            } else {
                                $roundedGrade = null; // Handle undefined case
                                $gradeValue = 'N/A';
                            }

                            // Grade Class Logic
                            if ($gradeValue === 'INC') {
                                $gradeClass = 'text-warning fw-bold'; // Yellow for INC
                            } elseif ($gradeValue === '5.0') {
                                $gradeClass = 'text-danger fw-bold'; // Red for failing grades
                            } elseif (!is_null($roundedGrade) && $roundedGrade >= 75) {
                                $gradeClass = 'text-success fw-bold'; // Green for passing grades
                            } else {
                                $gradeClass = 'text-muted'; // Default for N/A
                            }
                        @endphp
                        <tr>
                            <td>{{ $grade->subject->code }}</td>
                            <td>{{ $grade->subject->name }}</td>
                            <td>{{ $grade->subject->units }}</td>
                            <td class="{{ $gradeClass }}">{{ $gradeValue }}</td>
                            <td>{{ $grade->year_level }}</td>
                            <td>{{$grade->subject->teacher->name ?? 'N/A'}}</td> <!---Fetch Teacher --->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Card Layout) -->
        <div class="d-block d-lg-none mt-4">
            @foreach ($filteredGrades as $grade)
            @php
            // Round grades correctly and apply conversion for mobile view
            if (!is_null($grade->grade)) {
                $roundedGrade = ($grade->grade - floor($grade->grade) >= 0.50) ? ceil($grade->grade) : floor($grade->grade);

                if ($roundedGrade == 0) {
                    $gradeValue = 'INC';
                } elseif ($roundedGrade >= 75) {
                    // Convert the grade to the corresponding grade equivalent
                    $gradeConversion = [
                        100 => '1.0', 99 => '1.1', 98 => '1.2', 97 => '1.3', 96 => '1.4', 95 => '1.5',
                        94 => '1.6', 93 => '1.7', 92 => '1.8', 91 => '1.9', 90 => '2.0', 89 => '2.1',
                        88 => '2.2', 87 => '2.3', 86 => '2.4', 85 => '2.5', 84 => '2.6', 83 => '2.7',
                        82 => '2.8', 81 => '2.9', 80 => '3.0', 79 => '3.1', 78 => '3.2', 77 => '3.3',
                        76 => '3.4', 75 => '3.5',
                    ];
                    $gradeValue = $gradeConversion[$roundedGrade] ?? 'N/A';
                } else {
                    $gradeValue = '5.0'; // Fail if below 75
                }
            } else {
                $roundedGrade = null; // Handle undefined case
                $gradeValue = 'N/A';
            }

            // Grade Class Logic for Mobile View
            if ($gradeValue === 'INC') {
                $gradeClass = 'text-warning fw-bold'; // Yellow for INC
            } elseif ($gradeValue === '5.0') {
                $gradeClass = 'text-danger fw-bold'; // Red for failing grades
            } elseif (!is_null($roundedGrade) && $roundedGrade >= 75) {
                $gradeClass = 'text-success fw-bold'; // Green for passing grades
            } else {
                $gradeClass = 'text-muted'; // Default for N/A
            }
        @endphp
                <div class="card mb-3 shadow-sm border-0 rounded-4 overflow-hidden" style="border: 1px solid #e9ecef; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                    <!-- Card Header -->
                    <div class="card-header text-white text-center fw-bold py-2"
                        style="background: linear-gradient(135deg, #1E293B, #0F172A); font-size: 1.2rem; padding: 12px 0;">
                        📘 {{ $grade->subject->name }}
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4" style="background-color: #f8f9fa;">
                        <div style="margin-bottom: 12px;">
                            <strong style="color: #1E293B;">📚 Subject Code:</strong>
                            <span style="color: #6c757d;">{{ $grade->subject->code }}</span>
                        </div>

                        <div style="margin-bottom: 12px;">
                            <strong style="color: #1E293B;">🎯 Units:</strong>
                            <span style="color: #6c757d;">{{ $grade->subject->units }}</span>
                        </div>

                        <div style="margin-bottom: 12px;">
                            <strong style="color: #1E293B;">📅 Year Level:</strong>
                            <span style="color: #6c757d;">{{ $grade->year_level }}</span>
                        </div>

                        <div style="margin-bottom: 12px;">
                            <strong style="color: #1E293B;">🏫 Instructor:</strong>
                            <span style="color: #6c757d;">{{ $grade->subject->teacher->name ?? 'N/A' }}</span>
                        </div>

                        <div style="margin-top: 8px;">
                            <strong style="color: #1E293B;">📊 Grade:</strong>
                            <span class="{{ $gradeClass }}"
                                style="color: {{ $gradeValue == 'Passed' ? '#16A34A' : ($gradeValue == 'Failed' ? '#DC2626' : '#F59E0B') }}; font-weight: bold;">
                                {{ $gradeValue }}
                            </span>
                        </div>
                    </div>
                </div>


            @endforeach
        </div>
    @endif
</div>

<!-- Custom CSS -->
<style>
    /* Container Style */
    .container {
        max-width: 1100px;
    }

    /* Table Design */
    .table {
        border-radius: 12px;
        overflow: hidden;
    }

    .table th,
    .table td {
        padding: 12px 16px;
        text-align: center;
        vertical-align: middle;
    }

    /* Grade Row Hover Effect */
    .grade-row:hover {
        background-color: #f1f9f3;
        transition: all 0.3s ease-in-out;
    }

    /* Card Design for Mobile */
    .card {
        background-color: #f9f9f9;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: scale(1.02);
    }

    /* Success, Danger, and Warning Colors for Grades */
    .text-success {
        color: #198754 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-warning {
        color: #FFC107 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        h2 {
            font-size: 1.8rem;
        }

        h5 {
            font-size: 1rem;
        }

        .table {
            font-size: 0.9rem;
        }

        .card-body p {
            font-size: 0.95rem;
        }

        .table-responsive {
            max-height: 400px;
        }
    }

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

    /* Hide Table on Mobile */
    @media (max-width: 768px) {
        .table-container {
            display: none;
        }
    }

    /* ===== Mobile Card Animation and Transition ===== */
.card {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.4s ease-in-out;
    animation: fadeInCard 0.6s ease-in-out forwards;
}

/* ===== Animation for Fade-In Effect on Cards ===== */
@keyframes fadeInCard {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== Hover Effect for Better Interaction ===== */
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}

/* ===== Delayed Animation for Consecutive Cards ===== */
.card:nth-child(1) {
    animation-delay: 0.1s;
}
.card:nth-child(2) {
    animation-delay: 0.2s;
}
.card:nth-child(3) {
    animation-delay: 0.3s;
}
.card:nth-child(4) {
    animation-delay: 0.4s;
}
.card:nth-child(5) {
    animation-delay: 0.5s;
}
.card:nth-child(6) {
    animation-delay: 0.6s;
}

/* ===== Responsive Tweaks for Smaller Devices ===== */
@media (max-width: 768px) {
    .card {
        animation: fadeInCard 0.5s ease-in-out forwards;
    }
}

@media (max-width: 480px) {
    .card {
        animation: fadeInCard 0.4s ease-in-out forwards;
    }
}
/* Hide Mobile View Cards on iPad and Laptop */
@media (min-width: 768px) {
    .d-block.d-lg-none {
        display: none !important;  /* Hide the mobile cards on iPad, tablets, and laptops */
    }
}
</style>
@endsection
