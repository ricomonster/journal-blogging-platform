Vue.component('journal-posts-list', {
    data : function () {
        return {
            active : {},
            posts : [],
            loading : true,
            test : 'ei'
        };
    },
    ready : function () {
        this.getPosts();
    },
    methods : {
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
        selectPost : function (post) {
            var vm = this;
            vm.active = post;
        }
    }
});
