<nav class="app-navbar">

    {{-- BRAND --}}
    <div class="nav-left">
        <a href="{{ url('/') }}" class="nav-brand">
            <span class="brand-main">ISSUING</span>
            <span class="brand-sep">|</span>
            <span class="brand-sub">IGRPWT</span>
        </a>

        {{-- MENU LOOP --}}
        <div class="nav-menu">
            @foreach(config('menu') as $title => $menu)

                @if(is_assoc($menu))
                    <x-navbar.mega :title="$title" :groups="$menu"/>
                @else
                    <x-navbar.dropdown :title="$title" :items="$menu"/>
                @endif

            @endforeach
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="nav-right">
        <button id="themeToggle" class="nav-icon">
            <img id="themeIcon" src="{{ asset('moon.png') }}" width="20">
        </button>

        <a href="{{ route('logout') }}" class="nav-logout">
            Logout
        </a>
    </div>

</nav>