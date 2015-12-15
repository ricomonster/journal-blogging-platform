(function() {
    'use strict';

    angular.module('journal.constant')
        .constant('CONFIG', {
            'API_URL' : 'http://localhost:8000/api/v1.0',
            'DEFAULT_AVATAR_URL' : 'http://40.media.tumblr.com/7d65a925636d6e3df94e2ebe30667c29/tumblr_nq1zg0MEn51qg6rkio1_500.jpg',
            'DEFAULT_COVER_URL' : '/assets/images/wallpaper.jpg',
            'VERSION' : '1.5.5',
            'CDN_URL' : 'http://localhost:8000'
        });
})();
