<article id="upload_image_modal" class="modal fade" tabindex="-1" role="dialog"
v-if="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <image-uploader :image.sync="modal.image" :type="modal.type"></image-uploader>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" v-on:click="saveImage"
                        v-button-loader="processing">
                    Save
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</article>