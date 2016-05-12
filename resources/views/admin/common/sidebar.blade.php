<aside id="sidebar">
    <div class="content">
        <header class="sidebar-header">
            <h1 class="blog-title">
                <a href="{{ url('/') }}" target="_blank">
                    {{ blog_title() }}
                </a>
            </h1>
            <p class="user-name">{{ auth_user()->name }}</p>
        </header>
        <section class="sidebar-content">
            <ul class="menu">
                <!-- <li class="header">
                    <span class="title">Content</span>
                </li> -->
                <li class="{!! (is_active_menu('journal/posts')) ? 'active' : null !!}">
                    <a href="{{ url('journal/posts') }}">
                        <i class="fa fa-thumb-tack fa-fw"></i>
                        <span class="text">Posts</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('journal/pages')) ? 'active' : null !!}"
                style="display: none;">
                    <a href="{{ url('journal/pages/list') }}">
                        <i class="fa fa-file fa-fw"></i>
                        <span class="text">Pages</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('journal/tags')) ? 'active' : null !!}">
                    <a href="{{ url('journal/tags') }}">
                        <i class="fa fa-tags fa-fw"></i>
                        <span class="text">Tags</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('journal/users')) ? 'active' : null !!}">
                    <a href="{{ url('journal/users') }}">
                        <i class="fa fa-users fa-fw"></i>
                        <span class="text">Users</span>
                    </a>
                </li>
                <li class="divider"></li>
                <li class="{!! (is_active_menu('journal/settings')) ? 'active' : null !!}">
                    <a href="{{ url('journal/settings') }}">
                        <i class="fa fa-cog fa-fw"></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('journal/navigation')) ? 'active' : null !!}">
                    <a href="{{ url('journal/navigation') }}">
                        <i class="fa fa-location-arrow fa-fw"></i>
                        <span class="text">Navigation</span>
                    </a>
                </li>
                <!-- Owner Priviledge Access -->
                <li style="display: none;">
                    <a href="{{ url('journal/settings/roles') }}">
                        <i class="fa fa-briefcase fa-fw"></i>
                        <span class="text">Roles</span>
                    </a>
                </li>
                <li class="divider"></li>
                <li class="{!! (is_active_menu('journal/users/'.auth_user()->id.'/profile')) ? 'active' : null !!}">
                    <a href="{{ url('journal/users/'.auth_user()->id.'/profile') }}">
                        <i class="fa fa-user fa-fw"></i>
                        <span class="text">Edit Profile</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('journal/users/change-password')) ? 'active' : null !!}">
                    <a href="{{ url('journal/users/change-password') }}">
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span class="text">Change Password</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('journal/logout') }}">
                        <i class="fa fa-power-off fa-fw"></i>
                        <span class="text">Log out</span>
                    </a>
                </li>
            </ul>
        </section>
    </div>
</aside>
