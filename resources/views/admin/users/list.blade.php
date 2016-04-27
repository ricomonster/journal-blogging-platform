@extends('admin.layout')
@section('title', 'Users')

@section('content')
<journal-users-list inline-template>
    <div id="users_list_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Users</h1>
        </header>
        <section class="users-list scrollable-content">
            <div class="wrapper centered-content">
                <article class="user clearfix" v-for="user in users">
                    <a href="/journal/users/@{{ user.id }}/profile" class="clearfix">
                        <figure class="avatar-wrapper">
                            <img v-if="!user.avatar_url" v-bind:src="defaultAvatar"
                            class="avatar-photo"/>
                            <img v-if="user.avatar_url" v-bind:src="user.avatar_url"
                            class="avatar-photo"/>
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
