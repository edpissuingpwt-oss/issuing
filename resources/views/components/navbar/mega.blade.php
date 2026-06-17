<div class="nav-item dropdown">
    <button class="nav-link" data-toggle="dropdown">
        {{ $title }}
    </button>

    <div class="dropdown-menu mega-menu">

        @foreach($groups as $group => $items)
            <div class="mega-column">

                <div class="mega-title">{{ $group }}</div>

                @foreach($items as $item)
                    <a href="{{ route($item['route']) }}"
                       class="dropdown-item {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach

            </div>
        @endforeach

    </div>
</div>