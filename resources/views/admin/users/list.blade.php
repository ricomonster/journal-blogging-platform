@extends('admin.layout')
@section('title', 'Users')

@section('content')
<journal-users-list inline-template>
    <div id="users_list_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Users</h1>
        </header>
        <section class="users-list scrollable-content">
            <div class="wrapper">
                <article class="user clearfix" v-for="user in users">
                    <a href="#" class="clearfix">
                        <figure class="avatar-wrapper">
                            <img src="https://instagram.fmnl4-3.fna.fbcdn.net/t51.2885-15/s750x750/sh0.08/e35/12501866_1081025575254288_1106918070_n.jpg?ig_cache_key=MTIzMDIzMzA2MzA2Nzk0MzMwMg%3D%3D.2"/>
                        </figure>
                        <section class="user-details">
                            <span class="role label label-default pull-right">
                                @{{ user.role.name }}
                            </span>
                            <h3 class="user-name">@{{ user.name }}</h3>
                            <p>
                                @{{ user.email }}
                            </p>
                        </section>
                    </a>
                </article>
            </div>
        </section>
    </div>
</journal-tags-list>
@endsection
