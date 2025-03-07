<a class="brand relative" href="{{ home_url('/') }}">
    @php
    // $current = (! is_front_page() ||! is_woocommerce() || ! is_shop() ? "div" : 'h1');
    $current = (! is_front_page() ? "div" : 'h1');

    if (is_category()) {
      $current_page = single_cat_title('', false);
    // } elseif (is_page() || is_woocommerce() || is_shop()) {
    } elseif (is_page()) {
      $current_page = get_the_title();
    } elseif (is_single()) {
      $category = get_the_category();
        if ( ! empty( $category ) ) {
          $current_page = $category[0]->name;
        }
    }

    // $current_page = (! is_category() ? get_the_title() : single_cat_title('', false) );
    
    // $current_page = (! is_category() || ! is_page() ? get_the_category(get_the_ID())[0]->name : get_the_title('', false) );
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