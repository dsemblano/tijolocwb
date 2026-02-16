<!doctype html>
<html @php(language_attributes())>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php(do_action('get_header'))
  @php(wp_head())
  <?php echo \Roots\view('layouts/head/preload')->render(); ?>
  <link rel="author" type="text/plain" href="{{ asset('humans.txt') }}" />
  <meta name="apple-mobile-web-app-title" content="Tijolo Restaurante" />
  <link rel="manifest" href="@asset('images/favicon/site.webmanifest')" />
  <link rel="icon" type="image/png" sizes="96x96" href="@asset('images/favicon/favicon-96x96.png')">
  <link rel="apple-touch-icon" sizes="180x180" href="@asset('images/favicon/apple-touch-icon.png')">
  <link rel="shortcut icon" href="@asset('images/favicon/favicon.ico')">
  <link rel="icon" type="image/svg+xml" href="@asset('images/favicon/favicon.svg')">
  <?php echo \Roots\view('layouts/head/gconsole')->render(); ?>
  <?php echo \Roots\view('layouts/head/gtaghead')->render(); ?>
  <?php echo \Roots\view('partials/snippets/schema')->render(); ?>
  <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

</head>

<body @php(body_class())>
  @php(wp_body_open())
  <?php echo \Roots\view('partials/snippets/gtagbody')->render(); ?>

  <div id="app">
    <script>
  window.addEventListener('DOMContentLoaded', (event) => {
    confetti({
      particleCount: 150,
      spread: 70,
      origin: { y: 0.6 },
      colors: ['#BF5545', '#6EBAA8', '#F0E8D1'] // Optional: Use your brand colors!
    });
  });
</script>
    <a class="sr-only focus:not-sr-only" href="#main">
      {{ __('Skip to content') }}
    </a>

    @include('sections.header')

    <main id="main" class="main">

      @if (! is_front_page() && ! is_page('reservas') )
      <div class="container prose text-tijolop prose-h1:text-tijolovermelho prose-h2:text-tijolovermelho prose-h3:text-tijolovermelho mx-auto prose-a:no-underline prose-h1:mb-10 prose-h2:mb-4 prose-h3:mb-0 prose-blockquote:border-tijolopink prose-blockquote:text-tijolopink prose-figure:mb-4">
        @yield('content')
      </div>
      @else
      @yield('content')
      @endif
    </main>

    @hasSection('sidebar')
    <aside class="sidebar">
      @yield('sidebar')
    </aside>
    @endif

    @include('sections.footer')
  </div>
  @include('partials/arrowcdtop')

  @php(do_action('get_footer'))
  @php(wp_footer())

</body>

</html>
