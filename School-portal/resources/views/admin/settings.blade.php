@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Header Section -->
<div class="text-center mb-5">
    <h1 class="fw-bold" style="color: #16C47F; font-size: 3rem; letter-spacing: 1px;">
        🗓️ Semester Settings
    </h1>
    <div class="d-inline-block mt-3 p-4 rounded-4 shadow-sm" style="background-color: #ffffff; border-left: 6px solid #16C47F;">
        <h4 class="mb-2" style="color: #555;">
            📚 Current School Year:
            <span class="fw-semibold" style="color: #16C47F;">{{ $currentSchoolYear }} - {{ $currentSchoolYear + 1 }}</span>
        </h4>

        <h5 class="mb-2" style="color: #6c757d;">
            🕓 Current Semester:
            <span class="fw-semibold text-dark">
                @if ($currentSemester == 1)
                    Semester 1
                @elseif ($currentSemester == 2)
                    Semester 2
                @elseif ($currentSemester == 3)
                    Summer Class
                @else
                    Not Set
                @endif
            </span>
        </h5>

        @if ($latestSemester)
        <h6 style="color: #888;">
            📅 Semester Duration:
            <span class="fw-semibold text-dark">
                {{ \Carbon\Carbon::parse($latestSemester->start_date)->format('F d, Y') }}
            </span>
            —
            <span class="fw-semibold text-dark">
                {{ \Carbon\Carbon::parse($latestSemester->end_date)->format('F d, Y') }}
            </span>
        </h6>
        @endif
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

    <!-- Semester Selection Form -->
<form action="{{ route('admin.updateSemester') }}" method="POST" class="mb-4">
    @csrf
    <label for="semester">Select Semester:</label>
    <select name="semester" class="form-control w-25" id="semester-select">
        <option value="1" {{ $currentSemester == 1 ? 'selected' : '' }}>Semester 1</option>
        <option value="2" {{ $currentSemester == 2 ? 'selected' : '' }}>Semester 2</option>
        <option value="3" {{ $currentSemester == 3 ? 'selected' : '' }}>Summer Class</option>
    </select>

    <button type="submit" class="btn btn-primary mt-3"
        {{ $currentSemester == 3 ? 'disabled' : '' }}>
        Update Semester
    </button>
</form>

<!-- Semester Dates Form -->
<form action="{{ route('admin.updateSemesterDates') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="start_date" class="form-label">Enter New School Year Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control w-25" required>
    </div>

    <div class="mb-3">
        <label for="end_date" class="form-label">Enter New School Year End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control w-25" required>
    </div>

    <button type="submit" class="btn btn-success mt-2">Save Semester Dates</button>
</form>


</div>

<!-- Only show the promotion button if semester is '3' (Summer) -->
@if ($currentSemester == 3)
<div class="container mt-4">
    <form action="{{ route('admin.incrementYear') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Promote Students to Next Year</button>
    </form>
</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let semesterSelect = document.getElementById("semester-select");
        let updateButton = document.getElementById("update-semester-btn");

        // Disable "Update Semester" button if Summer (3) is selected
        updateButton.disabled = (semesterSelect.value === "3");
    });
</script>

<style>
  /* Main Container */
  .container {
      background-color: #F5F7FA;
      color: #34495E;
      min-height: 100vh;
  }

  /* Header Title */
  h1.fw-bold {
      color: #34495E;
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
  }

  /* Card Design */
  .card {
      background: #FFFFFF;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      border: none;
      transition: all 0.3s ease-in-out;
  }

  /* Card Header */
  .card-header {
      background: linear-gradient(135deg, #5B9BFF, #8759FF);
      color: #FFFFFF;
      font-size: 1.2rem;
      font-weight: bold;
      border-bottom: none;
  }

  /* Form Elements */
  .form-label {
      color: #34495E;
      font-weight: bold;
  }
  .form-control,
  .form-select {
      background-color: #FFFFFF;
      color: #34495E;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 12px;
      padding: 10px;
      transition: all 0.3s ease-in-out;
  }
  .form-control:focus,
  .form-select:focus {
      border-color: #5B9BFF;
      box-shadow: 0 0 12px rgba(91, 155, 255, 0.3);
  }

  /* Submit Button */
  .btn-success {
      background-color: #5B9BFF;
      color: #FFFFFF;
      border-radius: 12px;
      padding: 10px 18px;
      transition: all 0.3s ease-in-out;
  }
  .btn-success:hover {
      background-color: #4578D3;
      box-shadow: 0 8px 24px rgba(91, 155, 255, 0.3);
  }

  /* Floating Add Button */
  .add-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 12px 20px;
      font-size: 1.2rem;
      z-index: 1050;
      background-color: #5B9BFF;
      color: white;
      transition: all 0.3s ease-in-out;
      border-radius: 50px;
      box-shadow: 0 8px 18px rgba(91, 155, 255, 0.2);
  }

  /* Add Button Hover Effect */
  .add-btn:hover {
      background-color: #4578D3;
      transform: scale(1.1);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
  }

  /* Toast Notification */
  .custom-toast {
      position: fixed;
      top: 20px;
      right: 20px;
      min-width: 300px;
      padding: 12px 20px;
      border-radius: 12px;
      background-color: #5B9BFF;
      color: #FFFFFF;
      display: flex;
      align-items: center;
      gap: 12px;
      z-index: 1050;
      opacity: 0;
      transform: translateX(100%);
      transition: all 0.5s ease-in-out;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
  }

  /* Modal Design */
  .modal-content {
      background-color: #FFFFFF;
      border-radius: 16px;
      box-shadow: 0 10px 32px rgba(0, 0, 0, 0.2);
  }

  /* Modal Header */
  .modal-header {
      background: linear-gradient(135deg, #5B9BFF, #8759FF);
      color: #FFFFFF;
      font-size: 1.2rem;
      font-weight: bold;
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
      box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
  }
</style>

@endsection
