@extends('layouts.instructor')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <!-- Main Card -->
    <div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-3xl relative border-t-8 border-[#1E293B] overflow-hidden">

        <!-- Header Section with Gradient Effect -->
        <div class="text-center mb-8">
            <h1 style="background-color: #1E293B; color: white; border-radius: 20px;" class="text-4xl font-extrabold px-8 py-4 rounded-full shadow-lg inline-block bg-gradient-to-r from-[#1E293B] to-[#14A76C] animate-gradient">
                📅 School Calendar
            </h1>
            <p class="text-[#1E293B] mt-3 text-lg font-semibold">Stay informed about upcoming school events.</p>
        </div>

        <!-- Animated Divider Line -->
        <div class="w-28 h-1 bg-[#1E293B] mx-auto mb-6 rounded-full transition-all duration-300 ease-in-out hover:w-36"></div>
 <!-- School Calendar Section -->
 <div class="mt-4 p-4 bg-white shadow-sm rounded-4 calendar-section">
    <h4 class="fw-bold" style="color: #16C47F;">📅 School Calendar</h4>
    <div class="calendar-container position-relative mt-3">
        <embed src="{{ asset('storage/' . $calendar->image) }}" type="application/pdf" width="100%"
            height="500px" class="rounded-3 shadow-sm border calendar-embed">
    </div>
</div>
</div>

<!-- Custom CSS -->
<style>
    /* Gradient Animation */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .animate-gradient {
        background-size: 200% 200%;
        animation: gradientShift 6s ease infinite;
    }

    /* Modal Transition */
    .modal-content {
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }
    .modal.show .modal-content {
        transform: scale(1.05);
    }
    /* Make School Calendar Full Height */
.calendar-container {
    height: calc(100vh - 250px); /* Adjusted height to account for header and padding */
    max-height: 100vh;
    overflow: hidden;
}

.calendar-embed {
    height: 100%;
    width: 100%;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

/* Handle Responsiveness */
@media (max-width: 768px) {
    .calendar-container {
        height: calc(100vh - 200px);
    }
}

@media (max-width: 576px) {
    .calendar-container {
        height: calc(100vh - 180px);
    }
}
</style>
@endsection
