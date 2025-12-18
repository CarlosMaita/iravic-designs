{{-- Submenu below header (Desktop only) --}}
@if($submenuLinks->isNotEmpty())
<div class="bg-light border-bottom d-none d-lg-block">
    <div class="container">
        <nav class="navbar navbar-expand py-0">
            <ul class="navbar-nav gap-4 mx-auto">
                @foreach($submenuLinks as $link)
                    <li class="nav-item">
                        <a class="nav-link text-uppercase fw-medium py-3 px-0" style="font-size: 16px;" href="{{ $link->url }}">
                            {{ $link->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div>
@endif
