Vue.component('journal-user-profile', {
    data : function () {
        return {
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
