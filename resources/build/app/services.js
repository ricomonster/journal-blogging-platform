(function() {
    'use strict';

    angular.module('journal.component.deletePostModal')
        .service('DeletePostModalService', ['$http', 'AuthService', 'CONFIG', DeletePostModalService]);

    function DeletePostModalService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.deletePost = function(id) {
            var token = AuthService.getToken();

            return $http.post(this.apiUrl + '/posts/delete?token=' + token, {
                post_id : id
            });
        };
    }
})();

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
            var token       = AuthService.getToken(),
                url         = this.apiUrl + '/posts/save?token=' + token,
                authorId    = (post.author_id || ''),
                title       = (post.title || ''),
                markdown    = (post.markdown || ''),
                slug        = (post.slug || ''),
                status      = (post.status || 2),
                tags        = (post.tags || ''),
                publishedAt = (post.published_at || Math.floor(Date.now() / 1000));

            // check if post_id is set
            if (post.id) {
                url += '&post_id=' + post.id;
            }

            // send the request to the API
            return $http.post(url, {
                author_id : authorId,
                title : title,
                markdown : markdown,
                slug : slug,
                status : status,
                tags : tags,
                published_at : publishedAt});
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.installerDetails')
        .service('InstallerDetailsService', ['$http', 'CONFIG', InstallerDetailsService]);

    function InstallerDetailsService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createAccount = function(data) {
            return $http.post(this.apiUrl + '/installer/create_account', {
                email       : (data.email || ''),
                name        : (data.name || ''),
                password    : (data.password || ''),
                title       : (data.title || '')
            });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.login')
        .service('LoginService', ['$http', 'CONFIG', LoginService]);

    function LoginService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.authenticate = function(email, password) {
            return $http.post(this.apiUrl + '/auth/authenticate', {
                email : (email || ''),
                password : (password || '')
            });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.postLists')
        .service('PostListService', ['$http', 'CONFIG', PostListService]);

    function PostListService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getPosts = function() {
            return $http.get(this.apiUrl + '/posts/all');
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.services')
        .service('ServicesService', ['$http', 'AuthService', 'CONFIG', ServicesService]);

    function ServicesService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getServices = function(fields) {
            return $http.get(this.apiUrl + '/settings/get?fields=' + fields);
        };

        this.saveServices = function(settings) {
            var requests = {},
                token = AuthService.getToken();

            for (var s in settings) {
                requests[s] = settings[s];
            }

            return $http.post(this.apiUrl + '/settings/save?token=' + token, requests);
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.settings')
        .service('SettingsService', ['$http', 'AuthService', 'CONFIG', SettingsService]);

    function SettingsService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getSettings = function(fields) {
            return $http.get(this.apiUrl + '/settings/get?fields=' + fields);
        };

        this.saveSettings = function(settings) {
            var requests = {},
                token = AuthService.getToken();

            for (var s in settings) {
                requests[s] = settings[s];
            }

            return $http.post(this.apiUrl + '/settings/save?token=' + token, requests);
        };

        this.themes = function() {
            return $http.get(this.apiUrl + '/settings/themes');
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.sidebar')
        .service('SidebarService', ['$http', 'CONFIG', SidebarService]);

    function SidebarService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getSettings = function(fields) {
            return $http.get(this.apiUrl + '/settings/get?fields=' + fields);
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userCreate')
        .service('UserCreateService', ['$http', 'AuthService', 'CONFIG', UserCreateService]);

    function UserCreateService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.createUser = function(user) {
            var token = AuthService.getToken();

            return $http.post(this.apiUrl + '/users/create?token=' + token, {
                email       : (user.email || ''),
                password    : (user.password || ''),
                name        : (user.name || '')
            });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userLists')
        .service('UserListsService', ['$http', 'CONFIG', UserListsService]);

    function UserListsService($http, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getAllUsers = function() {
            return $http.get(this.apiUrl + '/users/all');
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.component.userProfile')
        .service('UserProfileService', ['$http', 'AuthService', 'CONFIG', UserProfileService]);

    function UserProfileService($http, AuthService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.getUser = function(userId) {
            return $http.get(this.apiUrl + '/users/get_user?user_id=' + userId);
        };

        this.updatePassword = function(data) {
            var token = AuthService.getToken(),
                userId = AuthService.user().id;

            return $http.post(this.apiUrl + '/users/change_password?token=' + token + '&user_id=' + userId, {
                old_password    : data.old_password || '',
                new_password    : data.new_password || '',
                repeat_password : data.repeat_password || ''
            });
        };

        this.updateUserDetails = function(data) {
            var token = AuthService.getToken(),
                userId = (data.id || '');

            return $http.post(this.apiUrl + '/users/update_details?token=' + token + '&user_id=' + userId, {
                name        : (data.name || ''),
                email       : (data.email || ''),
                biography   : (data.biography || ''),
                location    : (data.location || ''),
                website     : (data.website || ''),
                cover_url   : (data.cover_url || ''),
                avatar_url  : (data.avatar_url || '')
            });
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.shared.auth')
        .service('AuthService', ['$http', 'StorageService', 'CONFIG', AuthService]);

    function AuthService($http, StorageService, CONFIG) {
        this.apiUrl = CONFIG.API_URL;

        this.checkInstallation = function() {
            return $http.get(this.apiUrl + '/auth/check_installation');
        };

        this.checkToken = function() {
            return $http.get(this.apiUrl + '/auth/check?token=' + this.getToken());
        };

        this.getToken = function() {
            return StorageService.get('token');
        };
        
        this.login = function(user) {
            return StorageService.set('user', JSON.stringify(user));
        };

        this.logout = function() {
            // remove user
            StorageService.remove('user');
            // remove token
            StorageService.remove('token');
        };

        this.setToken = function(token) {
            return StorageService.set('token', token);
        };

        this.user = function() {
            return JSON.parse(StorageService.get('user'));
        };
    }
})();

(function() {
    'use strict';

    angular.module('journal.shared.fileUploader')
        .service('FileUploaderService', ['AuthService', 'Upload', 'CONFIG', FileUploaderService]);

    function FileUploaderService(AuthService, Upload, CONFIG) {
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

    function StorageService(localStorageService) {
        this.set = function(key, value) {
            return localStorageService.set(key, value);
        };

        this.get = function(key) {
            return localStorageService.get(key);
        };

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
