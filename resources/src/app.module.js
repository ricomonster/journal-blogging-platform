(function() {
    'use strict';

    angular.module('Journal', [
        'journal.config',
        'journal.routes',
        'journal.run',
        'journal.constant',
        'journal.component.deletePostModal',
        'journal.component.login',
        'journal.component.header',
        'journal.component.editor',
        'journal.component.installer',
        'journal.component.installerDetails',
        'journal.component.installerStart',
        'journal.component.installerSuccess',
        'journal.component.postLists',
        'journal.component.services',
        'journal.component.settings',
        'journal.component.settingsModal',
        'journal.component.sidebar',
        'journal.component.userCreate',
        'journal.component.userLists',
        'journal.component.userProfile',
        'journal.component.userProfileModal',
        'journal.shared.auth',
        'journal.shared.buttonLoader',
        'journal.shared.fileUploader',
        'journal.shared.storage',
        'journal.shared.toastr',
        'journal.shared.markdownConverter',
        'angularMoment']);

    // app files
    angular.module('journal.config', ['LocalStorageModule', 'toastr']);
    angular.module('journal.constant', []);
    angular.module('journal.routes', ['ui.router']);
    angular.module('journal.run', ['journal.shared.auth', 'ngProgressLite']);

    // COMPONENTS
    angular.module('journal.component.deletePostModal', []);

    angular.module('journal.component.login', []);
    angular.module('journal.component.header', []);

    // editor
    angular.module('journal.component.editor', ['ngFileUpload', 'ngSanitize', 'ui.codemirror']);

    // installer
    angular.module('journal.component.installer', ['ui.router']);
    angular.module('journal.component.installerStart', ['ui.router']);
    angular.module('journal.component.installerDetails', ['ui.router']);
    angular.module('journal.component.installerSuccess', []);

    // post
    angular.module('journal.component.postLists', ['ui.router']);

    // services
    angular.module('journal.component.services', []);

    // settings
    angular.module('journal.component.settings', ['ui.bootstrap', 'ui.router']);
    angular.module('journal.component.settingsModal', [
        'ngFileUpload',
        'ui.bootstrap',
        'ui.router']);

    angular.module('journal.component.sidebar', [
        'ui.bootstrap',
        'ui.router']);

    // user
    angular.module('journal.component.userCreate', ['ui.router']);
    angular.module('journal.component.userLists', ['angularMoment', 'ui.router']);
    angular.module('journal.component.userProfile', ['ui.bootstrap', 'ui.router']);
    angular.module('journal.component.userProfileModal', [
        'ngFileUpload',
        'ui.bootstrap',
        'ui.router']);

    // SHARED
    angular.module('journal.shared.auth', []);
    angular.module('journal.shared.buttonLoader', []);
    angular.module('journal.shared.fileUploader', ['ngFileUpload']);
    angular.module('journal.shared.toastr', ['ngAnimate', 'toastr']);
    angular.module('journal.shared.storage', ['LocalStorageModule']);
    angular.module('journal.shared.markdownConverter', ['ngSanitize']);
})();
