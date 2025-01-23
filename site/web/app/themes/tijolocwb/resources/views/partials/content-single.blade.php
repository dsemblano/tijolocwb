<article @php(post_class('single-post'))>
  <header class="relative">
    
    <figure class="featured-singleimg w-full overflow-hidden h-96">
      {{ the_post_thumbnail('', array( 'class' => 'w-full shadow-xl h-auto lg:h-thumb mb-6') ) }}
    </figure>

    <h1 class="text-4xl md:text-5xl text-center mt-8 animate__animated animate__fadeInDown relative bottom-20 bg-white w-10/12 m-auto p-6 rounded-t-md">
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
