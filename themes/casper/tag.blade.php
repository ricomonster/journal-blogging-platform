@extends('casper.layout')
@section('title', $tag->title . ' - '. blog_title())

@section('header')
    <header class="main-header tag-head no-cover">
        <nav class="main-nav overlay clearfix">
            @if ($logo_url)
            <a class="blog-logo" href="{{ url('/') }}">
                <img src="{{ $logo_url }}" alt="Blog blog blog">
            </a>
            @endif

            <a class="menu-button" href="#">
                <i class="fa fa-bars"></i>
                <span class="word">Menu</span>
            </a>
        </nav>

        <div class="vertical">
            <div class="main-header-content inner">
                <h1 class="page-title">{{ $tag->title }}</h1>
                <h2 class="page-description">
                        A {{ $posts->count() }}-post collection
                </h2>
            </div>
        </div>
    </header>
@endsection

@section('body')
    <div class="post-lists">
        @foreach ($posts as $key => $post)
        @include('casper.partials.post')
        @endforeach
    </div>
@endsection
