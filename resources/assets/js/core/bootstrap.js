/**
 * Load Vue & Vue Resource
 */
if (window.Vue === undefined) {
    window.Vue = require('vue');
}

require('vue-resource');

Vue.http.headers.common['X-CSRF-TOKEN'] = Journal.csrfToken;

// enable debug mode
Vue.config.debug = true;

/*
 * Load jQuery and Bootstrap jQuery, used for front-end interaction.
 */
if (window.$ === undefined || window.jQuery === undefined) {
    window.$ = window.jQuery = require('jquery');
}

require('bootstrap-sass/assets/javascripts/bootstrap');

/**
 * Load CodeMirror
 */
if (window.CodeMirror === undefined) {
    window.CodeMirror = require('codemirror');
}

/**
 * Load Showdown
 */
if (window.showdown === undefined) {
    window.showdown = require('showdown');
}

/**
 * Load Toastr
 */
if (window.toastr === undefined) {
    window.toastr = require('toastr');
}

/*
 * Load Moment.js, used for date formatting and presentation.
 */
if (window.moment === undefined) {
    window.moment = require('moment');
}

/**
 * Load Selectize JS
 */
require('selectize');

// set some global options for the toastr
toastr.options.positionClass = 'toast-bottom-left';
toastr.options.newestOnTop = false;
toastr.options.timeOut = 10000;
toastr.options.preventDuplicates = true;
toastr.options.closeMethod = 'fadeOut';
toastr.options.closeEasing = 'swing';
