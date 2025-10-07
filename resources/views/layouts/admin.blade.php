<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>
    <base href="{{ url('/') }}/">

    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 4px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
            margin: 2px 12px;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.15);
            transform: translateX(4px);
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            box-shadow: 0 2px 8px rgba(255,255,255,0.1);
        }
        
        .sidebar .sidebar-heading {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 16px;
            margin: 20px 0 8px 0;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .top-navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .content-wrapper {
            padding: 24px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .table thead th {
            background: #f8fafc;
            border: none;
            font-weight: 600;
            color: #4a5568;
            padding: 16px 12px;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.001);
        }
        
        .badge {
            font-weight: 500;
            border-radius: 6px;
            padding: 6px 12px;
        }
        
        .logo-section {
            padding: 24px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 8px;
        }
        
        .logo-section h4 {
            color: white;
            font-weight: 700;
            margin: 0;
        }
        
        .sidebar-toggle {
            display: none;
            background: var(--bs-primary);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: inline-block;
            }
            
            .content-wrapper {
                padding: 16px;
            }
        }
        
        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }
        
        /* Form Styling */
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        /* DataTable custom styling */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 20px;
            padding: 8px 16px;
            border: 1px solid #d1d5db;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            padding: 4px 8px;
            border: 1px solid #d1d5db;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="logo-section text-center">
            <h4><i class="fas fa-cogs me-2"></i>Admin Panel</h4>
        </div>
        
        <ul class="nav flex-column px-0">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    Dashboard
                </a>
            </li>
            
            <div class="sidebar-heading">Master Management</div>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                   href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-layer-group me-3"></i>
                    Categories
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.sub-categories.*') ? 'active' : '' }}" 
                   href="{{ route('admin.sub-categories.index') }}">
                    <i class="fas fa-sitemap me-3"></i>
                    Sub Categories
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.segments.*') ? 'active' : '' }}" 
                   href="{{ route('admin.segments.index') }}">
                    <i class="fas fa-puzzle-piece me-3"></i>
                    Segments
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.sub-segments.*') ? 'active' : '' }}" 
                   href="{{ route('admin.sub-segments.index') }}">
                    <i class="fas fa-th-large me-3"></i>
                    Sub Segments
                </a>
            </li>
            
            <div class="sidebar-heading">Product Management</div>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
                   href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box me-3"></i>
                    Products
                </a>
            </li>
            
            <div class="sidebar-heading">Account</div>
            
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <a class="nav-link text-light" href="#" 
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt me-3"></i>
                        Logout
                    </a>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="h3 mb-0 fw-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">Welcome, {{ auth()->user()->name }}</span>
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        Account
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Validation Error:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    
    <!-- Global Scripts -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        $(document).click(function(event) {
            if ($(window).width() <= 768) {
                if (!$(event.target).closest('.sidebar, .sidebar-toggle').length) {
                    $('#sidebar').removeClass('show');
                }
            }
        });

        // Delete confirmation with SweetAlert
        function confirmDelete(url, title = 'Delete Item') {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'swal2-popup-custom',
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Item has been deleted.',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                if (typeof dataTable !== 'undefined') {
                                    dataTable.ajax.reload();
                                } else {
                                    location.reload();
                                }
                            } else {
                                Swal.fire('Error!', response.message || 'Something went wrong.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        }

        // Initialize tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Add loading state to forms
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').attr('disabled', true).prepend('<i class="fas fa-spinner fa-spin me-1"></i>');
        });
    </script>

    @stack('scripts')
</body>
</html>