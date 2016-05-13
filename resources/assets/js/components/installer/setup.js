Vue.component('journal-installer-setup', {
    /**
     * Initial data
     */
    data : function () {
        return {
            errors : {},
            processing : false,
            setup : {}
        }
    },

    /**
     * Methods
     */
    methods : {
        saveSetup : function (e) {
            e.preventDefault();

            var vm      = this,
                data    = vm.setup;

            // flag that we're processing
            vm.$set('processing', true);

            // perform ajax request so we can save the data
            vm.$http.post('/api/installer/setup', data)
                .then(function(response) {
                    if (response.data.user) {
                        // redirect
                        window.location.href = '/installer/success';
                    }
                }, function (response) {
                    vm.errors = response.data.errors;

                    vm.$set('processing', false);
                });
        }
    }
})
