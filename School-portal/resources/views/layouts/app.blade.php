<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/students.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teachers.css') }}">
    <style>
       /* ===== Global Styles ===== */
body {
    font-family: 'Arial', sans-serif;
    padding: 0;
    margin: 0;
    background-color: #FFFFFF; /* Updated to white */
    color: #1E293B; /* Keeping text slightly dark for readability */
}

/* ===== Main Content ===== */
.main-content {
    transition: margin-left 0.4s ease;
    padding-top: 70px;
    margin-left: 250px;
    background-color: #FFFFFF; /* Make content area background also white */
}


        /* ===== Sidebar Styles ===== */
        .sidebar {
            height: 100vh;
            background: linear-gradient(135deg, #0F172A, #1E293B);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            width: 250px;
            transition: all 0.4s ease;
            box-shadow: 6px 0 20px rgba(0, 0, 0, 0.5);
            padding-top: 20px;
            overflow: hidden;
            border-right: 3px solid #1E40AF;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        /* ===== Sidebar Links ===== */
        .sidebar .nav-link {
            color: #F8FAFC;
            padding: 15px 25px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 6px 12px;
            border-radius: 12px;
            transition: all 0.3s ease-in-out;
            position: relative;
            text-decoration: none;
        }

        /* ===== Active Link with Glow Effect ===== */
        .sidebar .nav-link.active {
            background: #1E40AF;
            color: #F8FAFC;
            padding-left: 30px;
            border-left: 5px solid #3B82F6;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6);
            border-radius: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        /* ===== Hover Effect with Soft Glow ===== */
        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #3B82F6;
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        }

        /* ===== Icon Animation ===== */
        .sidebar .nav-link i {
            font-size: 1.5rem;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .sidebar .nav-link:hover i {
            transform: rotate(15deg) scale(1.2);
            color: #3B82F6;
        }

        /* ===== Collapsed Sidebar ===== */
        .sidebar.collapsed {
            width: 75px;
            transition: all 0.4s ease;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px;
            font-size: 1rem;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            font-size: 1.8rem;
        }



        /* Collapsed Sidebar Effect */
        .sidebar.collapsed+.main-content {
            margin-left: 75px;
        }

        /* ===== Toggle Icon ===== */
        .burger-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.75rem;
            cursor: pointer;
            color: #F8FAFC;
            transition: transform 0.3s ease;
        }

        .burger-icon:hover {
            transform: rotate(90deg);
            color: #3B82F6;
        }

        /* ===== Card Styles ===== */
        .dashboard-card {
            background: #1E293B;
            border: 2px solid #3B82F6;
            color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.3);
        }

        /* ===== Icon Styles for Cards ===== */
        .icon-container {
            background: #3B82F6;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .icon {
            font-size: 1.8rem;
            color: #F8FAFC;
        }

        /* ===== Button Style ===== */
        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #1E40AF);
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #2563EB;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5);
        }

        /* ===== Responsive Adjustments ===== */
        @media (max-width: 992px) {
            .sidebar {
                left: -250px;
                transition: left 0.4s ease;
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
            }

            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="burger-icon" id="burger-icon">
                <i class="bi bi-list"></i>
            </div>
            <div class="position-sticky pt-3 mt-5">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-house-door"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('students*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <i class="bi bi-person-fill"></i> <span>Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('teachers*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                            <i class="bi bi-person-badge"></i> <span>Teachers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('subjects*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                            <i class="bi bi-book"></i> <span>Subjects</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('classes*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
                            <i class="bi bi-building"></i> <span>Schedule</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/schoolCalendar') ? 'active' : '' }}" href="{{ route('admin.schoolCalendar') }}">
                            <i class="bi bi-calendar"></i> <span>School Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/announcements') ? 'active' : '' }}" href="{{ route('admin.announcements') }}">
                            <i class="bi bi-megaphone"></i> <span>Announcements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                            <i class="bi bi-gear"></i> <span>Semester Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/logout') ? 'active' : '' }}" href="{{ route('admin.logout') }}">
                            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container-fluid">
            <nav class="navbar navbar-expand-md navbar-light fixed-top">
                <button class="navbar-toggler" type="button" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>

            <div class="main-content p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & Custom Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar collapsed state
        document.getElementById('burger-icon').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');

            // Toggle sidebar collapsed class
            sidebar.classList.toggle('collapsed');

            // Check if sidebar is collapsed and adjust main content width
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = "75px"; // Adjust for collapsed sidebar
            } else {
                mainContent.style.marginLeft = "250px"; // Restore original size
            }
        });
    </script>
</body>

</html>
