<template id="image_uploader_template">
    <div class="featured-image">
        <section class="content">
            <div class="image-preview" v-if="imageUrl">
                <a class="btn btn-danger btn-sm remove-image"
                v-on:click="removeCurrentImage()">
                    <i class="fa fa-trash-o"></i>
                </a>
                <img v-bind:src="imageUrl"/>
            </div>
            <div class="file-upload" v-if="!imageUrl && option == 'file'">
                <button type="button" class="btn btn-primary" v-on:click="openFileManager"
                v-if="!loading">
                    <i class="fa fa-upload"></i> Add Image to Upload
                </button>
                <div class="loader" v-if="loading">
                    <i class="fa fa-circle-o-notch fa-spin"></i>
                </div>
                <input type="file" name="files" id="file_uploader" v-on:change="uploadFile"
                accept="image/*"/>
            </div>
            <div class="image-url" v-if="!imageUrl && option == 'link'">
                <input type="text" v-model="inputtedUrl" class="form-control"
                placeholder="http://" v-on:blur="getImageLink"/>
            </div>
        </section>
        <footer class="upload-option" v-if="!imageUrl">
            <a v-on:click="toggleOption()" class="fa"
            v-bind:class="{ 'fa-upload' : option == 'link', 'fa-link' : option == 'file' }"></a>
        </footer>
    </div>
</template>
