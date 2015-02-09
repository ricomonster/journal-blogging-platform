var Journal = {
    notification : function(message, type) {
        $('.top-right').notify({
            type : type,
            message: { text: message },
            transition : 'fade',
            fadeOut: { enabled: true, delay: 10000 }
        }).show();
    }
};
