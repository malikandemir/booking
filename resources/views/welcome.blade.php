<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero {
            background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            background: #EEF2FF;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .feature-icon i {
            font-size: 24px;
            color: #4F46E5;
        }
        .stats-box {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            text-align: center;
            margin-top: -50px;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4F46E5;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" height="32">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('language.switch', 'en') }}" class="nav-link {{ app()->getLocale() == 'en' ? 'active fw-bold' : '' }}">EN</a>
                            <span class="mx-1">|</span>
                            <a href="{{ route('language.switch', 'tr') }}" class="nav-link {{ app()->getLocale() == 'tr' ? 'active fw-bold' : '' }}">TR</a>
                        </div>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">{{ __('Dashboard') }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">{{ __('Login') }}</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    @if(config('app.debug'))
                        <div class="alert alert-info">
                            Current Locale: {{ app()->getLocale() }}<br>
                            Session Locale: {{ session('locale') }}<br>
                        </div>
                    @endif
                    <h1 class="display-4 fw-bold mb-4">{{ __('Efficient Resource Booking System') }}</h1>
                    <p class="lead mb-4">{{ __('Streamline your resource management with our intuitive booking platform. Perfect for companies of all sizes.') }}</p>
                    @guest
                        <div class="d-flex gap-3">
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">
                                {{ __('Get Started') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                                {{ __('Sign Up') }}
                            </a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stats-box">
                    <div class="stats-number">{{ \App\Models\Company::count() }}</div>
                    <div class="text-muted">{{ __('Companies') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-box">
                    <div class="stats-number">{{ \App\Models\Item::count() }}</div>
                    <div class="text-muted">{{ __('Resources') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-box">
                    <div class="stats-number">{{ \App\Models\Booking::count() }}</div>
                    <div class="text-muted">{{ __('Bookings Made') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">{{ __('Key Features') }}</h2>
                <p class="text-muted">{{ __('Everything you need to manage your resources effectively') }}</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="h5 fw-bold">{{ __('Easy Booking') }}</h3>
                    <p class="text-muted">{{ __('Simple and intuitive booking process for all your resources.') }}</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="h5 fw-bold">{{ __('Role-Based Access') }}</h3>
                    <p class="text-muted">{{ __('Secure access control with customizable user roles and permissions.') }}</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="h5 fw-bold">{{ __('Real-Time Availability') }}</h3>
                    <p class="text-muted">{{ __('Check resource availability in real-time and avoid conflicts.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 bg-light mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="{{ route('privacy.policy') }}" class="text-muted text-decoration-none">{{ __('Privacy Policy') }}</a>
                        <a href="{{ route('terms.service') }}" class="text-muted text-decoration-none">{{ __('Terms of Service') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
