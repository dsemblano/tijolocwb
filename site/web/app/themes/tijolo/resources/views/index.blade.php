@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  <section id="na-midia">
    <div class="grid grid-cols-1 gap-y-4 gap-x-10 md:grid-cols-2 mt-5 w-full lg:pr-8 mb-6">
      @php
        // O WordPress agora lê perfeitamente o 'paged' graças ao setup.php reativado
        $paged = max(1, get_query_var('paged'));
        $query = null;

        if (is_category('nosso-dia-a-dia')) {
          $category = get_queried_object();
          
          $args = array(
              'cat'            => $category->term_id,
              'posts_per_page' => 10,
              'paged'          => $paged,
          );

          $query = new WP_Query($args);
        }
      @endphp
      
      @if ($query && $query->have_posts())
        @while ($query->have_posts()) @php $query->the_post(); @endphp
            @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        @endwhile
      @else
        {{-- Fallback nativo caso precise --}}
        @while (have_posts()) @php the_post(); @endphp
            @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
        @endwhile
      @endif
    </div>
  </section>

  {{-- Paginação nativa do WordPress renderizando perfeitamente pelos links canônicos --}}
  @php 
    $max_pages = $query ? $query->max_num_pages : $GLOBALS['wp_query']->max_num_pages;
  @endphp

  @if ($max_pages > 1)
    {!! get_the_posts_pagination(array(
      'total'               => $max_pages,
      'current'             => $paged,
      'prev_text'           => '« Anterior',
      'next_text'           => 'Próximo »',
      'screen_reader_text'  => __('Navegação de página'),
    )) !!}
  @endif
  
  @php wp_reset_postdata(); @endphp
  
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection