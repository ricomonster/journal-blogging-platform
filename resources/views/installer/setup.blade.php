@extends('installer.layout')
@section('title', 'Setup')

@section('css')
<style type="text/css">
    #installer_setup .content form { padding: 0 20px; }
</style>
@endsection

@section('content')
<div id="installer_setup">
    <header class="subheader">
        <h3>Blog Setup</h3>
    </header>
    <section class="content">
        <p>
            Please provide the following information. You can change these
            settings later.
        </p>

        <form v-on:submit="saveSetup">
            <div class="form-group" v-bind:class="{ 'has-error' : errors.blog_title }">
                <label class="control-label">Blog Title</label>
                <input type="text" v-model="setup.blog_title" class="form-control"
                placeholder="Journal"/>
                <span class="help-block" v-if="{ 'has-error' : errors.blog_title }">
                    @{{errors.blog_title[0]}}
                </span>
            </div>

            <div class="form-group" v-bind:class="{ 'has-error' : errors.blog_description }">
                <label class="control-label">Blog Description</label>
                <textarea v-model="setup.blog_description" class="form-control"
                placeholder="Description..."></textarea>
                <span class="help-block" v-if="{ 'has-error' : errors.blog_description }">
                    @{{errors.blog_description[0]}}
                </span>
            </div>

            <div class="form-group" v-bind:class="{ 'has-error' : errors.name }">
                <label class="control-label">Your Name</label>
                <input type="text" v-model="setup.name" class="form-control"
                placeholder="John Doe"/>
                <span class="help-block" v-if="{ 'has-error' : errors.name }">
                    @{{errors.name[0]}}
                </span>
            </div>

            <div class="form-group" v-bind:class="{ 'has-error' : errors.email }">
                <label class="control-label">Email</label>
                <input type="email" v-model="setup.email" class="form-control"
                placeholder="name@email.com"/>
                <span class="help-block" v-if="{ 'has-error' : errors.email }">
                    @{{errors.email[0]}}
                </span>
            </div>

            <div class="form-group" v-bind:class="{ 'has-error' : errors.password }">
                <label class="control-label">Password</label>
                <input type="password" v-model="setup.password" class="form-control"
                placeholder="******"/>
                <span class="help-block" v-if="{ 'has-error' : errors.password }">
                    @{{errors.password[0]}}
                </span>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </section>
</div>
@endsection

@section('footer.js')
<script type="text/javascript">
    var app = new Vue({
        // element to mount to
        el : '#installer_setup',

        // initial data
        data : {
            errors : {},
            setup : {}
        },

        // methods
        methods : {
            saveSetup : function (e) {
                e.preventDefault();

                var data = this.setup;

                // perform ajax request so we can save the data
                this.$http.post('/api/installer/save_setup', data)
                    .then(function(response) {
                        if (response.data.user) {
                            // redirect
                            window.location.href = '/installer/success';
                        }
                    }, function (response) {
                        this.errors = response.data.errors;
                    }.bind(this));
            }
        }
    })
</script>
@endsection
