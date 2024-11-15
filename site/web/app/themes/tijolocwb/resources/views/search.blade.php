@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  @while(have_posts()) @php(the_post())
    @include('partials.content-search')
  @endwhile

  {{-- {!! get_the_posts_navigation() !!} --}}
  @if ($query->max_num_pages > 1)
  {!! get_the_posts_pagination(array(
    'total' => $query->max_num_pages,
    'current' => $paged,
    'prev_text' => '« Anterior',
    'next_text' => 'Próximo »',
    'screen_reader_text' => __('Navegação de página'),
    )) !!}
@endif
@endsection
