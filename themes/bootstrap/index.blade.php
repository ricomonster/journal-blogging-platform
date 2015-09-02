@extends('bootstrap.default')
@section('content')
    <div class="blog-masthead" style="display: none;">
        <div class="container">
            <nav class="blog-nav">
                <a class="blog-nav-item active" href="#">Home</a>
                <a class="blog-nav-item" href="#">New features</a>
                <a class="blog-nav-item" href="#">Press</a>
                <a class="blog-nav-item" href="#">New hires</a>
                <a class="blog-nav-item" href="#">About</a>
            </nav>
        </div>
    </div>

    <div class="container">
        <header class="blog-header">
            <h1 class="blog-title">{{blog_title()}}</h1>
            <p class="lead blog-description">{{blog_description()}}</p>
        </header>


        <div class="main">
            @foreach($posts as $post)
            <article class="post">
                <header class="post-header">
                    <h2 class="post-title">{{$post->title}}</h2>
                    <div class="post-meta">
                        <span class="author">By <a href="#">{{$post->author->name}}</a></span>
                        on [Tags]
                    </div>
                </header>
                <section class="content">
                    {!! markdown($post->markdown, true, 50) !!}
                </section>
            </article>
            @endforeach

            {{--<nav>--}}
                {{--<ul class="pager">--}}
                    {{--<li><a href="#">Previous</a></li>--}}
                    {{--<li><a href="#">Next</a></li>--}}
                {{--</ul>--}}
            {{--</nav>--}}
        </div>
    </div><!-- /.container -->
@stop
