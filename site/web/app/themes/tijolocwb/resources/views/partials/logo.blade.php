<a class="brand relative" href="{{ home_url('/') }}">
    @php
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

    @endphp
    <{{$current}}>
      <img id="logoname" class="w-auto h-16 hover:scale-105 transition duration-300 ease-in-out" src="@asset('images/logo/tijolologo.png')" alt="Tijolo logo">

    </{{$current}}>
  </a>