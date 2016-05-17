@extends('casper.layout')
@section('title', blog_title())

@section('body')
    <div class="post-lists">
        @foreach ($posts as $key => $post)
        @include('casper.partials.post')
        @endforeach

        @include('casper.partials.pagination')
    </div>
@endsection
