@php
    use Illuminate\Support\Facades\App;
@endphp
<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggler border-0 d-md-none" type="button" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>

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

<!-- Sidebar -->
<nav class="sidebar bg-white shadow-sm">
    <div class="sidebar-content p-3">
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
    </div>
</nav>

<style>
.navbar {
    height: 60px;
    z-index: 1030;
}

.navbar-toggler:focus {
    box-shadow: none;
}

.sidebar {
    position: fixed;
    top: 60px;
    left: 0;
    bottom: 0;
    width: 250px;
    overflow-y: auto;
    z-index: 1020;
}

.sidebar .nav-link {
    color: #333;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    margin-bottom: 0.25rem;
}

.sidebar .nav-link:hover {
    background-color: #f8f9fa;
}

.sidebar .nav-link.active {
    background-color: #e9ecef;
    color: #0d6efd;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('show');
}
</script>
