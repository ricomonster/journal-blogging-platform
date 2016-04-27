require('./../../journal-components/image-uploader');

Vue.component('journal-tags-list', {
    data : function () {
        return {
            loading     : true,
            newTag      : {},
            processing  : false,
            sidebarOpen : false,
            tags        : []
        }
    },

    ready : function () {
        this.getTags();

        // a little cheat, sorry
        $('.sidebar-overlay').on('click', function () {
            // toggle the sidebar
            this.toggleSidebar();
        }.bind(this));
    },

    methods : {
        /**
         * Fetches all tags saved in the API
         */
        getTags : function () {
            var vm = this;

            vm.$http.get('/api/tags/get')
                .then( function (response) {
                    if (response.data.tags) {
                        vm.$set('tags', response.data.tags);
                    }
                });
        },

        /**
         * Saves the newly created tag.
         */
        saveNewTag : function () {
            var vm = this,
                tag = vm.newTag;

            // flag that we're processing
            vm.$set('processing', true);

            // send it to the API
            vm.$http.post('/api/tags/create', tag)
                .then( function (response) {
                    if (response.data.tag) {
                        // append to the list of tags
                        vm.tags.push(response.data.tag);

                        // close modal
                        vm.$set('sidebarOpen', false);

                        // clear form
                        vm.newTag = {};

                        // set success message
                        toastr.success('You have successfully created a new tag.');

                        // remove flag
                        vm.$set('processing', false);
                    }
                }, function (response) {
                    // there's an error
                    if (response.data.errors.message) {
                        toastr.error(response.data.errors.message);
                    }

                    // remove flag
                    vm.$set('processing', false);
                });
        },

        /**
         * Toggle to close or open the sidebar
         */
        toggleSidebar : function () {
            this.$set('sidebarOpen', !this.sidebarOpen);
        },
    }
});
