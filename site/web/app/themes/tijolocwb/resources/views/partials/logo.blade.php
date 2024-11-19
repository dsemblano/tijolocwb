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