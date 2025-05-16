@extends('layouts.student')

@section('content')
<div class="container py-5">
    <!-- Profile Card Wrapper -->
    <div class="profile-wrapper mx-auto shadow-lg rounded-4 overflow-hidden animate-card" style="max-width: 900px; background-color: #fff;">
        <!-- Header Section -->
        <div class="profile-header text-white text-center py-4">
            <h2 class="fw-bold mb-0 animate-title">Student Profile</h2>
        </div>

        <!-- Profile Body -->
        <div class="card-body p-4 bg-white text-dark rounded-bottom-4">
            <!-- Profile Picture and Upload -->
            <div class="text-center mb-4">
                <div class="border rounded-circle overflow-hidden profile-img mx-auto" style="width: 150px; height: 150px;">
                    <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : asset('images/default-profile.png') }}"
                        alt="Profile Picture"
                        class="img-fluid w-100 h-100 animate-profile"
                        style="object-fit: cover;">
                </div>

                <!-- Upload Profile Picture Form -->
                <form action="{{ route('student.uploadPp') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <input type="file" name="profile_picture" class="form-control form-control-sm mb-2 mx-auto bg-white border-secondary" style="max-width: 200px;">
                    <button type="submit" class="btn profile-btn mx-auto d-block">Upload New Picture</button>
                </form>
                <div class="text-center mt-3 mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        ✏️ Edit Profile
                    </button>
                </div>
            </div>

            <!-- Contact Details -->
            <div class="section-header mb-4">
                <h5 class="section-title">Contact Details</h5>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="details-box">
                        <p><strong>📧 Email:</strong> {{ $student->email }}</p>
                        <p><strong>🏠 Address:</strong> {{ $student->address }}</p>
                        <p><strong>📅 Birthplace:</strong> {{ $student->bplace }}</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="details-box">
                        <p><strong>📞 Contact Number:</strong> {{ $student->cell_no }}</p>
                        <p><strong>🏫 Department:</strong> {{ $student->department->name }}</p>
                        <p><strong>📚 Year Level:</strong> {{ $student->year_level }}</p>
                    </div>
                </div>
            </div>

            <!-- Parent Info -->
            <div class="section-header mb-4">
                <h5 class="section-title">Parent's Information</h5>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="details-box">
                        <h6 class="fw-bold mb-2">Father's Information</h6>
                        <p><strong>First Name:</strong> {{ $student->father_first_name }}</p>
                        <p><strong>Middle Name:</strong> {{ $student->father_middle_name }}</p>
                        <p><strong>Last Name:</strong> {{ $student->father_last_name }}</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="details-box">
                        <h6 class="fw-bold mb-2">Mother's Information</h6>
                        <p><strong>First Name:</strong> {{ $student->mother_first_name }}</p>
                        <p><strong>Middle Name:</strong> {{ $student->mother_middle_name }}</p>
                        <p><strong>Last Name:</strong> {{ $student->mother_last_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Educational Background -->
            <div class="section-header mb-4">
                <h5 class="section-title">Educational Background</h5>
            </div>
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <div class="details-box">
                        <h6 class="fw-bold mb-2">Elementary</h6>
                        <p><strong>School Name:</strong> {{ $student->elem_school_name }}</p>
                        <p><strong>Year Graduated:</strong> {{ $student->elem_grad_year }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="details-box">
                        <h6 class="fw-bold mb-2">Secondary</h6>
                        <p><strong>School Name:</strong> {{ $student->hs_school_name }}</p>
                        <p><strong>Year Graduated:</strong> {{ $student->hs_grad_year }}</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="details-box">
                        <h6 class="fw-bold mb-2">Tertiary</h6>
                        <p><strong>School Name:</strong> {{ $student->tertiary_school_name }}</p>
                        <p><strong>Year Graduated:</strong> {{ $student->tertiary_grad_year }}</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information Moved to Bottom -->
            <div class="section-header mb-4">
                <h5 class="section-title">Personal Information</h5>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="details-box">
                        <p><strong>🎓 Student ID:</strong> {{ $student->student_id }}</p>
                        <p><strong>👤 Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
                        <p><strong>🎂 Birthday:</strong> {{ $student->bdate }}</p>
                        <p><strong>⚥ Sex:</strong> {{ $student->sex }}</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="details-box">
                        <p><strong>💼 Civil Status:</strong> {{ $student->civil_status }}</p>
                        <p><strong>📧 Email:</strong> {{ $student->email }}</p>
                        <p><strong>📞 Contact Number:</strong> {{ $student->cell_no }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unified Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
      <form method="POST" action="{{ route('student.updateProfile') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Student Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <!-- Mandatory Fields Note -->
            <div class="alert alert-info">
              <strong>Note:</strong> The following fields are mandatory for clearance:
              <ul>
                <li>Email</li>
                <li>Contact Number</li>
                <li>Address</li>
                <li>Father's & Mother's Information</li>
                <li>Educational Background</li>
                <li>Personal Information (Full Name, Birthdate, Sex)</li>
              </ul>
              <p>Please make sure to fill all the required fields before submitting.</p>
            </div>

            {{-- Contact Details --}}
            <h6 class="fw-bold mb-2">📞 Contact Details</h6>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ $student->email }}" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Contact Number <span class="text-danger">*</span></label>
                <input type="text" name="cell_no" value="{{ $student->cell_no }}" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Address <span class="text-danger">*</span></label>
                <input type="text" name="address" value="{{ $student->address }}" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Birthplace</label>
                <input type="text" name="bplace" value="{{ $student->bplace }}" class="form-control">
              </div>
            </div>

            {{-- Parent's Information --}}
            <h6 class="fw-bold mt-4 mb-2">👨‍👩‍👧 Parent's Information</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label>Father's First Name <span class="text-danger">*</span></label>
                <input type="text" name="father_first_name" value="{{ $student->father_first_name }}" class="form-control" required>
                <label class="mt-2">Middle Name</label>
                <input type="text" name="father_middle_name" value="{{ $student->father_middle_name }}" class="form-control">
                <label class="mt-2">Last Name</label>
                <input type="text" name="father_last_name" value="{{ $student->father_last_name }}" class="form-control">
              </div>
              <div class="col-md-6">
                <label>Mother's First Name <span class="text-danger">*</span></label>
                <input type="text" name="mother_first_name" value="{{ $student->mother_first_name }}" class="form-control" required>
                <label class="mt-2">Middle Name</label>
                <input type="text" name="mother_middle_name" value="{{ $student->mother_middle_name }}" class="form-control">
                <label class="mt-2">Last Name</label>
                <input type="text" name="mother_last_name" value="{{ $student->mother_last_name }}" class="form-control">
              </div>
            </div>

            {{-- Educational Background --}}
            <h6 class="fw-bold mt-4 mb-2">🎓 Educational Background</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label>Elementary School <span class="text-danger">*</span></label>
                <input type="text" name="elem_school_name" value="{{ $student->elem_school_name }}" class="form-control" required>
                <label class="mt-2">Graduation Year <span class="text-danger">*</span></label>
                <input type="number" name="elem_grad_year" value="{{ $student->elem_grad_year }}" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label>High School <span class="text-danger">*</span></label>
                <input type="text" name="hs_school_name" value="{{ $student->hs_school_name }}" class="form-control" required>
                <label class="mt-2">Graduation Year <span class="text-danger">*</span></label>
                <input type="number" name="hs_grad_year" value="{{ $student->hs_grad_year }}" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label>Tertiary School <span class="text-danger">*</span></label>
                <input type="text" name="tertiary_school_name" value="{{ $student->tertiary_school_name }}" class="form-control" required>
                <label class="mt-2">Graduation Year <span class="text-danger">*</span></label>
                <input type="number" name="tertiary_grad_year" value="{{ $student->tertiary_grad_year }}" class="form-control" required>
              </div>
            </div>

            {{-- Personal Info --}}
            <h6 class="fw-bold mt-4 mb-2">🧍 Personal Information</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label>First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" value="{{ $student->first_name }}" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label>Middle Name <span class="text-danger">*</span></label>
                <input type="text" name="middle_name" value="{{ $student->middle_name }}" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label>Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" value="{{ $student->last_name }}" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Student ID</label>
                <input type="text" name="student_id_display" value="{{ $student->student_id }}" class="form-control" disabled>
              </div>
              <div class="col-md-6">
                <label>Birthday <span class="text-danger">*</span></label>
                <input type="date" name="bdate" value="{{ $student->bdate }}" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Sex <span class="text-danger">*</span></label>
                <select name="sex" class="form-select" required>
                  <option value="Male" {{ $student->sex === 'Male' ? 'selected' : '' }}>Male</option>
                  <option value="Female" {{ $student->sex === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
              </div>
              <div class="col-md-6">
                <label>Civil Status <span class="text-danger">*</span></label>
                <input type="text" name="civil_status" value="{{ $student->civil_status }}" class="form-control" required>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-success" type="submit">💾 Save Changes</button>
          </div>
        </div>
      </form>
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

<!-- Custom CSS for Modern Aesthetics -->
<style>
    /* ===== Profile Wrapper ===== */
    .profile-wrapper {
        background-color: #fff;
        border: 1px solid #e9ecef;
        transition: all 0.4s ease-in-out;
    }

    .profile-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }

    /* ===== Header Section ===== */
    .profile-header {
        background: linear-gradient(to right, #0F172A, #1E293B);
        transition: background 0.3s ease-in-out;
    }

    .profile-header:hover {
        background: linear-gradient(to right, #1E293B, #0F172A);
    }

    /* ===== Profile Picture ===== */
    .profile-img {
        border: 4px solid #0EA5E9;
        transition: all 0.3s ease-in-out;
    }

    .profile-img:hover {
        border-color: #38BDF8;
        transform: scale(1.1);
        box-shadow: 0 10px 20px rgba(14, 165, 233, 0.5);
    }

    /* ===== Section Header with Dark Navy Blue Background ===== */
    .section-header {
        background-color: #1E293B;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #fff;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ===== Info Box ===== */
    .details-box {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease-in-out;
    }

    .details-box:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        transform: translateY(-3px);
    }

    /* ===== Upload Button ===== */
    .profile-btn {
        background-color: #0EA5E9;
        color: #fff;
        border: none;
        padding: 8px 20px;
        transition: all 0.3s ease-in-out;
    }

    .profile-btn:hover {
        background-color: #38BDF8;
        transform: scale(1.05);
    }

    /* ===== Animations ===== */
    .animate-title {
        animation: fadeInDown 0.8s ease-in-out;
    }

    .animate-profile {
        animation: fadeInUp 0.8s ease-in-out;
    }

    .animate-card {
        animation: fadeIn 0.9s ease-in-out;
    }

    /* ===== Keyframes for Animations ===== */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 768px) {
        .profile-wrapper {
            padding: 20px;
        }

        .details-box {
            padding: 12px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
        }

        .profile-btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .profile-header h2 {
            font-size: 1.3rem;
        }

        .details-box {
            padding: 10px;
        }

        .profile-img {
            width: 80px;
            height: 80px;
        }
    }



</style>
@endsection
