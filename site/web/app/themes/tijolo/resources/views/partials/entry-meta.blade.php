<time class="dt-published" datetime="{{ get_post_time('c', true) }}">
  Postado em: {{ get_the_date() }}
</time>

<div>
  @php
   $category = get_the_category();

if ( ! empty( $category ) ) {
    $category_slug = get_category_parents($category[0]->term_id, true, '/', true);
    $category_slug = trim($category_slug, '/');
}
  @endphp
<span class="relative top-px">Voltar para:
  {!!$category_slug !!}
</span>
  {{-- <a class="no-underline" href="/{{$category_slug}}/">&larr; <span class="relative top-px">Voltar para Nosso dia a dia</span></a> --}}
</div>

{{-- <p>
  <span>{{ __('By', 'sage') }}</span>
  <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" class="p-author h-card">
    {{ get_the_author() }}
  </a>
</p> --}}
