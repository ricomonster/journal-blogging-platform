(function() {
    'use strict';

    angular.module('journal.component.editor')
        .service('EditorService', ['$http', 'AuthService', 'CONFIG', EditorService]);

    function EditorService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.checkSlug = function(title, id) {
            var url = this.apiUrl + '/posts/check_slug?slug=' + (title || '');

            // check if id is set
            if (id) {
                url += '&post_id=' + id;
            }

            // do an API request
            return $http.get(url);
        };

        this.getPost = function(id) {
            return $http.get(this.apiUrl + '/posts/get_post?post_id=' + id);
        };

        this.getTags = function() {
            return $http.get(this.apiUrl + '/tags/all');
        };

        this.save = function(post) {
            var token           = AuthService.getToken(),
                url             = this.apiUrl + '/posts/save?token=' + token,
                authorId        = post.author_id || '',
                title           = post.title || '',
                markdown        = post.markdown || '',
                featuredImage   = post.featured_image || '',
                slug            = post.slug || '',
                status          = post.status || 2,
                tags            = post.tags || [],
                publishedAt     = post.published_at || Math.floor(Date.now() / 1000);

            // check if post_id is set
            if (post.id) {
                url += '&post_id=' + post.id;
            }

            // send the request to the API
            return $http.post(url, {
                author_id       : authorId,
                title           : title,
                markdown        : markdown,
                featured_image  : featuredImage,
                slug            : slug,
                status          : status,
                tags            : tags,
                published_at    : publishedAt});
        };
    }
})();
