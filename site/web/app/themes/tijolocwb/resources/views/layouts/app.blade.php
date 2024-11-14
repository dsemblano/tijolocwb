<!doctype html>
<html @php(language_attributes())>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @php(do_action('get_header'))
  @php(wp_head())
  <?php echo \Roots\view('layouts/head/preload')->render(); ?>
  <?php echo \Roots\view('layouts/head/favicon')->render(); ?>
  <?php echo \Roots\view('layouts/head/gconsole')->render(); ?>
  <?php echo \Roots\view('layouts/head/gtaghead')->render(); ?>
  <?php echo \Roots\view('partials/snippets/schema')->render(); ?>
  {{-- <script src="~partytown/partytown.js"></script>

  <script type="text/partytown">
    for (let i = 0; i < 999999; i++) console.log(i);
  </script> --}}
</head>

<body @php(body_class('debug-screens'))>
  @php(wp_body_open())
  <?php echo \Roots\view('partials/snippets/gtagbody')->render(); ?>

  <div id="app">
    <a class="sr-only focus:not-sr-only" href="#main">
      {{ __('Skip to content') }}
    </a>

    @include('sections.header')

    <main id="main" class="main">

      @if (! is_front_page() && ! is_page('reservas') )
      <div class="container prose mx-auto prose-a:no-underline prose-h1:mb-10 prose-h2:mb-0 prose-h3:mb-0">
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
    @include('partials/arrowcdtop')
  </div>

  @php(do_action('get_footer'))
  @php(wp_footer())
</body>

</html>