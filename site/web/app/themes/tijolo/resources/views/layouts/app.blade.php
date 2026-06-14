<!doctype html>
<html @php(language_attributes())>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preload" fetchpriority="high" as="image" href={{ Vite::asset('resources/images/TijoloEntrada.webp') }} type="image/webp">
    <link rel="preload" href={{ Vite::asset('resources/fonts/Cuprum/Cuprum-VariableFont_wght.ttf') }} as="font" type="font/ttf" crossorigin>
    <link rel="author" type="text/plain" href="{{ Vite::asset('humans.txt') }}" />

    <meta name="apple-mobile-web-app-title" content="Tijolo Restaurante" />
    <link rel="manifest" href="@asset('images/favicon/site.webmanifest')" />
    <link rel="icon" type="image/png" sizes="96x96" href="@asset('images/favicon/favicon-96x96.png')">
    <link rel="apple-touch-icon" sizes="180x180" href="@asset('images/favicon/apple-touch-icon.png')">
    <link rel="shortcut icon" href="@asset('images/favicon/favicon.ico')">
    <link rel="icon" type="image/svg+xml" href="@asset('images/favicon/favicon.svg')">
    @include('partials.schema')

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
                <div class="container ">
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
