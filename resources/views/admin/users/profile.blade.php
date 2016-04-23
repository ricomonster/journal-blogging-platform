@extends('admin.layout')
@section('title', 'Profile')

@section('content')
<journal-user-profile inline-template>
    <div id="user_profile_page">
        <input type="hidden" name="user_id" value="{{$id}}"/>
        <header class="page-header clearfix">
            <h1 class="page-title">@{{ user.name }}</h1>
        </header>
        <section class="user-profile scrollable-content">
            <header class="user-header">
                <img v-if="!user.cover_url" v-bind:src="images.cover" class="cover-photo"/>
                <img v-if="user.cover_url" v-bind:src="user.cover_url" class="cover-photo"/>

                <div class="user-avatar-details">
                    <figure class="avatar">
                        <a href="#" class="update-avatar" v-on:click="openImageUploaderModal('avatar_url')">
                            <span class="overlay">
                                <i class="fa fa-camera"></i> Update Avatar Photo
                            </span>
                        </a>

                        <img v-if="!user.avatar_url" v-bind:src="images.avatar"
                        class="avatar-photo"/>
                        <img v-if="user.avatar_url" v-bind:src="user.avatar_url"
                        class="avatar-photo"/>
                    </figure>
                    <div id="name" class="form-group">
                        <input type="text" class="form-control" v-model="user.name"/>
                        <span class="help-block">
                            Tell us your name so people will recognize you.
                        </span>
                    </div>
                </div>

                <a class="btn btn-info update-cover-photo"
                v-on:click="openImageUploaderModal('cover_url')">
                    <i class="fa fa-camera"></i> Update Cover Photo
                </a>
            </header>
            <section class="user-details">
                <div class="form-group clearfix">
                    <label class="control-label col-sm-3">Biography</label>
                    <div class="col-sm-9">
                        <textarea v-model="user.biography" class="form-control"></textarea>
                        <span class="help-block">
                            Tell us something about yourself.
                        </span>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-sm-3">Slug</label>
                    <div class="col-sm-9">
                        <input type="text" v-model="user.slug" class="form-control"/>
                        <span class="help-block">
                            {{ url('/') }}/@{{ user.slug }}
                        </span>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-sm-3">Location</label>
                    <div class="col-sm-9">
                        <input type="text" v-model="user.location" class="form-control"/>
                        <span class="help-block">
                            Where do you live?
                        </span>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-sm-3">Website</label>
                    <div class="col-sm-9">
                        <input type="text" v-model="user.website" class="form-control"/>
                        <span class="help-block">
                            Any other websites?
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"
                    v-on:click="updateUserDetails">
                        Save
                    </button>
                </div>
            </section>
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
                    <button type="button" class="btn btn-primary" v-on:click="saveUserImage">
                        Save
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </aside>
</journal-user-profile>

@include('admin.scripts.image-uploader')
@endsection
