require('./image-uploader');

Vue.component('journal-settings', {
    data : function () {
        return {
            modal : {
                image : null,
                type : null
            },
            settings : {},
            themes : {}
        }
    },

    ready : function () {
        this.getThemes();
        this.getBlogSettings();
    },

    methods : {
        /**
         * Fetches the settings of the blog from the API.
         */
        getBlogSettings : function () {
            var vm = this,
                parameters = 'blog_title,blog_description,logo_url,cover_url,'+
                    'post_per_page,theme_template';

            vm.$http.get('/api/settings/get?fields='+parameters)
                .then(function (response) {
                    if (response.data.settings) {
                        // prepare the data so we can easily put it in the template
                        var fields = {},
                            settings = response.data.settings;

                        for (var s in settings) {
                            fields[settings[s].name] = settings[s].value;
                        }

                        vm.$set('settings', fields);
                    }
                }, function () {

                });
        },

        /**
         * Get the lists of installed themes.
         */
        getThemes : function () {

        },

        openImageUploaderModal : function (type) {
            var vm = this;

            // get the value of the selected type
            // assign in the modal object
            // open modal
        },

        /**
         * Saves the settings
         */
        saveSettings : function () {
            var vm = this,
                settings = vm.settings;

            vm.$http.post('/api/settings/save_settings', settings)
                .then( function (response) {
                    if (response.data.settings) {
                        // update the data scope

                        // show success message
                        toastr.success('You have successfully updated the blog settings.');
                    }
                }, function (response) {
                    var error = response.data.message;
                });
        }
    }
});
