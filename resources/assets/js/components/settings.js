Vue.component('journal-settings', {
    data : function () {
        return {

        }
    },

    ready : function () {
        this.getBlogSettings();
    },

    methods : {
        getBlogSettings : function () {
            var vm = this;

            vm.$http.get('/api/settings/get?'+parameters)
                .then(function (response) {
                    if (response.data.settings) {

                    }
                }, function () {

                });
        }
    }
});
