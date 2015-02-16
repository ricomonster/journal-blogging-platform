@extends('installer.layout')
@section('title')
    Setup your blog
@stop
@section('body')
    <section class="blog-page installer-page">
        <div class="content">
            <h1 class="hero-title">Journal</h1>
            <form method="post" action="/installer/setup_blog" autocomplete="off">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <input type="text" name="blog_title" class="form-control"
                    placeholder="Blog title"/>
                </div>
                <div class="form-group">
                    <textarea name="blog_description" class="form-control"
                    placeholder="Blog description"></textarea>
                </div>
                <div class="form-group">
                    <select name="theme" class="form-control">
                        <option value="" selected>-- Select your theme --</option>
                        @foreach($themes as $key => $theme)
                        <option value="{{ $key }}">{{ $theme }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    Let's go!
                </button>
            </form>
        </div>
    </section>
@stop
