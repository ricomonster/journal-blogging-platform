<?php //-->

/**
 * Returns if the variable is the current route
 *
 * @param $menu
 * @return string
 */
if ( ! function_exists('header_menu'))
{
    function header_menu($menu)
    {
        $currentRoute = Route::getCurrentRoute()->getPath();

        if (strpos($currentRoute,$menu) !== false) {
            echo 'active';
        }
    }
}

/**
 * Converts a Markdown text to HTML
 *
 * @return string
 */
use \Michelf\MarkdownExtra;

if ( ! function_exists('markdown'))
{
    function markdown($str, $trim = false, $limit = 100)
    {
        $parser = new MarkdownExtra;
        $parser->no_markup = false;
        // check we wanted to trim the string
        if($trim) {
            $text = strip_tags($parser->transform($str));
            if(str_word_count($text) > $limit) {
                $limit = $limit+1;
                $words = explode(' ', $text, $limit);
                array_pop($words);
                $text = implode(' ', $words) . '...';
            }

            return $text;
        }

        return $parser->transform($str);
    }
}

/**
 * Converts datetime to normal human readable time (eg. 45 minutes ago)
 */
use Carbon\Carbon;

if ( ! function_exists('convert_readable_time')) {
    function convert_readable_time($dateTime)
    {
        // check if date is greater than 1 week
        if(strtotime($dateTime) < strtotime('-1 week')) {
            return date('M d, Y', strtotime($dateTime));
        }

        return Carbon::createFromTimeStamp(strtotime($dateTime))->diffForHumans();
    }
}

if ( ! function_exists('theme_path')) {
    function theme_path($file = null)
    {
        // get the theme
        $theme = Journal\Setting::where('key', '=', 'theme')->first();
        return asset('themes/' . $theme->value . '/' . $file);
    }
}

/**
 * Shows the blog title
 */
if ( ! function_exists('site_title')) {
    function site_title()
    {
        $response = Journal\Setting::where('key', '=', 'blog_title')->first();
        return $response->value;
    }
}
