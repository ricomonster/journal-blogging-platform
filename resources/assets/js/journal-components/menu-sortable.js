Vue.component('menu-sortable', {
    template: '#menu_sortable_template',

    props : [{
        name : 'menus',
        type : Array
    }],

    ready : function () {
        var vm = this;

        Sortable.create(vm.$el, {
            draggable: 'article',
            handle : '.handler',

            onSort : function (event) {
                vm.sortMenus(event.oldIndex, event.newIndex);
            }
        });
    },

    methods : {
        /**
         * Removes the selected menu from the lists.
         */
        deleteMenu : function (menu) {
            var vm = this,
                menus = vm.menus;

            // get the index of the current menu
            var index = menus.indexOf(menu);

            // remove it from the list of menus
            menus.splice(index, 1);

            // update the order
            for (var m in menus) {
                menus[m].order = parseInt(m) + 1;
            }

            // set it
            vm.$set('menus', menus);
        },

        /**
         * Sort the menus after it was dragged or something.
         */
        sortMenus : function (oldIndex, newIndex) {
            var vm = this,
                menus = vm.menus;

            // get the menu
            var menu = menus[oldIndex];

            // remove
            menus.splice(oldIndex, 1);

            // update
            menus.splice(newIndex, 0, menu);

            // update the order
            for (var m in menus) {
                menus[m].order = parseInt(m) + 1;
            }

            // set it
            vm.$set('menus', menus);
        }
    }
});