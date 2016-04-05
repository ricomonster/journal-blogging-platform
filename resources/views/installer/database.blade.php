@extends('installer.layout')
@section('title', 'Database Setup')

@section('css')
<style type="text/css">
    #installer_welcome {}
</style>
@endsection

@section('content')
<journal-database-setup inline-template>
    <div id="installer_database">
        <header class="subheader">
            <h3>Database Connection</h3>
        </header>
        <section class="content">
            <p>
                Enter your database connection details below. Make sure that you
                have inputted properly or else it will cause some error.
            </p>

            <br/>

            <form v-on:submit.prevent="saveDatabaseSettings()">
                <div class="form-group">
                    <label class="control-label">Database Name</label>
                    <input type="text" v-model="database.db_database" class="form-control"
                    value="{{$env['db_database']}}"/>
                    <span class="help-block">
                        The name of the database you want to run Journal
                    </span>
                </div>

                <div class="form-group">
                    <label class="control-label">User Name</label>
                    <input type="text" v-model="database.db_username" class="form-control"
                    value="{{$env['db_username']}}"/>
                    <span class="help-block">
                        Your MySQL username
                    </span>
                </div>

                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="text" v-model="database.db_password" class="form-control"
                    value="{{$env['db_password']}}"/>
                    <span class="help-block">
                        Your MySQL password
                    </span>
                </div>

                <div class="form-group">
                    <label class="control-label">Database Host</label>
                    <input type="text" v-model="database.db_host" class="form-control"
                    value="{{$env['db_host']}}"/>
                    <span class="help-block">
                        If <code>localhost</code> or <code>127.0.0.1</code> won't work,
                        check the details from your web host.
                    </span>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </section>
    </div>
</journal-database-setup>
@endsection
