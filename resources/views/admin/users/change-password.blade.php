@extends('admin.layout')
@section('title', 'Change Password')

@section('content')
<journal-users-change-password inline-template>
    <div id="users_change_password">
        <header class="page-header clearfix">
            <h1 class="page-title">Change Password</h1>
        </header>
        <section class="user-change-password scrollable-content">
            <div class="form-wrapper form-horizontal centered-content">
                <div class="form-group">
                    <label class="control-label col-sm-3">Current password</label>
                    <div class="col-sm-9">
                        <input type="password" v-model="password.current_password"
                        class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">New password</label>
                    <div class="col-sm-9">
                        <input type="password" v-model="password.new_password"
                        class="form-control"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Repeat new password</label>
                    <div class="col-sm-9">
                        <input type="password" v-model="password.repeat_new_password"
                        class="form-control"/>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-primary" v-on:click="submitPasswords"
                    v-button-loader="processing">
                        Update
                    </button>
                </div>
            </div>
        </section>
    </div>
</journal-users-change-password>
@endsection
