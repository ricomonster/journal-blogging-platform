@extends('admin.layout')
@section('title', 'Settings')

@section('content')
<journal-settings inline-template>
    <div id="settings_page">
        <header class="page-header clearfix">
            <h1 class="page-title">Settings</h1>
        </header>
        <section class="settings scrollable-content">
            <form class="form-wrapper form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog title</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control"/>
                        <span class="help-block">The name of your blog.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control"></textarea>
                        <span class="help-block">Describe what your blog is all about.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog logo</label>
                    <div class="image-wrapper col-sm-9">
                        <a class="open-modal">
                            <!-- <img ng-src=""/> -->
                        </a>
                        <a class="btn btn-info">
                            <i class="fa fa-camera"></i> Upload a photo.
                        </a>

                        <span class="help-block">Upload a logo for your blog.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Blog cover photo</label>
                    <div class="image-wrapper col-sm-9">
                        <a class="open-modal">
                            <!-- <img ng-src=""/> -->
                        </a>
                        <a class="btn btn-info">
                            <i class="fa fa-camera"></i> Upload a photo.
                        </a>

                        <span class="help-block">Upload a cover photo for your blog.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3">Post per page</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control"/>
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
</journal-settings>
@endsection
