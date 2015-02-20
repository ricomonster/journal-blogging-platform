<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') &lsaquo; [Site Title] &#8212; Journal</title>
    <link href="{{ asset('vendor/stylesheets/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/stylesheets/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/stylesheets/bootstrap-notify.css') }}" rel="stylesheet">
    <link href="{{ asset('stylesheets/screen.css') }}" rel="stylesheet">
    <style type="text/css">
        /* Notifications plugin */
        .notifications {
            position: fixed;
            width: 300px;
            z-index: 1000;
        }

        .modal-open .notifications { z-index: 15000; }

        .notifications.top-right { right: 10px; top: 55px; }
        .notifications > div { margin: 5px 0; position: relative; z-index: 9999; }

        .notifications > .alert { color: rgba(255,255,255,0.9); }

        .notifications > .alert.alert-danger { background: #e25440; border-color: #e25440; }
        .notifications > .alert.alert-success { background: #9fbb58; border-color: #9fbb58;}
        .notifications > .alert-alert-warning { background: #f2a925; border-color: #f2a925; }

        .notifications > .alert > .close { color: #ffffff; opacity: 1; }

        /* Responsive Layouts */
        @media only screen and (max-width: 768px) {
            h1 { font-size: 46px; }
            h2 { font-size: 38px; }
            h3 { font-size: 30px; }

            .content-wrapper.centralized { width: 80%; }

            .notifications { right: 0; left: 0; width: 100%; z-index: 10000 }
            .notifications.top-right { top: 0px; }
            .notifications > div { margin: 0; }

            .sidebar .scrollable-content .menu-lists li.blog-page { display: block; }
            .sidebar .scrollable-content .user-menu { display: block; }

            .collapse.navbar-collapse { display: none !important; }
            .admin-header .page-header-title { display: block; }
            .admin-header .navbar-header { display: block; margin: 0; width: 100%; }
            .admin-header .navbar-header .navbar-toggle { display: block; float: left; }
            .admin-header .navbar-header .navbar-brand.trigger-sidebar { display: none; }
        }

        @media only screen and (max-width: 480px) {
            .content-wrapper.centralized { width: 100%; }
        }
    </style>
    @yield('css')
</head>
<body>
    <nav class="sidebar">
    	<div class="scrollable-content">
    		<ul class="menu-lists">
                <li class="blog-page"><a href="/">Site Title</a></li>
    			<li><a href="/journal"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
    			<li class="clearfix">
                    <a href="/journal/posts" class="pull-left">
                        <i class="fa fa-edit fa-fw"></i> Posts
                    </a>
                    <a href="/journal/editor" class="pull-right">
                        <i class="fa fa-plus"></i>
                    </a>
                </li>
    			<li class="clearfix">
                    <a href="/journal/media" class="pull-left">
                        <i class="fa fa-camera-retro fa-fw"></i> Media
                    </a>
                    <a href="/journal/media/add" class="pull-right">
                        <i class="fa fa-plus"></i>
                    </a>
                </li>
    			<li class="clearfix">
                    <a href="/journal/users" class="pull-left">
                        <i class="fa fa-user fa-fw"></i> Users
                    </a>

                    <a href="/journal/users/add" class="pull-right">
                        <i class="fa fa-plus"></i>
                    </a>

                </li>
    			<li class="clearfix">
                    <a href="/journal/settings">
                        <i class="fa fa-gears fa-fw"></i> Setting
                    </a>
                </li>
    			<li>
                    <a href="/journal/settings/appearance">
                        <i class="fa fa-picture-o fa-fw"></i> Appearance
                    </a>
                </li>
    			<li>
                    <a href="/journal/settings/services">
                        <i class="fa fa-laptop fa-fw"></i> Services
                    </a>
                </li>
    		</ul>

            <div class="user-menu">
                <div class="dropdown dropup">
                    <a href="#" class="dropdown-toggle user-details"
                    data-toggle="dropdown" aria-expanded="true">
                        <p class="user-name">Name</p>
                        <small class="menu-label">Profile & Settings</small>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="/journal/users/profile">Edit Profile</a></li>
                        <li><a href="/journal/logout">Log Out</a></li>
                    </ul>
                </div>
            </div>
    	</div>
    </nav>
    <div class="sidebar-overlay"></div>
	<main class="container-fluid main-container">
		<header class="navbar navbar-default navbar-fixed-top admin-header"
		role="navigation">
		    <div id="bar"></div>
			<div class="container-fluid">
				<div class="navbar-header">
                    <button type="button" class="navbar-toggle trigger-sidebar">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

					<a class="navbar-brand trigger-sidebar" href="#">Journal</a>
                    <p class="page-header-title pull-right" href="#"></p>
				</div>
				<nav class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="/">{{ site_title() }}</a></li>
                        <li class="site-directory {{ header_menu('posts') }}">
                            <a href="/journal/posts">Posts</a>
                        </li>
                        <li class="site-directory {{ header_menu('media') }}">
                            <a href="/journal/media">Media</a>
                        </li>
                        <li class="site-directory {{ header_menu('users') }}">
                            <a href="/journal/users">Users</a>
                        </li>
                        <li class="site-directory {{ header_menu('settings') }}">
                            <a href="/journal/settings">Settings</a>
                        </li>
                        <li class="site-directory {{ header_menu('appearance') }}">
                            <a href="/journal/appearance">Appearance</a>
                        </li>
                        <li class="site-directory {{ header_menu('services') }}">
                            <a href="/journal/services">Services</a>
                        </li>
					</ul>
					<ul class="nav navbar-nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle"
							data-toggle="dropdown">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu dropdown-menu-right" role="menu">
								<li><a href="/journal/users/profile">Edit Profile</a></li>
								<li><a href="/journal/logout">Log Out</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</header>

		@yield('body')

		<footer></footer>
	</main>

	@yield('modals')

    <aside class="notifications top-right"></aside>
    <!--  End of Content  -->

    <script src="{{ asset('vendor/javascript/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/javascript/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/javascript/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('javascript/journal.min.js') }}"></script>
    <script>
        //NProgress.configure({ showSpinner: false });

//        $(document).ready(function() {
//            $({property: 0}).animate({property: 105}, {
//                duration: 4000,
//                step: function() {
//                    NProgress.start();
//                },
//                complete: function() {
//                    NProgress.done(true);
//                }
//            });
//        });

    	$('.trigger-sidebar').on('click', function(e) {
            // disable temporarily the sidebar
            return;
    		e.preventDefault();
    		// check if the body is set to toggle the sidebar
    		if($('body').hasClass('transition-sidebar')) {
    			$('body').removeClass('transition-sidebar');
    			return;
    		}

    		$('body').addClass('transition-sidebar');
    		return;
    	});

        $(document).on('click', '.main-content', function() {
            if($('body').hasClass('transition-sidebar')) {
                $('body').removeClass('transition-sidebar');
            }
        });
    </script>
    @yield('footer.js')
</body>
</html>

