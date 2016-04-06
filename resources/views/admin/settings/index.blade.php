@extends('admin.layout')
@section('title', 'Settings')

@section('content')
<journal-settings inline-template>
    <div id="settings_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Settings</h1>
        </header>
        <section class="settings scrollable-content">
            <form class="form-wrapper form-horizontal" v-on:submit.prevent="saveSettings()">
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog title</label>
                    <div class="col-sm-9">
                        <input type="text" v-model="settings.blog_title" class="form-control"/>
                        <span class="help-block">The name of your blog.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog description</label>
                    <div class="col-sm-9">
                        <textarea v-model="settings.blog_description" class="form-control"></textarea>
                        <span class="help-block">Describe what your blog is all about.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog logo</label>
                    <div class="image-wrapper col-sm-9">
                        <a class="open-modal" v-on:click="openImageUploaderModal('logo_url')"
                        v-if="settings.logo_url">
                            <img v-bind:src="settings.logo_url"/>
                        </a>
                        <a class="btn btn-info" v-on:click="openImageUploaderModal('logo_url')"
                        v-if="!settings.logo_url">
                            <i class="fa fa-camera"></i> Upload a photo.
                        </a>

                        <span class="help-block">Upload a logo for your blog.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog cover photo</label>
                    <div class="image-wrapper col-sm-9">
                        <a class="open-modal"  v-on:click="openImageUploaderModal('cover_url')"
                        v-if="settings.cover_url">
                            <img v-bind:src="settings.cover_url"/>
                        </a>
                        <a class="btn btn-info" v-on:click="openImageUploaderModal('cover_url')"
                        v-if="!settings.cover_url">
                            <i class="fa fa-camera"></i> Upload a photo.
                        </a>

                        <span class="help-block">Upload a cover photo for your blog.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Post per page</label>
                    <div class="col-sm-9">
                        <input type="number" v-model="settings.post_per_page" class="form-control"/>
                        <span class="help-block">Number of posts that a page will show.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Theme</label>
                    <div class="col-sm-9">
                        <select class="form-control">
                            <option value="" selected>Select theme...</option>
                        </select>
                        <span class="help-block">The theme that will be rendered on your blog.</span>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </section>
    </div>

    <aside id="upload_image_modal" class="modal fade" tabindex="-1" role="dialog"
    v-if="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <image-uploader :image.sync="modal.image" :type="modal.type"></image-uploader>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="saveImageSettings">
                        Save
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </aside>
</journal-settings>

@include('admin.scripts.image-uploader')
@endsection
