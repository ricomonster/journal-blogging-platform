require('./../directives/codemirror');
require('./../directives/selectize');
require('./../directives/sync-scroll');

require('./../journal-components/image-uploader');

Vue.component('journal-editor', {
    data: function () {
        return {
            active : [],
            baseUrl : window.location.protocol + "//" + window.location.host,
            buttons : [
                { class : 'btn-danger', group : 1, status : 1, text : 'Publish Now' },
                { class : 'btn-primary', group : 1, status : 2, text : 'Save as Draft' },
                { class : 'btn-danger', group : 2, status : 2, text : 'Unpublish Post' },
                { class : 'btn-info', group : 2, status : 1, text : 'Update Post' }
            ],
            counter : {
                enable : true,
                count : 0
            },
            loading : true,
            sidebarOpen : false,
            post : {
                status : 2,
                slug : '',
                published_at : ''
            },
            processing : false,
            tags : [],
            userId : Journal.userId
        };
    },

    ready : function () {
        // get the tags from the API
        this.getTags();

        // render the buttons based on the post status
        this.renderButtons();

        // set the publish date and time
        this.setPublishDateTime();

        if ($('input[name="post_id"]').length > 0) {
            // get the the post
            this.getPost($('input[name="post_id"').val());
        }

        // a little cheat, sorry
        $('.sidebar-overlay').on('click', function () {
            // toggle the sidebar
            this.toggleSidebar();
        }.bind(this));
    },

    methods : {
        /**
         * Deletes the selected/active post.
         */
        deletePost : function () {
            var vm = this,
                post = vm.post;

            vm.$http.delete('/api/posts/delete', {
                    post_id : post.id,
                    user_id : vm.userId
                })
                .then(function (response) {
                    if (!response.data.error) {
                        toastr.success('You have successfully deleted the post.');

                        setTimeout(function () {
                            // redirect
                            window.location.href = '/journal/posts/list';
                        }, 3000);
                    }
                })
        },

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

                        // set the published date time
                        vm.setPublishDateTime();
                    }
                });
        },

        /**
         * Fetches all the tags saved in the API.
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
         * Saves the post to the API
         */
        savePost : function (e) {
            e.preventDefault();

            var vm = this,
                post = vm.post,
                successMessage = 'You have successfully created a new post.',
                url = '/api/posts/save?author_id='+vm.userId;

            // flag that we're processing something
            vm.$set('processing', true);

            // check if post id is present
            if (post.id) {
                url += '&post_id='+post.id;

                successMessage = 'You have successfully updated your post.';
            }

            // fix the publish date, convert it to timestamp
            post.published_at = moment(post.published_at).unix();

            // save post
            vm.$http.post(url, post)
                .then(function (response) {
                    if (response.data.post) {
                        // update the post data
                        vm.post = response.data.post;

                        // check if this is a new post
                        if (!post.id && history.pushState) {
                            // set the new url
                            var newurl = vm.baseUrl +
                                window.location.pathname + '/'+vm.post.id;

                            // update the url
                            window.history.pushState({path:newurl},'',newurl);
                        }

                        // notify user for success
                        toastr.success(successMessage, 'Success!');

                        // update the buttons
                        vm.renderButtons();

                        // update the published date time
                        vm.setPublishDateTime();

                        // remove the processing flag
                        vm.$set('processing', false);
                    }
                }, function (response) {
                    // error, show it
                    toastr.error('Something went wrong while saving your post.', 'Error');

                    // update the buttons
                    vm.renderButtons();

                    // remove the processing flag
                    vm.$set('processing', false);
                });
        },

        /**
         * This will render the buttons to be shown
         */
        renderButtons : function () {
            var vm = this;

            // we're going to assume that status is published.
            var selectedOption = vm.buttons[3];

            // check if the post status is draft
            if (vm.post.status == 2) {
                selectedOption = vm.buttons[1];
            }

            // set the button option
            vm.$set('active', selectedOption);
        },

        /**
         * Set the status of the post
         */
        setPostStatus : function (option) {
            var vm = this;

            // set the selected one to be active
            vm.$set('active', option);

            // reflect to the prop
            vm.$set('post.status', option.status);
        },

        /**
         * Toggle to close or open the sidebar
         */
        toggleSidebar : function () {
            this.$set('sidebarOpen', !this.sidebarOpen);
        },

        /**
         * Gets the value of the title and send to the API to generate a slug
         */
        generateSlug : function () {
            var vm = this,
                title = vm.post.title || '',
                url = '/api/posts/generate_slug';

            // let's not send if the title is empty
            if (!title) {
                return;
            }

            // check if post id is present
            if (vm.post.id) {
                url += '?post_id='+vm.post.id;
            }

            // send request to the api
            vm.$http.post(url, { string : title })
                .then(function (response) {
                    if (response.data.slug) {
                        // update the post slug
                        vm.$set('post.slug', response.data.slug);
                    }
                }, function () {
                    // do something
                });
        },

        /**
         * Sets the date and time to be shown in the input datetime-local field.
         */
        setPublishDateTime : function () {
            var vm = this,
                // set format
                format = 'YYYY-MM-DD[T]HH:mm';

            if (vm.post.published_at) {
                // render the published date
                vm.$set(
                    'post.published_at',
                    moment.unix(vm.post.published_at).format(format));
                return;
            }

            // get the current date and set as published date/time
            vm.$set('post.published_at', moment().format(format));
        }
    }
});
