@extends('installer.layout')
@section('title', 'Setup')

@section('css')
<style type="text/css">
    #installer_setup .content form { padding: 0 20px; }
</style>
@endsection

@section('content')
<journal-installer-setup inline-template>
    <div id="installer_setup">
        <header class="subheader">
            <h3>Blog Setup</h3>
        </header>
        <section class="content">
            <p>
                Please provide the following information. You can change these
                settings later.
            </p>

            <form v-on:submit="saveSetup" autocomplete="off">
                <div class="form-group" v-bind:class="{ 'has-error' : errors.blog_title }">
                    <label class="control-label">Blog Title</label>
                    <input type="text" v-model="setup.blog_title" class="form-control"
                    placeholder="Journal"/>
                    <span class="help-block" v-if="{ 'has-error' : errors.blog_title }">

                    </span>
                </div>

                <div class="form-group" v-bind:class="{ 'has-error' : errors.blog_description }">
                    <label class="control-label">Blog Description</label>
                    <textarea v-model="setup.blog_description" class="form-control"
                    placeholder="Description..."></textarea>
                    <span class="help-block" v-if="{ 'has-error' : errors.blog_description }">

                    </span>
                </div>

                <div class="form-group" v-bind:class="{ 'has-error' : errors.name }">
                    <label class="control-label">Your Name</label>
                    <input type="text" v-model="setup.name" class="form-control"
                    placeholder="John Doe"/>
                    <span class="help-block" v-if="{ 'has-error' : errors.name }">

                    </span>
                </div>

                <div class="form-group" v-bind:class="{ 'has-error' : errors.email }">
                    <label class="control-label">Email</label>
                    <input type="email" v-model="setup.email" class="form-control"
                    placeholder="name@email.com"/>
                    <span class="help-block" v-if="{ 'has-error' : errors.email }">

                    </span>
                </div>

                <div class="form-group" v-bind:class="{ 'has-error' : errors.password }">
                    <label class="control-label">Password</label>
                    <input type="password" v-model="setup.password" class="form-control"
                    placeholder="******"/>
                    <span class="help-block" v-if="{ 'has-error' : errors.password }">

                    </span>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </section>
    </div>
</journal-installer-setup>
@endsection
