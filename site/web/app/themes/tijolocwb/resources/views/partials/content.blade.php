<article @php(post_class('border-default'))>
  <a href="{{ get_permalink() }}">
    <figure class="imgpost">
      {{ the_post_thumbnail('medium_large', array( 'class' => 'w-full shadow-xl rounded-t-md' ) ) }}
    </figure>
  </a>
  <header>
    <div class="wrap">
      <h2 class="text-xl my-2">
        <a class="postslinks" href="{{ get_permalink() }}">
          {!! $title !!}
        </a>
      </h2>

      {{-- <p class="mb-3 excerpt">
        <a class="postslinks" href="{{ get_permalink() }}">
          {{ get_the_excerpt() }}
        </a>
      </p> --}}
    </div>
  </header>
</article>