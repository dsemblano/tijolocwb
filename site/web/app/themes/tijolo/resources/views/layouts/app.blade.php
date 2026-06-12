<!doctype html>
<html @php(language_attributes())>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php(do_action('get_header'))
    @php(wp_head())

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body @php(body_class())>
    @php(wp_body_open())

    <div id="app">
        <a class="sr-only focus:not-sr-only" href="#main">
            {{ __('Skip to content', 'sage') }}
        </a>

        @include('sections.header')

        <main id="main" class="main">
            @if (!is_front_page() && !is_page('reservas'))
                <div
                    class="container prose max-w-none w-full text-tijolop prose-h1:text-tijolovermelho prose-h2:text-tijolovermelho prose-h3:text-tijolovermelho mx-auto prose-a:no-underline prose-h1:mb-10 prose-h2:mb-4 prose-h3:mb-0 prose-blockquote:border-tijolopink prose-blockquote:text-tijolopink prose-figure:mb-4">
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

    @php(do_action('get_footer'))
    @php(wp_footer())
</body>

</html>
