@extends('admin.layout')
@section('title')
Users
@stop

@section('body')
<style type="text/css">
	.user-list-page .page-header { padding: 10px 0 0; }

	.user-list-page .user-details { border-bottom: #f6f6f6 1px solid; padding: 10px 0; }
	.user-list-page .user-details .user-hyperlink { display: block; }
	.user-list-page .user-details .user-hyperlink .user-avatar { float: left; width: 80px; }
	.user-list-page .user-details .user-hyperlink .user-content { float: left; margin-left: 15px; word-wrap: break-word; }
	.user-list-page .user-details .user-hyperlink .user-content .user-name {
		color: rgba(0,0,0,0.8);
		font-size: 20px;
		font-weight: normal;
		line-height: 24px;
		margin: 0;
	}

	.user-list-page .user-details .user-hyperlink .user-content .last-login {
		font-family: 'Noto Serif', serif;
		font-size: 14px;
		font-style: italic;
	}

	.user-list-page .user-details .user-hyperlink .role { float: right; }
</style>
<section class="main-content user-list-page centered">
	<header class="page-header">
		<h1 class="hero-title pull-left">Users</h1>
		<a href="/journal/users/add" class="btn btn-primary pull-right">Add User</a>
		<div class="clearfix"></div>
	</header>
	<section class="content-wrapper">
		@foreach($users as $key => $user)
		<article class="user-details">
			<a href="/journal/users/profile/{{ $user->id }}" class="user-hyperlink clearfix">
				<img src="http://41.media.tumblr.com/d7a4552d99639890d00b5e85d9a18673/tumblr_mldfty8fh41qcnibxo1_1280.png" class="user-avatar img-circle"/>
				<div class="user-content">
					<h3 class="user-name">{{ $user->name }}</h3>
					{{--<p class="text-muted last-login">Last login</p>--}}
				</div>
				@if($user->role == 1)
				<span class="label label-success role">Administrator</span>
				@endif
				@if($user->role == 2)
				<span class="label label-info role">Editor</span>
				@endif
			</a>
		</article>
		@endforeach
	</section>
</section>
@stop
@section('footer.js')
@stop
