Vue.component('image-uploader', {
    template : '#image_uploader_template',

    props : ['image', 'type'],

    data : function () {
        return {
            imageUrl        : '',
            inputtedUrl     : '',
            loading         : false,
            option          : 'file',
            toUploadFile    : ''
        }
    },

    ready : function () {
        // make sure that everytime this loads up, it will be empty
        this.$set('option', 'file');
        this.$set('imageUrl', '');
        this.$set('inputtedUrl', '');
    },

    methods : {
        /**
         * Gets the input and shows it as the image
         */
        getImageLink : function () {
            var vm = this;

            // let's put a set timeout so once the user blur out from the
            // input field, the image won't appear immediately. Give it
            // some time.
            setTimeout( function () {
                // get the value of the input and set as a value
                vm.$set('imageUrl', vm.inputtedUrl);

                // now assign this to the prop
                vm.$set('image', vm.inputtedUrl);
            }, 1000);
        },

        /**
         * A little cheat on how to open the file manager
         */
        openFileManager : function () {
            $('#file_uploader').click();
        },

        /**
         * Removes the current image shown.
         */
        removeCurrentImage : function () {
            var vm = this;

            // set null!
            vm.$set('imageUrl', null);

            // remove the value of the input field for the image link
            vm.$set('inputtedUrl', null);

            // also the prop!
            vm.$set('image', null);
        },

        /**
         * Toggles the option or preference of the user on how to set an
         * image.
         */
        toggleOption : function () {
            var vm = this;

            // check the active option
            vm.option = (vm.option == 'file') ? 'link' : 'file';
        },

        /**
         * Performs an API request to upload the selected file.
         */
        uploadFile : function (e) {
            e.preventDefault();

            var vm = this,
                // get files
                files = e.target.files[0],
                // initialize Form Data
                data = new FormData();

            // append the file to the form data
            data.append('files', files);

            // flag that we're loading
            vm.$set('loading', true);

            // send to the API
            vm.$http.post('/api/upload', data)
                .then( function (response) {
                    if (response.data.url) {
                        // set the new image
                        vm.$set('imageUrl', response.data.url);

                        // assign to the prop
                        vm.$set('image', response.data.url);

                        // flag to false the loading
                        vm.$set('loading', false);
                    }
                }, function (response) {
                    // there's an error
                    toastr.error(response.data.message);

                    // flag to false the loading
                    vm.$set('loading', false);
                });
        }
    },

    watch : {
        /**
         * Watches the changes to the image property and once there's a change
         * detected it will assign to the imageUrl variable.
         */
        'image' : function (value) {
            var vm = this;

            // assign to imageUrl
            vm.$set('imageUrl', value);
        }
    }
});
