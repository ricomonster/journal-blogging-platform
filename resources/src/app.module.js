(function() {
    'use strict';

    angular.module('Journal', [
        // APP
        'journal.config',
        'journal.constants',
        'journal.routes',
        'journal.run',
        // COMPONENTS
        'journal.components.editor',
        'journal.components.login',
        //'journal.components.post',
        'journal.components.postLists',
        //'journal.components.settings',
        'journal.components.settingsGeneral',
        'journal.components.settingsGeneralModal',
        'journal.components.sidebar',
        //'journal.components.user',
        'journal.components.userCreate',
        'journal.components.userLists',
        'journal.components.userProfile',
        'journal.components.userProfileModal',
        // SHARED
        'journal.shared.auth',
        'journal.shared.fileUploader',
        'journal.shared.markdownReader',
        'journal.shared.storage',
        'journal.shared.toastr',
        // DEPENDENCIES
        'angular-ladda']);

    // APP
    angular.module('journal.config', ['LocalStorageModule', 'toastr']);
    angular.module('journal.constants', []);
    angular.module('journal.routes', ['ui.router', 'journal.constants']);
    angular.module('journal.run', ['ngProgressLite']);

    // COMPONENTS
    // Editor
    angular.module('journal.components.editor', ['ngFileUpload', 'ui.bootstrap', 'ui.codemirror']);

    // Login
    angular.module('journal.components.login', []);

    // Posts
    angular.module('journal.components.post', []);
    angular.module('journal.components.postLists', []);

    // Settings
    angular.module('journal.components.settings', []);
    angular.module('journal.components.settingsGeneral', []);
    angular.module('journal.components.settingsGeneralModal', []);

    // Sidebar
    angular.module('journal.components.sidebar', []);

    // Users
    angular.module('journal.components.user', []);
    angular.module('journal.components.userCreate', []);
    angular.module('journal.components.userLists', ['angularMoment']);
    angular.module('journal.components.userProfile', []);
    angular.module('journal.components.userProfileModal', []);

    // SHARED
    angular.module('journal.shared.auth', []);
    angular.module('journal.shared.fileUploader', ['ngFileUpload']);
    angular.module('journal.shared.markdownReader', []);
    angular.module('journal.shared.storage', ['LocalStorageModule']);
    angular.module('journal.shared.toastr', ['ngAnimate', 'toastr']);
})();
