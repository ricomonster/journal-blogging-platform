Vue.extend({
    template : '#editor_buttons_template',
    props : ['status'],
    data : function () {
        return {
            active : [],
            buttons : [
                { class : 'btn-danger', group : 1, status : 1, text : 'Publish Now' },
                { class : 'btn-primary', group : 1, status : 2, text : 'Save as Draft' },
                { class : 'btn-danger', group : 2, status : 2, text : 'Unpublish Post' },
                { class : 'btn-info', group : 2, status : 1, text : 'Update Post' }]
        };
    },
    ready : function () {
        this.renderButtons();
    },
    methods : {
        /**
         * This will render the buttons to be shown
         */
        renderButtons : function () {
            // we're going to assume that status is published.
            var selectedOption = this.buttons[3];

            // check if the post status is draft
            if (this.status == 2) {
                selectedOption = this.buttons[1];
            }

            // set the button option
            this.active = selectedOption;
        },
        /**
         * Set the status of the post
         */
        setPostStatus : function (option) {
            var vm = this;

            // set the selected one to be active
            vm.active = option;

            // reflect to the prop
            vm.status = option.status;
        }
    }
});
