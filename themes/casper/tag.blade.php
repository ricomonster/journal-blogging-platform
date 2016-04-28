@extends('casper.layout')
@section('title', $tag->title . ' - '. blog_title())

@section('header')
    <header class="main-header tag-head {!! ($tag->cover_image) ? null : 'no-cover' !!}"
    {!! ($tag->cover_image) ? 'style="background-image: url('.$tag->cover_image.');"' : null !!}>
        <nav class="main-nav overlay clearfix">
            {!! blog_logo_photo() !!}

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
