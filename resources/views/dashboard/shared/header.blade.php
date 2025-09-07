
<!-- Header -->
<header class="navbar navbar-expand-lg navbar-light bg-light border-bottom shadow-sm" style="margin-left: 280px;">
    <div class="container-fluid">
        
        <!-- Mobile menu toggle -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Breadcrumb -->
        <div class="flex-grow-1">
            @include('dashboard.shared.breadcrumb')
        </div>
        
        <!-- User menu -->
        <div class="dropdown">
            <a class="nav-link dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ci-settings fs-lg me-2"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">{{ __('dashboard.header.settings') }}</h6></li>
                <li>
                    <a class="dropdown-item" href="{{ route('my-profile.edit') }}">
                        <i class="ci-user me-2"></i> {{ __('dashboard.header.profile') }}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ci-sign-out me-2"></i> {{ __('auth.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>