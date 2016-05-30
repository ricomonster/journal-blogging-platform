<?php //-->
/**
 * All of the helpers that Journal needs are registered here. User can also
 * add their own by putting their helper below the file or create a new
 * file and register it app/Providers/JournalServiceProvider.php
 */
use \Michelf\MarkdownExtra;

/**
 * Wraps the Auth::user() to a method.
 *
 * @return Auth
 */
if (!function_exists('auth_user')) {
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
if (!function_exists('blog_title')) {
    function blog_title()
    {
        $settings = \DB::table('settings')
            ->where('name', 'blog_title')
            ->first();

        return (count($settings) > 0) ?
            $settings->value : null;
    }
}

/**
 * Description of the blog, duh.
 *
 * @return string
 */
if (!function_exists('blog_description')) {
    function blog_description()
    {
        $settings = \DB::table('settings')
            ->where('name', 'blog_description')
            ->first();

        return (count($settings) > 0) ?
            $settings->value : null;
    }
}

/**
 * Returns the cover url for the blog.
 *
 * @return string|void
 */
if (!function_exists('cover_photo')) {
    function cover_photo()
    {
        $settings = \DB::table('settings')
            ->where('name', 'cover_url')
            ->first();

        return (count($settings) > 0) ?
            $settings->value : null;
    }
}

/**
 * Returns the logo url for the blog.
 *
 * @return string|void
 */
if (!function_exists('logo_photo'))
{
    function logo_photo()
    {
        $settings = \DB::table('settings')
            ->where('name', 'logo_url')
            ->first();

        return (count($settings) > 0) ?
            $settings->value : null;
    }
}

/**
 * Wraps the logo image in an a href tag
 *
 * @param   string $url
 * @param   string|null $class
 * @param   string|null $alt
 * @return  string|null
 */
if (!function_exists('blog_logo_photo'))
{
    function blog_logo_photo($url = '/', $class = null, $alt = null)
    {
        // check if the url is empty
        if (empty($url)) {
            // set the homepage as the url
            $url = url('/');
        }

        // check if there's a logo
        $logoUrl = logo_photo();

        if (empty($logoUrl)) {
            return null;
        }

        return sprintf(
            '<a href="%s" class="blog-logo %s"><img src="%s" alt="%s"/></a>',
            $url,
            $class,
            $logoUrl,
            $alt);
    }
}

/**
 * Checks if the current url is the same or similar to the given keyword and
 * returns a boolean to confirm it.
 *
 * @param   string $keyword
 * @return  boolean
 */
if (!function_exists('is_active_menu')) {
    function is_active_menu($keyword)
    {
        return (Request::path() === $keyword);
    }
}

/**
 * Helper function to easily set the path of the file or asset to be used.
 *
 * @param   string $filePath
 * @return  string
 */
if (!function_exists('theme_asset')) {
    function theme_asset($filePath)
    {
        // get the active theme
        $settings = \DB::table('settings')
            ->where('name', 'theme_template')
            ->first();

        return asset('themes/'.$settings->value . '/' . $filePath);
    }
}

/**
 * Converts the markdown content to HTML
 *
 * @param   string $markdown
 * @return  string
 */
if (!function_exists('markdown')) {
    function markdown($markdown)
    {
        $parser = new MarkdownExtra;
        $parser->no_markup = false;

        return $parser->transform($markdown);
    }
}

/**
 * Strips the content of the post to make an excerpt.
 *
 * @param   string  $post
 * @param   int     $limit
 * @return  string
 */
if (!function_exists('excerpt')) {
    function excerpt($post, $limit = 50) {
        // check if there's an object content in the post parameter
        if (!$post->content) {
            // return empty
            return null;
        }

        // convert to markdown
        $markdowned = markdown($post->content);

        // remove the tags
        $excerpt = strip_tags($markdowned);

        if (str_word_count($excerpt) > $limit) {
            $limit = $limit + 1;
            $words = explode(' ', $excerpt, $limit);
            array_pop($words);
            $excerpt = implode(' ', $words) . '...';
        }

        return $excerpt;
    }
}

/**
 * Renders the post tags in a better fashion. :)
 *
 * @param   array   $tags
 * @param   string  $delimiter
 * @param   boolean $convertToLink
 * @return  string
 */
if (!function_exists('post_tags')) {
    function post_tags($tags, $delimiter = ',', $convertToLink = true)
    {
        // check if it is empty
        if (empty($tags)) {
            return false;
        }

        // initialize this
        $tagString = '';

        // loop the tags
        foreach ($tags as $key => $tag) {
            // check if this is the last tag to be looped
            $delimiter = (count($tags) == $key + 1) ? '' : $delimiter;

            // check first if we need it to convert to a link or not
            $tagString .= ($convertToLink) ?
                    // format it!
                    sprintf(
                        '<a href="%s">%s</a>%s&nbsp',
                        url('tag/' . $tag->slug),
                        $tag->title,
                        $delimiter) :
                    // just bring plain old text
                    $tag->title . $delimiter;
        }

        return $tagString;
    }
}

/**
 * Returns the list of navigation menu
 *
 * @return null|array
 */
if (!function_exists('navigation_menu')) {
    function navigation_menu()
    {
        // get the saved navigation
        $settings = \DB::table('settings')
            ->where('name', 'navigation')
            ->first();

        if (empty($settings)) {
            return [];
        }

        // convert the value to an array because it is saved in JSON and return
        // it back.
        return json_decode($settings->value);
    }
}
