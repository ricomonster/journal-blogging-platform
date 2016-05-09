require('../journal-components/menu-sortable');

Vue.component('journal-settings-navigation', {
    data : function () {
       return {
           loading : true,
           menus : [],
           newMenu : {
               label : '',
               url : window.location.protocol + '//' + window.location.host + '/'
           },
           processing : false
       }
    },

    ready : function () {
        this.getMenus();
    },

    methods : {
        /**
         * Adds a new menu based on the given data.
         */
        addNewMenu : function () {
            var vm      = this,
                menus   = vm.menus,
                newMenu = vm.newMenu;

            // check first if label or the url is empty
            if (newMenu.label.length < 1) {
                toastr.error('Set a label for the menu.');
                return;
            }

            if (newMenu.url.length < 1) {
                toastr.error('Set a URL for the menu.');
                return;
            }

            // add the new menu
            menus.push(newMenu);

            // fix the order then update the data
            for (var m in menus) {
                menus[m].order = parseInt(m) + 1;
            }

            vm.$set('menus', menus);

            // empty the form
            vm.$set('newMenu', {
                label : '',
                url : window.location.protocol + '//' + window.location.host + '/'
            });
        },

        /**
         * Fetches the menus from the API
         */
        getMenus : function () {
            var vm = this;

            vm.$http.get('/api/settings/get?fields=navigation')
                .then( function (response) {
                    if (response.data.settings) {
                        // assign to the data
                        if (response.data.settings.name == 'navigation') {
                            vm.$set('menus', JSON.parse(response.data.settings.value));
                        }
                    }
                });
        },

        /**
         * Save the current setup of the menu to the API
         */
        saveMenus : function () {
            var vm = this,
                menus = vm.menus;

            // flag that we're processing
            vm.$set('processing', true);

            // send data to the API
            vm.$http.post('/api/settings/save_settings', { navigation : menus })
                .then( function (response) {
                    if (response.data.settings) {
                        // tell that it is successful
                        toastr.success('You have successfully updated the navigation list.');

                        // assign to the data
                        if (response.data.settings.name == 'navigation') {
                            vm.$set('menus', JSON.parse(response.data.settings.value));
                        }

                        vm.$set('processing', false);
                    }
                })
        }
    }
});
