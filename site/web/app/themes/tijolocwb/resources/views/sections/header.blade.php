<header id="header" class="banner w-full z-50 bg-tijolo sticky top-0 left-0 shadow">
  <nav class="nav-primary container py-2 nav-primary">
    <div class="flex flex-wrap lg:flex-nowrap justify-between items-center mx-auto">
      <a class="brand relative" href="{{ home_url('/') }}">
        @php
        $current = (! is_front_page() ? "div" : 'h1');
        
        $current_page = (! is_category() ? get_the_title() : single_cat_title('', false) );
        // $current_page = !is_category() 
        // ? htmlspecialchars_decode(get_the_title(), ENT_QUOTES) 
        // : htmlspecialchars_decode(single_cat_title('', false), ENT_QUOTES);
        @endphp
        <{{$current}} id="logo"
          class="text-white logohome relative bottom-4 hover:scale-105 transition duration-300 ease-in-out">
          {{-- <span id="logoname" class="text-8xl relative">Tijolo</span> --}}
          <span id="logoname">Tijolo</span>
          <span id="logosurname" class="text-lg absolute top-[85%] right-0">comidas e vinhos</span>

          @if (! is_front_page())
          <span id="logosurnamepage" class="text-lg absolute top-[80%] left-0 hidden"><?php echo esc_html($current_page); ?></span>
          @endif
        </{{$current}}>
      </a>
      <div id="mobile-menu" class="flex lg:order-2 ml-8">

        <button aria-label="Botão menu sanduíche" data-collapse-toggle="mobile-menu-3" type="button"
          class="inline-flex items-center p-2 text-sm text-white rounded-lg lg:hidden" aria-controls="mobile-menu-3"
          aria-expanded="false">
          <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
          <svg class="hidden w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
      <div class="hidden justify-between items-center w-full lg:flex lg:order-1" id="mobile-menu-3">
        {{-- <div class="relative mt-3 lg:hidden">
          @include('partials/inputsearch')
        </div> --}}
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'flex flex-col pl-4 py-2
        tracking-widest w-full justify-evenly lg:flex-row lg:mt-0 nav text-white text-lg relative', 'echo' => false]) !!}
      </div>
    </div>
  </nav>
</header>