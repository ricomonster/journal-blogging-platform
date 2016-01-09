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

(function() {
    'use strict';

    angular.module('journal.components.login')
        .service('LoginService', ['$http', '$q', 'CONFIG', LoginService]);

    /**
     *
     * @param $http
     * @param $q
     * @param CONFIG
     * @constructor
     */
    function LoginService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        /**
         *
         * @param email
         * @param password
         * @returns {*}
         */
        this.authenticate = function(email, password) {
            var deferred = $q.defer(),
                parameters = {
                    email       : email,
                    password    : password
                };

            $http.post(this.apiUrl + '/auth/authenticate', parameters)
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
(function() {
    'use strict';

    angular.module('journal.components.postLists')
        .service('PostListsService', ['$http', '$q', 'CONFIG', PostListsService]);

    function PostListsService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getAllPosts = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/posts/all')
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
(function() {
    'use strict';

    angular.module('journal.components.settingsGeneral')
        .service('SettingsGeneralService', ['$http', '$q', 'AuthService', 'CONFIG', SettingsGeneralService]);

    function SettingsGeneralService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getSettings = function(fields) {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/settings/get?fields='+fields)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.saveSettings = function(settings) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                request     = {};

            // prepare the request
            for (var s in settings) {
                request[s] = settings[s];
            }

            // send request to the API
            $http.post(this.apiUrl + '/settings/save?token=' + token, request)
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.themes = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/settings/themes')
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        }
    }
})();

(function() {
    'use strict';

    angular.module('journal.components.settingsGeneralModal')
        .service('SettingsGeneralModalService', ['$http', '$q', 'AuthService', 'CONFIG',SettingsGeneralModalService]);

    function SettingsGeneralModalService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.saveSettings = function(settings) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                request     = {};

            // prepare the request
            for (var s in settings) {
                request[s] = settings[s];
            }

            // send request to the API
            $http.post(this.apiUrl + '/settings/save?token=' + token, request)
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

(function() {
    'use strict';

    angular.module('journal.components.sidebar')
        .service('SidebarService', ['$http', '$q', 'CONFIG', SidebarService]);

    function SidebarService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getBlogSettings = function() {
            var deferred = $q.defer(),
                fields = 'title';

            $http.get(this.apiUrl + '/settings/get?fields=' + fields)
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
(function() {
    'use strict';

    angular.module('journal.components.tagEdit')
        .service('TagEditService', ['$http', '$q', 'AuthService', 'CONFIG', TagEditService]);

    function TagEditService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;
    }
})();

(function() {
    'use strict';

    angular.module('journal.components.tagLists')
        .service('TagListsService', ['$http', '$q', 'AuthService', 'CONFIG', TagListsService]);

    function TagListsService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createTag = function(tag) {
            var deferred    = $q.defer(),
                token       = AuthService.token();

            // perform API request
            $http.post(this.apiUrl + '/tags/create?token=' + token, {
                    name        : tag.name          || '',
                    slug        : tag.slug          || '',
                    image_url   : tag.image_url     || '',
                    description : tag.description   || ''
                })
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

            $http.get(this.apiUrl + '/tags/all')
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

(function() {
    'use strict';

    angular.module('journal.components.userCreate')
        .service('UserCreateService', ['$http', '$q', 'AuthService', 'CONFIG', UserCreateService]);

    function UserCreateService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createUser = function(user) {
            var deferred = $q.defer(),
                token = AuthService.token();

            $http.post(this.apiUrl + '/users/create?token=' + token, {
                    name        : user.name     || '',
                    email       : user.email    || '',
                    password    : user.password || '',
                    role        : user.role     || ''
                })
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.getRoles = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/roles/all')
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

(function() {
    'use strict';

    angular.module('journal.components.userLists')
        .service('UserListsService', ['$http', '$q', 'CONFIG', UserListsService]);

    function UserListsService($http, $q, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getUsers = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/users/all')
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
(function() {
    'use strict';

    angular.module('journal.components.userProfile')
        .service('UserProfileService', [
            '$http', '$q', 'AuthService', 'CONFIG', UserProfileService]);

    function UserProfileService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getUser = function(id) {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/users/get_user?user_id=' + id || '')
                .success(function(response) {
                    deferred.resolve(response);
                })
                .error(function(error) {
                    deferred.reject(error);
                });

            return deferred.promise;
        };

        this.updateProfileDetails = function(user) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                userId      = user.id || '';

            $http.post(this.apiUrl + '/users/update_profile?user_id='+userId+'&token='+token, {
                    name        : user.name         || '',
                    email       : user.email        || '',
                    biography   : user.biography    || '',
                    location    : user.location     || '',
                    website     : user.website      || '',
                    avatar_url  : user.avatar_url   || '',
                    cover_url   : user.cover_url    || ''
                })
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

(function() {
    'use strict';

    angular.module('journal.components.userProfileModal')
        .service('UserProfileModalService', [
            '$http', '$q', 'AuthService', 'CONFIG', UserProfileModalService]);

    function UserProfileModalService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.updateUserDetails = function(user) {
            var deferred    = $q.defer(),
                token       = AuthService.token(),
                userId      = user.id || '';

            $http.post(this.apiUrl + '/users/update_profile?user_id='+userId+'&token='+token, {
                name        : user.name         || '',
                email       : user.email        || '',
                biography   : user.biography    || '',
                location    : user.location     || '',
                website     : user.website      || '',
                avatar_url  : user.avatar_url   || '',
                cover_url   : user.cover_url    || ''
            })
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

(function() {
    'use strict';

    angular.module('journal.shared.auth')
        .service('AuthService', ['$http', '$q', 'StorageService', 'CONFIG', AuthService]);

    function AuthService($http, $q, StorageService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.login = function(user, token) {
            // save the user details
            StorageService.set('user', user);

            // save the token details
            StorageService.set('token', token);
        };

        this.logout = function() {
            StorageService.remove('user');
            StorageService.remove('token');
        };

        this.token = function() {
            return StorageService.get('token');
        };

        this.update = function(key, value) {
            return StorageService.set(key, value);
        };

        this.user = function() {
            return StorageService.get('user');
        };

        this.validateInstallation = function() {

        };

        this.validateToken = function() {
            var deferred = $q.defer();

            $http.get(this.apiUrl + '/auth/check?token=' + this.token())
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

(function() {
    'use strict';

    angular.module('journal.shared.deletePostModal')
        .service('DeletePostModalService', [
            '$http', '$q', 'AuthService', 'CONFIG', DeletePostModalService]);

    function DeletePostModalService($http, $q, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        /**
         * Sends a request to delete a post.
         *
         * @param postId
         * @returns {*}
         */
        this.deletePost = function(postId) {
            var deferred = $q.defer(),
                token = AuthService.token(),
                postId = postId || '';

            $http.delete(this.apiUrl + '/posts/delete?post_id=' + postId + '&token='+token)
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

(function() {
    'use strict';

    angular.module('journal.shared.fileUploader')
        .service('FileUploaderService', ['Upload', 'CONFIG', FileUploaderService]);

    function FileUploaderService(Upload, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.upload = function(file) {
            return Upload.upload({
                url : this.apiUrl + '/upload',
                file : file
            });
        }
    }
})();
(function() {
    'use strict';

    angular.module('journal.shared.storage')
        .service('StorageService', ['localStorageService', StorageService]);

    /**
     * Ease the usage of the Local Storage.
     *
     * @param localStorageService
     * @constructor
     */
    function StorageService(localStorageService) {
        /**
         * Save the data to the local storage.
         *
         * @param key
         * @param value
         * @returns {*}
         */
        this.set = function(key, value) {
            return localStorageService.set(key, value);
        };

        /**
         * Fetches a saved data from the local storage.
         *
         * @param key
         * @returns {*}
         */
        this.get = function(key) {
            return localStorageService.get(key);
        };

        /**
         * Removes a data from the local storage.
         *
         * @param key
         * @returns {*}
         */
        this.remove = function(key) {
            return localStorageService.remove(key);
        };
    }
})();
(function() {
    'use strict';

    angular.module('journal.shared.toastr')
        .service('ToastrService', ['toastr', ToastrService]);

    function ToastrService(toastr) {
        this.toast = function(message, type) {
            switch (type) {
                case 'success':
                    toastr.success(message);
                    break;
                case 'info':
                    toastr.info(message);
                    break;
                case 'error':
                    toastr.error(message);
                    break;
                case 'warning':
                    toastr.warning('message');
                    break;
                default:
                    toastr.success(message);
                    break;
            }
        };
    }
})();