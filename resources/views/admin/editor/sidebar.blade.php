<aside id="editor_sidebar" v-bind:class="{ active : sidebarOpen }">
    <div class="sidebar-overlay"></div>
    <div class="content">
        <header class="sidebar-header clearfix">
            <h3>Settings</h3>
            <a class="close close-sidebar" v-on:click="toggleSidebar()">&times;</a>
        </header>
        <section class="post-settings">
            <div class="form-group">
                <label class="control-label">Featured Image</label>
                <!-- <div id="featured_image_component">
                    <div class="wrapper">
                        <section class="content">
                            <div class="image-preview">
                                <a class="fa fa-times" v-on:click="removeCurrentImage()"></a>
                                <img src=""/>
                            </div>
                            <div class="file-upload">

                            </div>
                            <div class="image-url"></div>
                        </section>
                        <footer class="upload-option">
                            <a v-on:click="toggleOption()" class="fa"
                            v-bind:class="{ 'fa-upload' : option == 'link', 'fa-link' : option == 'file' }"></a>
                        </footer>
                    </div>
                </div> -->
            </div>
            <div class="form-group">
                <label class="control-label">Tags</label>
            </div>
            <div class="form-group">
                <label class="control-label">Post URL</label>
                <input type="text" v-model="post.slug" class="form-control" placeholder="post-url"/>
                <span class="help-block"></span>
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
