/**
 * Load Selectize JS
 */
require('selectize');

Vue.directive('selectize', {
    twoWay : true,

    params: ['tags'],

    bind : function () {
        var vm  = this;

        // initialize the selectize
        this.vm.$once('hook:ready', function () {
            vm.selectize = $(vm.el).selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,
                valueField: 'tag',
                labelField: 'tag',
                searchField: 'tag',
                create: function(input) {
                    return {
                        tag: input
                    }
                }
            })
            // listen for changes
            .on('change', function (e) {
                var tags    = [],
                    value   = e.target.value,
                    // explode
                    explode = value.split(',');

                // loop
                for (var ex in explode) {
                    tags.push({
                        tag : explode[ex]
                    });
                }

                // set the value
                vm.set(tags);
            })[0].selectize;
        });
    },

    update : function (values) {
        var vm = this;

        if (vm.selectize) {
            setTimeout( function () {
                for (var v in values) {
                    vm.selectize.addItem(values[v].title);
                }
            });
        }
    },

    paramWatchers : {
        'tags' : function (value) {
            var vm = this;

            for (var v in value) {
                vm.selectize.addOption({
                    tag : value[v].title
                });
            }
        }
    }
});
