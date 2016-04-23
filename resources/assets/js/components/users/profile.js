require('./../../journal-components/image-uploader');

Vue.component('journal-user-profile', {
    data : function () {
        return {
            images : {
                avatar : 'http://41.media.tumblr.com/4883a07dc16a879663ce1c8f97352811/tumblr_mldfty8fh41qcnibxo2_540.png',
                cover : 'https://gotag-static.s3-eu-west-1.amazonaws.com/assets/core/default_cover-b045d9c32935fda6b3c19cc04082b863.jpg'
            },
            modal : {
                image : null,
                type : null
            },
            loading : true,
            user : {}
        }
    },

    ready : function () {
        this.getUser();
    },

    methods : {
        /**
         * Fetches the user details from the API
         */
        getUser : function () {
            var vm = this;

            // check if user_id is present
            if ($('input[name="user_id"]').length < 1) {
                // redirect to the user lists
                window.location.href = '/journal/users/list';
            }

            var id = $('input[name="user_id"]').val();

            // get the user details
            vm.$http.get('/api/users/get?user_id=' + id)
                .then( function (response) {
                    if (response.data.user) {
                        vm.$set('user', response.data.user);
                    }
                });
        },

        /**
         * Opens the modal for the image uploader and also populates the modal
         * object that will be used by the image uploader.
         */
        openImageUploaderModal : function (type) {
            var vm      = this,
                image   = '';

            // get the image
            if (type == 'avatar_url') {
                image = vm.user.avatar_url;
            }

            if (type == 'cover_url') {
                image = vm.user.cover_url;
            }

            // assign in the modal object
            vm.modal = {
                type : type,
                image : image
            };

            // open modal
            $('#upload_image_modal').modal('show');
        },

        saveUserImage : function () {
            var vm      = this,
                message = '',
                modal   = vm.modal,
                user    = vm.user;

            // get the image from the modal and assign to the respected type
            if (modal.type == 'avatar_url') {
                // update the object
                user.avatar_url = modal.image;

                // set the success message
                message = 'You have successfully updated your avatar.';
            }

            if (modal.type == 'cover_url') {
                // update the object
                user.cover_url = modal.image;

                // set the success message
                message = 'You have successfully updated your cover photo.';
            }

            vm.$http.post('/api/users/update?user_id=' + user.id, user)
                .then( function (response) {
                    if (response.data.user) {
                        // show success message
                        toastr.success(message);

                        // update the data
                        vm.$set('user', response.data.user);

                        // close the modal
                        $('#upload_image_modal').modal('hide');
                    }
                }, function (response) {

                });
        },

        /**
         * Sends the update user details to the API
         */
        updateUserDetails : function () {
            var vm      = this,
                user    = vm.user;

            vm.$http.post('/api/users/update?user_id=' + user.id, user)
                .then( function (response) {
                    if (response.data.user) {
                        // show success message
                        toastr.success('You have successfully updated your profile.');

                        // update the data
                        vm.$set('user', response.data.user);
                    }
                }, function (response) {

                });
        }
    }
});
