module.exports = {
    base : {
        bower : './bower_components',
        public : './public',
        src : './resources/src',
        themes : './themes'
    },
    sources : {
        app : [
            './resources/src/app.module.js',
            './resources/src/app.config.js',
            './resources/src/app.routes.js',
            './resources/src/app.run.js',
            './resources/src/app.constants.js'
        ],
        build : [
            './resources/build/app.js',
            './resources/build/controllers.js',
            './resources/build/directives.js',
            './resources/build/providers.js',
            './resources/build/services.js'
        ],
        css : [
            './resources/src/css/normalize.css',
            './resources/src/css/utilities.css',
            './resources/src/css/buttons.css',
            './resources/src/css/forms.css',
            './resources/src/css/typography.css',
            './resources/src/components/**/*.css',
            './resources/src/shared/**/*.css'
        ],
        components : {
            controllers : './resources/src/components/**/*Controller.js',
            services : './resources/src/components/**/*Service.js',
            directives : './resources/src/components/**/*Directive.js',
            providers : './resources/src/components/**/*Provider.js',
            templates : './resources/src/components/**/*.html',
            css : './resources/src/components/**/*.css'
        },
        shared : {
            controllers : './resources/src/shared/**/*Controller.js',
            services : './resources/src/shared/**/*Service.js',
            directives : './resources/src/shared/**/*Directive.js',
            providers : './resources/src/shared/**/*Provider.js',
            templates : './resources/src/shared/**/*.html',
            css : './resources/src/shared/**/*.css'
        }
    },
    destination : {
        build : './resources/build',
        templates : './public/assets/templates',
        vendor : {
            css : './public/vendor/css',
            js : './public/vendor/js',
            fonts : './public/vendor/fonts'
        },
        assets : {
            css : './public/assets/css',
            js : './public/assets/js'
        },
        vendorBase : './public/vendor'
    }
};
