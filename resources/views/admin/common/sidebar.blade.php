<style type="text/css">
#sidebar {}
#sidebar .content {
    background-color: #f6f7f8;
    border-right: 1px solid #dddddd;
    bottom: 0;
    left: 0;
    position: absolute;
    top: 0;
    width: 220px;
}

#sidebar .content .sidebar-header { border-bottom: 1px solid #dddddd; padding: 15px 10px 5px; }
#sidebar .content .sidebar-header .blog-title {
    margin: 0 0 5px;
    font-size: 24px;
    font-weight: bold;
}

#sidebar .content .sidebar-header .blog-title a { color: #231f20; text-decoration: none; }

#sidebar .content .sidebar-header .user-name {}

#sidebar .content .sidebar-content {
    padding: 10px 0;
    position: absolute;
    top: 82px;
    bottom: 0;
    right: 0;
    left: 0;
    overflow-y: auto
}

#sidebar .content .sidebar-content .menu-lists { margin: 0; padding: 0 10px; width: 100%; }
#sidebar .content .sidebar-content .menu-lists li { padding: 3px 0; }
#sidebar .content .sidebar-content .menu-lists li a { color: #4d4a4c; padding: 0 10px; text-decoration: none; }
#sidebar .content .sidebar-content .menu-lists li a i { margin-right: 5px; }

#sidebar .content .sidebar-content .menu-lists li.active a { color: #3187e1; }


#sidebar .content .sidebar-content .menu-lists li.header {
    color: #8c8b8c;
    font-size: 13px;
    font-weight: bold;
    margin: 15px 0 0;
    text-transform: uppercase;
}

#sidebar .content .sidebar-content .menu-lists li.header:first-child { margin: 0; }
</style>
<aside id="sidebar">
    <div class="content">
        <header class="sidebar-header">
            <h1 class="blog-title">
                <a href="/" target="_blank">{{blog_title()}}</a>
            </h1>
            <p class="user-name">{{auth_user()->name}}</p>
        </header>
        <section class="sidebar-content">
            <ul class="menu-lists">
                <li class="header">Create new...</li>
                <li class="">
                    <a href="/journal/editor">
                        <i class="fa fa-plus-circle fa-fw"></i> Post
                    </a>
                </li>
                <li style="display: none;">
                    <a>
                        <i class="fa fa-user-plus fa-fw"></i> User
                    </a>
                </li>
                <li>
                    <a href="/journal/tags/create">
                        <i class="fa fa-tag fa-fw"></i> Tag
                    </a>
                </li>
                <li class="header">
                    Menu
                </li>
                <li class="">
                    <a href="/journal/posts/list">
                        <i class="fa fa-list fa-fw"></i> Posts
                    </a>
                </li>
                <li class="">
                    <a href="/journal/tags/list">
                        <i class="fa fa-tags fa-fw"></i> Tags
                    </a>
                </li>
                <li class="">
                    <a href="/journal/users/list">
                        <i class="fa fa-users fa-fw"></i> Users
                    </a>
                </li>
                <li class="header">Settings</li>
                <li class="">
                    <a href="/journal/settings/general">
                        <i class="fa fa-cog fa-fw"></i> General
                    </a>
                </li>
                <li style="display: none;">
                    <a>
                        <i class="fa fa-rocket fa-fw"></i> Services
                    </a>
                </li>
                <li class="header">Account</li>
                <li class="">
                    <a href="/journal/user/profile">
                        <i class="fa fa-user fa-fw"></i> Edit Profile
                    </a>
                </li>
                <li style="display: none;">
                    <a>
                        <i class="fa fa-unlock fa-fw"></i> Change Password
                    </a>
                </li>
                <li>
                    <a href="/journal/logout">
                        <i class="fa fa-power-off fa-fw"></i> Log out
                    </a>
                </li>
            </ul>
        </section>
    </div>
</aside>
