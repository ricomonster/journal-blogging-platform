Vue.directive('sync-scroll', function (element) {
    $(this.el)
        .find('.CodeMirror-scroll')
        .on('scroll', function (event) {
            var codemirror = $(event.target),
                codemirrorSizer = $(codemirror).find('.CodeMirror-sizer'),
                synchronizedElement = $(element),
                childSynchronizedElement = synchronizedElement.find('>:first-child'),

                // get the difference of the editor
                editorDifference = codemirrorSizer.outerHeight() - codemirror.outerHeight(),

                // get the difference of the elements to be synchronized
                syncElementsDifference = childSynchronizedElement.outerHeight() - synchronizedElement.outerHeight(),

                // get the quotient of the differences
                quotient = syncElementsDifference / editorDifference,

                // get the scroll position of the editor
                scroll = codemirror.scrollTop() * quotient;

                // set the scroll value
                synchronizedElement.scrollTop(scroll);
        });
});
