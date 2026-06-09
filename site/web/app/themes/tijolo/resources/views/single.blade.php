@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
  <section id="diaadia" class="prose mx-auto">
    @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
  </section>
  @endwhile
@endsection
