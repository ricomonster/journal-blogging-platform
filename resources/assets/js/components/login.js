Vue.component('journal-login', {
    data : function () {
        return {
            processing : false,
        }
    },

    methods : {
        login : function () {
            var vm = this;

            // flag that we're processing
            vm.$set('processing', true);
        }
    }
});
