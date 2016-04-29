<aside id="tag_sidebar" v-bind:class="{ active : sidebarOpen }">
    <div class="sidebar-overlay"></div>
    <div class="tag-sidebar-content">
        <header class="sidebar-header clearfix">
            <h3>Add new tag</h3>
            <a class="close close-sidebar" v-on:click="toggleSidebar">&times;</a>
        </header>
        <section class="tag-form">
            <div class="form-group">
                <label class="control-label">Title</label>
                <input type="text" v-model="newTag.title" class="form-control"/>
                <span class="help-block">The name is how it appears on your site.</span>
            </div>

            <div class="form-group">
                <label class="control-label">Slug</label>
                <input type="text" v-model="newTag.slug" class="form-control"/>
                <span class="help-block">
                    The "slug" is the URL-friendly version of the name.
                    It is usually all lowercase and contains only
                    letters, numbers, and hyphens.
                </span>
            </div>

            <div class="form-group">
                <label class="control-label">Description</label>
                <textarea v-model="newTag.description" class="form-control"></textarea>
                <span class="help-block">Tell us something about this tag.</span>
            </div>

            <div class="form-group">
                <label class="control-label">Cover Image</label>
                <image-uploader :image.sync="newTag.cover_image"></image-uploader>
                <span class="help-block">
                    An image that describes this tag.
                </span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"
                v-button-loader="processing" v-on:click="saveNewTag">
                    Save
                </button>
            </div>
        </section>
    </div>
</aside>
