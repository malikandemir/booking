<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', App::getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Resource Booking System') }}</title>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            padding-top: 60px;
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        .mobile-nav-toggle {
            position: fixed;
            top: 70px;
            left: 1rem;
            z-index: 1031;
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-nav-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Navigation Toggle -->
    <button class="btn btn-primary mobile-nav-toggle d-md-none" type="button" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    @include('layouts.navigation_new')

    <main class="main-content">
        @auth
            @if(auth()->user()->company)
                <div class="bg-white border-bottom mb-4">
                    <div class="container-fluid py-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building text-primary me-2"></i>
                            <span class="text-muted">{{ __('Company') }}:</span>
                            <span class="fw-semibold ms-2">{{ auth()->user()->company->name }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js'></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
    </script>
    @stack('scripts')
</body>
</html>
