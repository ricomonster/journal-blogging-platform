(function() {
    'use strict';

    angular.module('Journal', [
        // APP
        'journal.config',
        'journal.constants',
        'journal.routes',
        'journal.run',
        // COMPONENTS
        'journal.components.login',
        'journal.components.post',
        'journal.components.postLists',
        'journal.components.sidebar',
        // SHARED
        'journal.shared.auth',
        'journal.shared.markdownReader',
        'journal.shared.storage',
        'journal.shared.toastr']);

    // APP
    angular.module('journal.config', ['LocalStorageModule', 'toastr']);
    angular.module('journal.constants', []);
    angular.module('journal.routes', ['ui.router', 'journal.constants']);
    angular.module('journal.run', []);

    // COMPONENTS
    // Login
    angular.module('journal.components.login', []);

    // Posts
    angular.module('journal.components.post', []);
    angular.module('journal.components.postLists', []);

    // Sidebar
    angular.module('journal.components.sidebar', []);

    // SHARED
    angular.module('journal.shared.auth', []);
    angular.module('journal.shared.markdownReader', []);
    angular.module('journal.shared.storage', ['LocalStorageModule']);
    angular.module('journal.shared.toastr', ['ngAnimate', 'toastr']);
})();