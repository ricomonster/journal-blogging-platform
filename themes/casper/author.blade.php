@extends('casper.layout')
@section('title', $author->name . ' - '. blog_title())

@section('header')
    <header class="main-header author-head {!! ($author->cover_url) ? null : 'no-cover' !!}"
    {!! ($author->cover_url) ? 'style="background-image: url('.$author->cover_url.');"' : null !!}>
        <nav class="main-nav overlay clearfix">
            {!! blog_logo_photo() !!}

            <a class="menu-button" href="#">
                <i class="fa fa-bars"></i>
                <span class="word">Menu</span>
            </a>
        </nav>
    </header>

    <section class="author-profile inner">
        @if ($author->avatar_url)
        <figure class="author-image">
            <div class="img" style="background-image: url({{ $author->avatar_url }})">
                <span class="hidden">{{ $author->name }}'s Picture</span>
            </div>
        </figure>
        @endif

        <h1 class="author-title">{{ $author->name }}</h1>
        <div class="author-meta">
            @if ($author->location)
            <span class="author-location">
                <i class="fa fa-map-marker"></i>
                {{ $author->location }}
            </span>
            @endif

            @if ($author->website)
            <span class="author-link">
                <a href="{{ $author->website }}">
                    <i class="fa fa-link"></i>
                    {{ $author->website }}
                </a>
            </span>
            @endif

            <span class="author-stats">
                <i class="fa fa-bar-chart"></i> {{ $posts->total() }} post
            </span>
        </div>
    </section>
@endsection

@section('body')
    <div class="post-lists">
        @foreach ($posts as $key => $post)
        @include('casper.partials.post')
        @endforeach

        @include('casper.partials.pagination')
    </div>
@endsection
