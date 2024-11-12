@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning">
      {!! __('Sorry, no results were found.', 'sage') !!}
    </x-alert>

    {!! get_search_form(false) !!}
  @endif

  <section class="gap-4 grid grid-cols-1 md:grid-cols-2 mt-5 w-full lg:pr-8 mb-6">
    @while(have_posts()) @php(the_post())
    @includeFirst(['partials.content-' . get_post_type(), 'partials.content'])
    @endwhile
  </section>

  {!! get_the_posts_navigation() !!}
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection
