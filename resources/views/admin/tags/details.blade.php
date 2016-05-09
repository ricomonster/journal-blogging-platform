@extends('admin.layout')
@section('title', 'Update Tag')

@section('content')
<journal-tag-details inline-template>
    <div id="tags_details_page">
        <header class="page-header clearfix">
            <a href="{{ url('journal/tags') }}" class="back">
                <i class="fa fa-angle-left"></i> Back
            </a>

            <h1 class="page-title">Edit Tag</h1>
        </header>
        <section class="tag-details scrollable-content">
            <div class="centered-content form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-3">
                        Title
                    </label>
                    <div class="col-sm-9">
                        <input type="text" v-model="tag.title" class="form-control"/>
                        <span class="help-block">The name is how it appears on your site.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">
                        Slug
                    </label>
                    <div class="col-sm-9">
                        <input type="text" v-model="tag.slug" class="form-control"/>
                        <span class="help-block">
                            The "slug" is the URL-friendly version of the name.
                            It is usually all lowercase and contains only
                            letters, numbers, and hyphens.
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Description</label>
                    <div class="col-sm-9">
                        <textarea v-model="tag.description" class="form-control"></textarea>
                        <span class="help-block">Tell us something about this tag.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Cover Image</label>
                    <div class="col-sm-9">
                        <image-uploader :image.sync="tag.cover_image"></image-uploader>
                        <span class="help-block">
                            An image that describes this tag.
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-info"
                    v-button-loader="processing" v-on:click="updateTag">
                        Update
                    </button>
                    <button type="button" class="btn btn-danger delete-tag"
                    data-toggle="modal" data-target="#delete_tag_modal">
                        Delete tag
                    </button>
                </div>
            </div>
        </section>

        @if ($tagId)
        <input type="hidden" name="tag_id" value="{{ $tagId }}"/>
        @endif
    </div>

    <aside id="delete_tag_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <header class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Delete Post</h4>
                </header>
                <section class="modal-body">
                    <p>
                        Are you sure you wanted to delete the tag
                        <span class="tag-title">@{{tag.title}}</span>?
                    </p>
                </section>
                <footer class="modal-footer">
                    <button type="button" class="btn btn-danger" v-on:click="deleteTag"
                    v-button-loader="processing">
                        Delete this tag
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </footer>
            </div>
        </div>
    </aside>
</journal-tag-details>

@include('admin.scripts.image-uploader')
@endsection
