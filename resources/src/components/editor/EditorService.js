(function() {
    'use strict';

    angular.module('journal.components.editor')
        .service('EditorService', ['$http', '$q', 'AuthService', 'CONFIG', EditorService]);

    function EditorService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getPost = function(postId) {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/posts/get_post?post_id=' + postId)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.getSlug = function(title, id) {
            var deferred = $q.defer(),
                parameters = 'slug='+(title || '');

            // check if there's an ID given
            if (id) {
                // append post id to the parameter
                parameters += '&post_id=' + id;
            }

            // perform request to the API
            $http.get(this.apiUrl + '/posts/check_slug?' + parameters)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.getTags = function() {
            var deferred = $q.defer();

            // perform request to the API
            $http.get(this.apiUrl + '/tags/all')
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.savePost = function(post) {
            var deferred = $q.defer(),
                token = AuthService.token(),
                url = this.apiUrl + '/posts/save?token=' + token,
                authorId        = post.author_id || '',
                title           = post.title || '',
                markdown        = post.markdown || '',
                featuredImage   = post.featured_image || '',
                slug            = post.slug || '',
                status          = post.status || 2,
                tags            = post.tags || [],
                publishedAt     = post.published_at.getTime() / 1000 || Math.floor(Date.now() / 1000);

            // check if post_id is set
            if (post.id) {
                url += '&post_id=' + post.id;
            }

            // send request to the API
            $http.post(url, {
                    author_id       : authorId,
                    title           : title,
                    markdown        : markdown,
                    featured_image  : featuredImage,
                    slug            : slug,
                    status          : status,
                    tags            : tags,
                    published_at    : publishedAt})
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };
    }
})();
