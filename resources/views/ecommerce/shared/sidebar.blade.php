<div class="c-sidebar-brand test">
    <a href="">
        {{-- <img class="c-sidebar-brand-full" src="{{ url('/assets/brand/coreui-base-white.svg') }}" width="118" height="46" alt="CoreUI Logo"> --}}
        <img class="c-sidebar-brand-minimized" src="{{ asset('img/logo-white.png') }}" width="40" height="40" alt="Logo">
    </a>
</div>
<ul class="c-sidebar-nav">
    {{-- dashboard --}}
    <li class="c-sidebar-nav-item">
        <a class="c-sidebar-nav-link" href="{{route('ecommerce.dashboard')}}"><i class="cil-compass c-sidebar-nav-icon"></i> {{ __('dashboard.breadcrumb.home') }}</a>
    </li>

    {{-- profile --}}
    <li class="c-sidebar-nav-item {{$menuService->isActive($url,"/e/mi-perfil", false, true)}}">
        <a class="c-sidebar-nav-link" href="{{route('ecommerce.myprofile.index')}}">
            <i class="cil-user c-sidebar-nav-icon"></i>{{ __('dashboard.sidebar.my-profile') }}
        </a>
    </li>

</ul>
<button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>