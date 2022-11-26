<a class="sr-only focus:not-sr-only" href="#main">
  {{ __('Skip to content') }}
</a>

@include('sections.header')
@php $current_page = basename(get_permalink()) @endphp
<main id="main" class="main{{ ! is_front_page() ? " $current_page" : '' }}">
  @if (! is_front_page())
  <div class="container">
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
@if (! is_front_page())
@include('partials/arrowcdtop')
@endif