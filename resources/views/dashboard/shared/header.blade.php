
      
<div class="c-wrapper">
  <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    {{--  --}}
    <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show"><span class="c-header-toggler-icon"></span></button><a class="c-header-brand d-sm-none" href="#"><img class="c-header-brand" src="{{ url('/assets/brand/coreui-base.svg') }}" width="97" height="46" alt="CoreUI Logo"></a>
    {{--  --}}
    <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true"><span class="c-header-toggler-icon"></span></button>
    {{--  --}}
    <ul class="c-header-nav ml-auto mr-4">
      <li class="c-header-nav-item dropdown">
        {{-- <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <div class="c-avatar"><img class="c-avatar-img" src="{{ url('/assets/img/avatars/6.jpg') }}" alt="user@email.com"></div>
        </a> --}}
        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          {{-- 
          <svg class="c-icon mr-2">
            <use xlink:href="{{ url('/icons/sprites/free.svg#cil-settings') }}"></use>
          </svg> 
          --}}
          <i class="cil-cog"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right pt-0">
          {{-- Utilizar para accesos directos --}}
          {{-- 
          <div class="dropdown-header bg-light py-2"><strong>Account</strong></div><a class="dropdown-item" href="#">
            <svg class="c-icon mr-2">
              <use xlink:href="{{ url('/icons/sprites/free.svg#cil-bell') }}"></use>
            </svg> Updates<span class="badge badge-info ml-auto">42</span></a><a class="dropdown-item" href="#">
            <svg class="c-icon mr-2">
              <use xlink:href="{{ url('/icons/sprites/free.svg#cil-envelope-open') }}"></use>
            </svg> Messages<span class="badge badge-success ml-auto">42</span></a><a class="dropdown-item" href="#">
            <svg class="c-icon mr-2">
              <use xlink:href="{{ url('/icons/sprites/free.svg#cil-task') }}"></use>
            </svg> Tasks<span class="badge badge-danger ml-auto">42</span></a><a class="dropdown-item" href="#">
            <svg class="c-icon mr-2">
              <use xlink:href="{{ url('/icons/sprites/free.svg#cil-comment-square') }}"></use>
            </svg> Comments<span class="badge badge-warning ml-auto">42</span></a>
          --}}
          <div class="dropdown-header bg-light py-2">
            <strong>{{ __('dashboard.header.settings') }}</strong>
          </div>
          <a class="dropdown-item" href="{{ route('my-profile.edit') }}">
            <svg class="c-icon mr-2">
              <use xlink:href="{{ url('/icons/sprites/free.svg#cil-user') }}"></use>
            </svg> {{ __('dashboard.header.profile') }}
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <svg class="c-icon mr-2">
                <use xlink:href="{{ url('/icons/sprites/free.svg#cil-account-logout') }}"></use>
              </svg>
              <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
              </form>
              {{ __('auth.logout') }}
          </a>
        </div>
      </li>
    </ul>
    <div class="c-subheader px-3">
      @include('dashboard.shared.breadcrumb')
    </div>
  </header>