<header class="banner bg-tijolo">
  <div class="container flex flex-row-reverse xl:flex-row">
    @if (! is_front_page())
    <a class="brand" href="{{ home_url('/') }}">
      {{-- {{ get_bloginfo('name', 'display') }} --}}
      <img src="@asset('images/tijolo_logo.png')" width="61" height="43" alt="Tijolo Comidas e Vinhos" class="w-16 py-2" />
    </a>
    @endif
    <nav class="nav-primary flex flex-row justify-evenly items-center w-full py-2" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav text-white text-xl md:text-3xl tracking-widest relative', 'echo' => false]) !!}
      @endif
    </nav>
  </div>
</header>
