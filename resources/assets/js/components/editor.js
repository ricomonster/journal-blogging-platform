require('./../directives/codemirror');

Vue.component('journal-editor', {
    data: function () {
        return {
            loading : true,
            post : {
                status : 2
            },
            userId : Journal.userId,
            active : [],
            buttons : [
                { class : 'btn-danger', group : 1, status : 1, text : 'Publish Now' },
                { class : 'btn-primary', group : 1, status : 2, text : 'Save as Draft' },
                { class : 'btn-danger', group : 2, status : 2, text : 'Unpublish Post' },
                { class : 'btn-info', group : 2, status : 1, text : 'Update Post' }
            ]
        };
    },
    ready : function () {
        this.renderButtons();

        if ($('input[name="post_id"]').length > 0) {
            // get the the post
            this.getPost($('input[name="post_id"').val());
        }
    },
    methods : {
        /**
         * Fetches the post from the API
         */
        getPost : function (postId) {
            var vm = this;

            vm.$http.get('/api/posts/get?post_id='+postId)
                .then(function (response) {
                    if (response.data.post) {
                        vm.$set('post', response.data.post);

                        // render the buttons
                        vm.renderButtons();
                    }
                });
        },
        /**
         * Saves the post to the API
         */
        savePost : function () {
            var vm = this,
                post = vm.post,
                successMessage = 'You have successfully created a new post.',
                url = '/api/posts/save?author_id='+vm.userId;

            // check if post id is present
            if (post.id) {
                url += '&post_id='+post.id;

                successMessage = 'You have successfully updated your post.';
            }

            // save post
            vm.$http.post(url, post)
                .then(function (response) {
                    if (response.data.post) {
                        // update the post data
                        vm.post = response.data.post;

                        // notify user for success
                        toastr.success(successMessage, 'Success!');

                        // update the buttons
                        vm.renderButtons();
                    }
                }, function (response) {
                    // error, show it
                    toastr.error('Something went wrong while saving your post.', 'Error');
                });
        },
        /**
         * This will render the buttons to be shown
         */
        renderButtons : function () {
            // we're going to assume that status is published.
            var selectedOption = this.buttons[3];

            // check if the post status is draft
            if (this.post.status == 2) {
                selectedOption = this.buttons[1];
            }

            // set the button option
            this.active = selectedOption;
        },
        /**
         * Set the status of the post
         */
        setPostStatus : function (option) {
            var vm = this;

            // set the selected one to be active
            vm.active = option;

            // reflect to the prop
            vm.post.status = option.status;
        }
    }
});
