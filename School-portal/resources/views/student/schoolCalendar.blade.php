@extends('layouts.student')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-50 p-6">
    <!-- Card Container -->
    <div class="bg-white shadow-xl rounded-lg p-6 w-full max-w-3xl border-t-4 border-[#16C47F]">

        <!-- Title with Gradient Effect -->
        <h1 class="text-4xl font-extrabold text-center text-black bg-gradient-to-r from-[#16C47F] to-[#14A76C] text-transparent bg-clip-text">
            📅 School Calendar
        </h1>

        <!-- Divider -->
        <div class="w-20 h-1 bg-[#16C47F] mx-auto my-4 rounded-full"></div>

        <!-- School Calendar Section -->
        <div class="mt-4 p-4 bg-white shadow-sm rounded-4 calendar-section">
            <h4 class="fw-bold text-[#16C47F] mb-3">📅 School Calendar</h4>
            <div class="calendar-container">
                <!-- Iframe for Desktop & Tablet -->
                <iframe id="calendarIframe"
                    src="{{ asset('storage/' . $calendar->image) }}"
                    class="calendar-iframe rounded-3 shadow-sm border"
                    frameborder="0">
                </iframe>

                <!-- Fallback Download Link for Mobile or If Blocked -->
                <div id="calendarFallback" class="hidden">
                    <p class="text-center text-gray-700 mt-4">
                        📥 Unable to display the calendar?
                        <a href="{{ asset('storage/' . $calendar->image) }}" download="School_Calendar_{{ date('Y') }}.pdf"
                            class="text-[#16C47F] font-semibold underline">Download Calendar Here</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('student.studentDashboard') }}"
                class="px-6 py-3 bg-[#16C47F] text-black font-semibold rounded-lg shadow-md transition-all duration-300 hover:bg-[#14A76C] hover:scale-105">
                ⬅ Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- JavaScript for Mobile Detection and Fallback -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const iframe = document.getElementById('calendarIframe');
        const fallback = document.getElementById('calendarFallback');

        // Detect if PDF fails to load (especially on iOS or mobile devices)
        iframe.onerror = function () {
            fallback.classList.remove('hidden');
            iframe.classList.add('hidden');
        };

        // Extra check in case the iframe doesn't load after a delay
        setTimeout(function () {
            if (!iframe.contentWindow || iframe.contentWindow.length === 0) {
                fallback.classList.remove('hidden');
                iframe.classList.add('hidden');
            }
        }, 1500);
    });
</script>

<!-- Custom CSS for Responsive Embed -->
<style>
    /* General Styling */
    body {
        background-color: #f9fafb;
    }

    .rounded-4 {
        border-radius: 20px;
    }

    /* Calendar Section */
    .calendar-container {
        width: 100%;
        max-width: 100%;
        border-radius: 12px;
        overflow: hidden;
    }

    .calendar-iframe {
        width: 100%;
        height: 500px; /* Default height for desktop */
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    /* Fallback Download Link */
    .hidden {
        display: none;
    }

    /* Responsive Handling */
    @media (max-width: 1024px) {
        .calendar-iframe {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .calendar-iframe {
            height: 350px;
        }

        .text-4xl {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .calendar-iframe {
            height: 280px;
        }

        .calendar-section {
            padding: 0.75rem;
        }

        .text-4xl {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
