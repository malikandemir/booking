@php
    use Illuminate\Support\Facades\App;
@endphp
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" height="32">
        </a>

        <div class="ms-auto d-flex align-items-center">
            <div class="me-4">
                <a href="{{ route('language.switch', 'tr') }}" class="text-decoration-none {{ App::getLocale() == 'tr' ? 'text-primary' : 'text-secondary' }} me-1">TR</a>
                <span class="text-secondary mx-1">|</span>
                <a href="{{ route('language.switch', 'en') }}" class="text-decoration-none {{ App::getLocale() == 'en' ? 'text-primary' : 'text-secondary' }}">EN</a>
            </div>

            @auth
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

<nav class="sidebar bg-white shadow-sm">
    <div class="sidebar-content p-3">
        <!-- Main Navigation -->
        <ul class="nav flex-column">
            @auth
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                        <i class="fas fa-calendar me-2"></i>{{ __('Bookings') }}
                    </a>
                </li>

                @if(auth()->user()->isSuperAdmin())
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                            <i class="fas fa-building me-2"></i>{{ __('Companies') }}
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('items.*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                            <i class="fas fa-car me-2"></i>{{ __('Items') }}
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fas fa-users me-2"></i>{{ __('Users') }}
                        </a>
                    </li>
                @endif
            @endauth
        </ul>

        <!-- Bottom Section -->
        <div class="sidebar-bottom mt-auto">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <div class="nav-item mb-2">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                        </a>
                    </div>
                @endif

                @if (Route::has('register'))
                    <div class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Register') }}
                        </a>
                    </div>
                @endif
            @endguest
        </div>
    </div>
</nav>

<style>
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

.sidebar-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.sidebar .nav-link {
    color: #333;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    transition: all 0.2s;
}

.sidebar .nav-link:hover {
    background-color: #f8f9fa;
}

.sidebar .nav-link.active {
    background-color: #e9ecef;
    color: #0d6efd;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    .main-content {
        margin-left: 0;
    }
}
</style>
