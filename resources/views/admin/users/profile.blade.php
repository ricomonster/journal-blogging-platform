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
                <img src="https://gotag-static.s3-eu-west-1.amazonaws.com/assets/core/default_cover-b045d9c32935fda6b3c19cc04082b863.jpg"
                class="cover-photo"/>
                <div class="user-avatar-details">
                    <figure class="avatar">
                        <img src="http://41.media.tumblr.com/4883a07dc16a879663ce1c8f97352811/tumblr_mldfty8fh41qcnibxo2_540.png"/>
                    </figure>
                    <div id="name" class="form-group">
                        <input type="text" class="form-control" v-model="user.name"/>
                        <span class="help-block">
                            Tell us your name so people will recognize you.
                        </span>
                    </div>
                </div>

                <a class="btn btn-info update-cover-photo">
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
</journal-user-profile>
@endsection
