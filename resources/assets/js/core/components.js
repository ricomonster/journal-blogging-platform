/**
 * Load all the components
 */

require('./../components/installer/database');
require('./../components/installer/setup');
require('./../components/posts/list');
require('./../components/tags/details');
require('./../components/tags/list');
require('./../components/users/list');
require('./../components/users/profile');
require('./../components/editor');
require('./../components/login');
require('./../components/settings');

require('./../journal-components/markdown-reader');
require('./../journal-components/time-ago');

/**
 * Load Directives that most of the components used.
 */
require('./../directives/button-loader');
