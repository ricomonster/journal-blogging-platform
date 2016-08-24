/**
 * Load the Components here
 */
require('./core/components');

/**
 * Export the Journal Application
 */
module.exports = {
    el : '#journal_app',

    data : function () {
        return {
            appSidebar : true
        }
    },

    ready : function () {

    },

    methods : {
        toggleAppSidebar : function () {
            var vm = this;

            // get first the state of the body
            var sidebar = ($('body').hasClass('open-sidebar')) ? 'close' : 'open';

            // saves it to a session in the backend
            vm.$http.post('/api/settings/sidebar', {
                    status : sidebar
                })
                .then( function (response) {
                    if (!response.data.error) {
                        // update the sidebar
                        if (sidebar == 'open') {
                            $('body')
                                .removeClass('close-sidebar')
                                .addClass('open-sidebar');

                            return;
                        }

                        if (sidebar == 'close') {
                            $('body')
                                .removeClass('open-sidebar')
                                .addClass('close-sidebar');

                            return;
                        }
                    }
                });
        }
    }
};
