<aside id="sidebar">
    <div class="content">
        <header class="sidebar-header">
            <h1 class="blog-title">
                <a href="/" target="_blank">{{blog_title()}}</a>
            </h1>
            <p class="user-name">{{auth_user()->name}}</p>
        </header>
        <section class="sidebar-content">
            <ul class="menu">
                <!-- <li class="header">
                    <span class="title">Content</span>
                </li> -->
                <li class="{!! (is_active_menu('posts/list')) ? 'active' : null !!}">
                    <a href="{{ url('journal/posts/list') }}">
                        <i class="fa fa-list fa-fw"></i>
                        <span class="text">Posts</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('pages/list')) ? 'active' : null !!}"
                style="display: none;">
                    <a href="{{ url('journal/pages/list') }}">
                        <i class="fa fa-file fa-fw"></i>
                        <span class="text">Pages</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('tags/list')) ? 'active' : null !!}"
                style="display: none;">
                    <a href="{{ url('journal/tags/list') }}">
                        <i class="fa fa-tags fa-fw"></i>
                        <span class="text">Tags</span>
                    </a>
                </li>
                <li class="{!! (is_active_menu('users/list')) ? 'active' : null !!}">
                    <a href="{{ url('journal/users/list') }}">
                        <i class="fa fa-users fa-fw"></i>
                        <span class="text">Users</span>
                    </a>
                </li>
                <li class="divider"></li>
                <li class="{!! (is_active_menu('settings')) ? 'active' : null !!}">
                    <a href="{{ url('journal/settings') }}">
                        <i class="fa fa-cog fa-fw"></i>
                        <span class="text">Settings</span>
                    </a>
                </li>
                <!-- Owner Priviledge Access -->
                <li>
                    <a href="{{ url('journal/settings/roles') }}">
                        <i class="fa fa-briefcase fa-fw"></i>
                        <span class="text">Roles</span>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="{{ url('journal/users/'.auth_user()->id) }}">
                        <i class="fa fa-user fa-fw"></i>
                        <span class="text">
                            Edit Profile
                        </span>
                    </a>
                </li>
                <li>
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
