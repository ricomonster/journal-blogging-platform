<aside id="editor_sidebar" v-bind:class="{ active : sidebarOpen }">
    <div class="sidebar-overlay"></div>
    <div class="editor-content">
        <header class="sidebar-header clearfix">
            <h3>Settings</h3>
            <a class="close close-sidebar" v-on:click="toggleSidebar()">&times;</a>
        </header>
        <section class="post-settings">
            <div class="form-group">
                <label class="control-label">Featured Image</label>
                <image-uploader :image.sync="post.cover_image"></image-uploader>
            </div>
            <div class="form-group">
                <label class="control-label">Tags</label>
            </div>
            <div class="form-group">
                <label class="control-label">Post URL</label>
                <input type="text" v-model="post.slug" class="form-control" placeholder="post-url"/>
                <span class="help-block">@{{baseUrl}}/@{{post.slug}}</span>
            </div>
            <div class="form-group">
                <label class="control-label">Published Date/Time</label>
                <input type="datetime-local" v-model="post.published_at" class="form-control"
                placeholder="yyyy-MM-dd HH:mm:ss"/>
            </div>
            <div class="delete-post-zone auto-height" v-if="post.id">
                <p class="message">Are you you wanted to do this?</p>
                <a class="btn btn-danger btn-block" data-toggle="modal"
                data-target="#delete_post_modal">
                    <i class="fa fa-trash-o"></i> Delete this post?
                </a>
            </div>
        </section>
    </div>
</aside>
