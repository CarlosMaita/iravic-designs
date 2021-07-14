<div class="c-sidebar-brand test">
  <a href="{{ route('admin.home') }}">
      <img class="c-sidebar-brand-full" src="{{ url('/assets/brand/coreui-base-white.svg') }}" width="118" height="46" alt="CoreUI Logo">
      <img class="c-sidebar-brand-minimized" src="{{ url('/assets/brand/coreui-signet-white.svg') }}" width="118" height="46" alt="CoreUI Logo">
  </a>
</div>
<ul class="c-sidebar-nav">
  {{-- <li class="c-sidebar-nav-item">
      <a class="c-sidebar-nav-link" href=""><i class="cil-calculator c-sidebar-nav-icon"></i>Inicio</a>
  </li> --}}
  {{-- Configuration links --}}
  <li class="c-sidebar-nav-dropdown {{
      $menuService->isActive($url,"/admin/config/usuarios", false, true) . " " .
      $menuService->isActive($url,"/admin/config/roles", false, true) . " " . 
      $menuService->isActive($url,"/admin/config/permisos", false, true)
  }}">
      <a class="c-sidebar-nav-dropdown-toggle" href="#"><i class="cil-calculator c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.settings') }}</a>
      <ul class="c-sidebar-nav-dropdown-items">
          {{--  --}}
          @if (Auth::user()->can('viewany', App\Models\Role::class) || Auth::user()->can('viewany', App\Models\Permission::class))
              <li class="c-sidebar-nav-dropdown {{
                          $menuService->isActive($url,"/admin/config/roles", false, true) . " " . 
                          $menuService->isActive($url,"/admin/config/permisos", false, true)
                      }}">
                  <a class="c-sidebar-nav-dropdown-toggle" href="#">{{ __('dashboard.sidebar.privileges') }}</a>
                  <ul class="c-sidebar-nav-dropdown-items">
                      @can('viewany', App\Models\Role::class)
                          <li class="c-sidebar-nav-item">
                              <a class="c-sidebar-nav-link" href="{{ route('roles.index') }}"><span class="c-sidebar-nav-icon"></span>{{ __('dashboard.sidebar.roles') }}</a>
                          </li>
                      @endcan
                      {{--  --}}
                      @can('viewany', App\Models\Permission::class)
                          <li class="c-sidebar-nav-item">
                              <a class="c-sidebar-nav-link" href="{{ route('permisos.index') }}"><span class="c-sidebar-nav-icon"></span>{{ __('dashboard.sidebar.permissions') }}</a>
                          </li>
                      @endcan
                  </ul>
              </li>
          @endif
          {{--  --}}
          @can('viewany', App\User::class)
              <li class="c-sidebar-nav-item">
                  <a class="c-sidebar-nav-link {{$menuService->isActive($url,"/admin/config/usuarios")}}" href="{{ route('usuarios.index') }}"></span>{{ __('dashboard.sidebar.users') }}</a>
              </li>
          @endcan
      </ul>
  </li>
</ul>
<button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>