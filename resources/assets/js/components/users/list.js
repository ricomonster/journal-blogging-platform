Vue.component('journal-users-list', {
    data : function () {
        return {
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
