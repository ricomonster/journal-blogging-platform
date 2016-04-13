Vue.component('journal-tags-list', {
    data : function () {
        return {
            tags : []
        }
    },

    ready : function () {
        this.getTags();
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
        }
    }
});
