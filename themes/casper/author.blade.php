@extends('casper.layout')
@section('title', $author->name . ' - '. blog_title())

@section('header')
    <header class="main-header author-head no-cover">
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
    </header>

    <section class="author-profile inner">
        <h1 class="author-title">{{ $author->name }}</h1>
        <div class="author-meta">
            <span class="author-stats">
                <i class="fa fa-bar-chart"></i> {{ $posts->count() }} post
            </span>
        </div>
    </section>
@endsection

@section('body')
    <div class="post-lists">
        @foreach ($posts as $key => $post)
        @include('casper.partials.post')
        @endforeach
    </div>
@endsection
