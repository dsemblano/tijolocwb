{{--
  Template Name: Home Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    {{-- @include('partials.page-header') --}}
    @include('partials/home.home1')
    @include('partials/home.bar')
    {{-- @include('partials/home.home2') --}}    
    @include('partials.content-page')
    @include('partials/home.home3')
  @endwhile
@endsection
