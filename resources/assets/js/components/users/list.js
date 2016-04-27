Vue.component('journal-users-list', {
    data : function () {
        return {
            defaultAvatar : 'http://41.media.tumblr.com/4883a07dc16a879663ce1c8f97352811/tumblr_mldfty8fh41qcnibxo2_540.png',
            users : []
        }
    },

     ready : function () {
        // get the users
        this.getUsers();
     },

     methods : {
        /**
         * Fetch all the users from the API.
         */
        getUsers : function () {
            var vm = this;

            vm.$http.get('/api/users/get')
                .then( function (response) {
                    if (response.data.users) {
                        vm.$set('users', response.data.users);
                    }
                });
        }
     }
});
