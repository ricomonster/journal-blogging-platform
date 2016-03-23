module.exports = {
  base : {
    bower : './bower_components',
    build : './resources/build',
    public : './public',
    vendor : './public/vendor'
  },
  src : {
    stylesheets : [
      './resources/src/css/normalize.css',
      './resources/src/css/buttons.css',
      './resources/src/css/forms.css',
      './resources/src/css/typography.css',
      './resources/src/css/elements.css',
      './resources/src/css/**/*.css',
      './resources/src/css/**/**/*.css'
    ]
  }
};
