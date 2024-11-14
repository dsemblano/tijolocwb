<article @php(post_class('nossodiaadia'))>
  <a class="img-link" href="{{ get_permalink() }}">
    <figure class="imgpost shadow-xl rounded">
      {{ the_post_thumbnail('medium_large', array( 'class' => 'w-full shadow-xl rounded' ) ) }}
    </figure>
  </a>
  <header>
      <h2 class="text-center">
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