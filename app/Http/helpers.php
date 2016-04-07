<?php //-->
/**
 * All of the helpers that Journal needs are registered here. User can also
 * add their own by putting their helper below the file or create a new
 * file and register it app/Providers/JournalServiceProvider.php
 */

/**
 * Wraps the Auth::user() to a method.
 *
 * @return Auth;
 */
if ( ! function_exists('auth_user')) {
    function auth_user()
    {
        return Auth::user();
    }
}

/**
 * Title of the blog, duh.
 *
 * @return string
 */
if ( ! function_exists('blog_title')) {
    function blog_title()
    {
        $settings = \DB::table('settings')
            ->where('name', 'blog_title')
            ->first();

        return (count($settings) > 0) ?
            $settings->value : null;
    }
}

if ( ! function_exists('is_active_menu')) {
    function is_active_menu($keyword)
    {
        // get the current url of the page
        $url = Request::url();

        return strpos($url, $keyword);
    }
}
