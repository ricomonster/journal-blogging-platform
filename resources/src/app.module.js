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
        'journal.component.settings',
        'journal.component.settingsModal',
        'journal.component.userCreate',
        'journal.component.userLists',
        'journal.component.userProfile',
        'journal.component.userProfileModal',
        'journal.shared.auth',
        'journal.shared.fileUploader',
        'journal.shared.growl',
        'journal.shared.storage',
        'journal.shared.markdownConverter']);

    // app files
    angular.module('journal.config', ['angular-growl', 'LocalStorageModule']);
    angular.module('journal.constant', []);
    angular.module('journal.routes', ['ui.router']);
    angular.module('journal.run', ['journal.shared.auth', 'ngProgressLite']);

    // COMPONENTS
    angular.module('journal.component.deletePostModal', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.bootstrap',
        'ui.router']);

    angular.module('journal.component.login', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.router']);

    angular.module('journal.component.header', ['journal.shared.auth', 'ui.bootstrap']);

    // editor
    angular.module('journal.component.editor', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.markdownConverter',
        'journal.shared.growl',
        'ngFileUpload',
        'ngSanitize',
        'ui.bootstrap',
        'ui.codemirror',
        'ui.router']);

    // installer
    angular.module('journal.component.installer', ['ui.router']);
    angular.module('journal.component.installerStart', ['ui.router']);
    angular.module('journal.component.installerDetails', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.router']);
    angular.module('journal.component.installerSuccess', [
        'journal.shared.auth',
        'journal.shared.growl']);

    // post
    angular.module('journal.component.postLists', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'journal.shared.markdownConverter',
        'ui.router']);

    // settings
    angular.module('journal.component.settings', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.bootstrap',
        'ui.router']);
    angular.module('journal.component.settingsModal', [
        'journal.constant',
        'journal.component.settings',
        'journal.shared.auth',
        'journal.shared.fileUploader',
        'journal.shared.growl',
        'ngFileUpload',
        'ui.bootstrap',
        'ui.router']);

    // user
    angular.module('journal.component.userCreate', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.router']);
    angular.module('journal.component.userLists', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.router']);
    angular.module('journal.component.userProfile', [
        'journal.constant',
        'journal.shared.auth',
        'journal.shared.growl',
        'ui.bootstrap',
        'ui.router']);
    angular.module('journal.component.userProfileModal', [
        'journal.component.userProfile',
        'journal.shared.fileUploader',
        'journal.shared.growl',
        'ngFileUpload',
        'ui.bootstrap',
        'ui.router']);

    // SHARED
    angular.module('journal.shared.auth', ['journal.constant', 'journal.shared.storage']);
    angular.module('journal.shared.fileUploader', [
        'journal.constant',
        'journal.shared.auth',
        'ngFileUpload']);
    angular.module('journal.shared.growl', ['angular-growl']);
    angular.module('journal.shared.storage', ['LocalStorageModule']);
    angular.module('journal.shared.markdownConverter', ['ngSanitize']);
})();
