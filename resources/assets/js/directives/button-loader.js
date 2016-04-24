Vue.directive('button-loader', {
    bind : function () {
        var vm      = this,
            element = $(vm.el);

        // wrap it to jquery
        element
            // set some class
            .addClass('button-loader')
            // prepend the spinner
            .prepend('<i class="fa fa-circle-o-notch fa-spin"></i>');
    },

    update : function (state) {
        var vm      = this,
            element = $(vm.el);

        // processing
        if (state) {
            element
                .addClass('processing btn-disabled')
                // disable the button
                .attr('disabled', 'disabled');
        }

        if (!state) {
            element
                .removeClass('processing btn-disabled')
                // disable the button
                .removeAttr('disabled');
        }
    }
});
