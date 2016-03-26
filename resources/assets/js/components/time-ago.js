Vue.component('time-ago', {
    template : '<span class="time-ago">{{relative}}</span>',
    props : ['timestamp'],
    computed : {
        relative : function () {
            var timestamp = this.timestamp;
            return moment.unix(timestamp).fromNow();
        }
    }
});
