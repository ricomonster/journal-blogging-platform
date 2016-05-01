Vue.component('journal-users-change-password', {
    data : function () {
        return {
            password : {},
            processing : false
        }
    },

    methods : {
        /**
         * Submits the data to the API.
         */
        submitPasswords : function () {
            var vm          = this,
                userId      = window.Journal.userId,
                password    = vm.password;

            // flag that we're processing
            vm.$set('processing', true);

            // send request to the API
            vm.$http.put('/api/users/change-password?user_id=' + userId, password)
                .then( function (response) {
                    if (!response.data.error) {
                        // update successful
                        // empty the form
                        vm.$set('password', {});

                        // show success message
                        toastr.success('You have successfully changed your password.');

                        // remove processing flag
                        vm.$set('processing', false);
                    }
                }, function (response) {
                    // ERRORS!
                    // remove processing flag
                    vm.$set('processing', false);

                    if (response.data.errors) {
                        var errors = response.data.errors;

                        // check if there's just a message
                        if (errors.message) {
                            toastr.error(errors.message);
                            return;
                        }

                        // loop this shit
                        for (var e in errors) {
                            // toastr it
                            toastr.error(errors[e][0]);
                        }
                    }
                });
        }
    }
});
