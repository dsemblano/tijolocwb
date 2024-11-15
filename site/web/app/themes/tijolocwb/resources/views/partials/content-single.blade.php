<article @php(post_class('single-post'))>
  <header>
    <h1 class="text-5xl text-center mt-8 animate__animated animate__fadeInDown">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  <div class="e-content mt-8">
    @php(the_content())
  </div>

  @if ($pagination)
    <footer>
      <nav class="page-nav" aria-label="Page">
        {!! $pagination !!}
      </nav>
    </footer>
  @endif

  {{-- @php(comments_template()) --}}
</article>
