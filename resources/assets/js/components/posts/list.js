Vue.component('journal-posts-list', {
    data : function () {
        return {
            active : {},
            posts : [],
            loading : true,
            userId : Journal.userId
        };
    },
    ready : function () {
        this.getPosts();
    },
    methods : {
        /**
         * Deletes the selected/active post.
         */
        deletePost : function () {
            var vm = this,
                post = vm.active;

            vm.$http.delete('/api/posts/delete', {
                    post_id : post.id,
                    user_id : vm.userId
                })
                .then(function (response) {
                    if (!response.data.error) {
                        // close modal
                        $('#delete_post_modal').modal('hide');

                        // get the index of the active post
                        var index = vm.posts.indexOf(post);

                        // remove from the array
                        vm.posts.splice(index, 1);

                        // set a new active post
                        vm.$set('active', vm.posts[0]);

                        toastr.success('You have successfully deleted the post.');
                    }
                })
        },

        /**
         * Gets all the active post from the API.
         */
        getPosts : function () {
            var vm = this;

            vm.loading = true;

            vm.$http.get('/api/posts/get')
                .then(function (response) {
                    if (response.data.posts) {
                        vm.posts = response.data.posts;

                        // get the first post as the active post
                        vm.active = vm.posts[0];
                    }

                    vm.loading = false;
                });
        },

        /**
         * Selects the post and shows in the preview window.
         */
        selectPost : function (post) {
            var vm = this;
            vm.active = post;
        }
    }
});
