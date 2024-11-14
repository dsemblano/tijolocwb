@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  {{-- <section class="gap-4 grid grid-cols-1 md:grid-cols-2 mt-5 w-full lg:pr-8 mb-6"> --}}
    <section class="flex flex-col">
    {{-- @php
      $excluded_category_slug = 'na-midia'; // Defina o slug da categoria a ser excluída
      $excluded_category_id = get_category_by_slug($excluded_category_slug)->term_id;
    @endphp --}}

    @php
if (is_category()) {
          $category = get_queried_object();

          // Verifica se a categoria atual é pai ou filha
          if ($category->category_parent == 0) {
              // Se for uma categoria pai, mostramos apenas os posts da categoria pai
              $args = array(
                  'category__in' => array($category->term_id),
                  'category__not_in' => get_term_children($category->term_id, 'category'),
                  'posts_per_page' => 10,
              );
          } else {
              // Se for uma subcategoria, mostramos apenas os posts da subcategoria
              $args = array(
                  'category__in' => array($category->term_id),
                  'posts_per_page' => 10,
              );
          }

          $query = new WP_Query($args);
        }
    @endphp
    
    @if ($query->have_posts())
      @while ($query->have_posts())
          <?php $query->the_post(); ?>
          @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
      @endwhile
    <?php wp_reset_postdata(); ?>
    @else
      @while (have_posts())
          <?php the_post(); ?>
          @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
      @endwhile
    @endif
  </section>

  {!! get_the_posts_navigation() !!}
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection
