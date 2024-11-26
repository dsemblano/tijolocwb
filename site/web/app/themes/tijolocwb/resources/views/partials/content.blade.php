<article @php(post_class('nossodiaadia relative'))>
  <a class="img-link lg:w-1/3" href="{{ get_permalink() }}">
    <figure class="imgpost rounded overflow-hidden h-60">
      {{ the_post_thumbnail('medium_large', array( 'class' => 'w-full rounded' ) ) }}
    </figure>
  </a>
  <header class="nossodia-header relative bottom-20 w-10/12 m-auto p-4 rounded-t-md bg-white">
      <h2 class="text-center mt-0 text-lg md:text-2xl">
        <a class="postslinks" href="{{ get_permalink() }}">
          {!! $title !!}
        </a>
      </h2>

      {{-- <p class="mb-3 excerpt">
        <a class="postslinks" href="{{ get_permalink() }}">
          {{ get_the_excerpt() }}
        </a>
      </p> --}}
  </header>
</article>