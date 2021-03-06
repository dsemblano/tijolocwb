<header class="banner bg-tijolo">
  <div class="container flex flex-row-reverse xl:flex-row">
    @if (! is_front_page())
    <h1 class="z-50">
      <a class="brand" href="{{ home_url('/') }}">
        {{-- {{ get_bloginfo('name', 'display') }} --}}
        <img src="@asset('images/tijolo_logo.png')" width="61" height="43" alt="Tijolo Comidas e Vinhos" class="w-16 py-2" />
      </a>
    </h1>
    @endif
    <nav class="nav-primary flex flex-row justify-evenly items-center w-full py-2">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav text-white text-xl md:text-3xl tracking-widest']) !!}
      @endif
    </nav>
  </div>
</header>
