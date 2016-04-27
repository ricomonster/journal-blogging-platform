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
            </div>

            <div class="form-group">
                <label class="control-label">Slug</label>
                <input type="text" v-model="newTag.slug" class="form-control"/>
            </div>

            <div class="form-group">
                <label class="control-label">Description</label>
                <textarea v-model="newTag.description" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label class="control-label">Cover Image</label>
                <image-uploader :image.sync="newTag.cover_image"></image-uploader>
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
