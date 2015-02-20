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

if ( ! function_exists('site_title')) {
    function site_title()
    {
        $response = Journal\Setting::where('key', '=', 'blog_title')->first();
        return $response->value;
    }
}
