Vue.directive('codemirror', {
    twoWay : true,

    params: ['action'],

    bind : function () {
        this.codemirror = CodeMirror(this.el, {
            mode : "markdown",
            tabMode : "indent",
            lineWrapping : !0
        });

        this.codemirror.on("change", function () {
            this.set(this.codemirror.getValue());
        }.bind(this));
    },

    update: function (value, oldValue) {
        this.codemirror.setValue(value || '');
    }
});
