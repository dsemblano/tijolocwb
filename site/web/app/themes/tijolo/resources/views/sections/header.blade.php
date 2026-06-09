<header id="banner" class="sticky banner w-full z-50 bg-tijolopinkhover top-0 left-0 shadow">
    <div class="container">
        <div id="banner-inner" class="flex flex-row py-4 text-white justify-between lg:justify-start lg:items-center transition-all duration-300">
            @include('partials.logo')
            @if (has_nav_menu('primary_navigation'))
                <nav id="banner-nav" class="nav-primary lg:w-full" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
                    @include('partials.menu')
                </nav>
            @endif
        </div>
    </div>
</header>