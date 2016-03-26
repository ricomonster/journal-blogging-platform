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
