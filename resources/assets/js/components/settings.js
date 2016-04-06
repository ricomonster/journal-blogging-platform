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

        /**
         * Opens the modal for the image uploader and also populates the modal
         * object that will be used by the image uploader.
         */
        openImageUploaderModal : function (type) {
            var vm = this;

            // get the value of the selected type
            var image = vm.settings[type];

            // assign in the modal object
            vm.modal = {
                type : type,
                image : image
            };

            // open modal
            $('#upload_image_modal').modal('show');
        },

        /**
         * Sends request to the API so we can save the newly inputted image.
         */
        saveImageSettings : function () {
            var vm          = this,
                settings    = vm.settings,
                message     = (vm.modal.type == 'cover_url') ? 'cover photo' : 'avatar',
                data        = {};

            // prepare the data
            data[vm.modal.type] = vm.modal.image;

            vm.$http.post('/api/settings/save_settings', data)
                .then( function (response) {
                    if (response.data.settings) {
                        // update the settings scope
                        settings[response.data.settings[0].name] = response.data.settings[0].value;

                        vm.$set('settings', settings);

                        // show success message
                        toastr.success('You have successfully updated ' + message + ' of your blog.');

                        // close the modal
                        $('#upload_image_modal').modal('hide');
                    }
                }, function (response) {
                    var error = response.data.message;

                    toastr.error(error);
                });
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
