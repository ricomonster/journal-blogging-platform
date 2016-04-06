Vue.component('journal-database-setup', {
    data : function () {
        return {
            database : {}
        }
    },

    methods : {
        saveDatabaseSettings : function () {
            var vm = this,
                data = vm.database;

            // send request to the API
            vm.$http.post('/api/installer/database', data)
                .then( function (response) {
                    if (response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    }
                }, function (response) {
                    // error
                    var message = response.data.message;

                    console.log(message);
                })
        }
    }
});