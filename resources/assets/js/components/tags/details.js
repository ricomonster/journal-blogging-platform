require('./../../journal-components/image-uploader')

Vue.component('journal-tag-details', {
    data : function () {
        return {
            loading : true,
            processing : false,
            tag : []
        }
    },

    ready : function () {
        this.getTag();
    },

    methods : {
        /**
         * Sends a request to the API to delete this tag.
         */
        deleteTag : function () {
            var vm  = this,
                tag = vm.tag;

            // set the processing flag
            vm.$set('processing', true);

            // send request to delete the tag
            vm.$http.delete('/api/tags/delete', { tag_id : tag.id })
                .then( function (response) {
                    if (!response.data.error) {
                        // show success message
                        toastr.success('You have successfully deleted the tag');

                        // redirect after 5 seconds
                        setTimeout( function () {
                            window.location.href = '/journal/tags/list';
                        }, 3500);
                    }
                }, function (response) {
                    // TODO: handle error here
                    // remove processing flag.
                    vm.$set('processing', false);
                });
        },

        /**
         * Fetches the details of the tag from the API.
         */
        getTag : function () {
            var vm = this;

            if ($('input[name="tag_id"]').length < 1) {
                // go to 404 page
                window.location.href = '/journal/tags/list';
            }

            // get the id
            var tagId = $('input[name="tag_id"]').val();

            // get tag from the API
            vm.$http.get('/api/tags/get?tag_id=' + tagId)
                .then( function (response) {
                    if (response.data.tag) {
                        // assign it
                        vm.$set('tag', response.data.tag);
                    }
                }, function () {

                });
        },

        /**
         * Sends the updated tag to the API.
         */
        updateTag : function () {
            var vm  = this,
                tag = vm.tag;

            // flag that we're processing
            vm.$set('processing', true);

            // send request to the API
            vm.$http.put('/api/tags/update?tag_id=' + tag.id, tag)
                .then( function (response) {
                    if (response.data.tag) {
                        // show success message
                        toastr.success('You have successfully updated the tag.');

                        // update tag data
                        vm.$set('tag', response.data.tag);

                        // remove processing flag
                        vm.$set('processing', false);
                    }
                });
        }
    }
})
