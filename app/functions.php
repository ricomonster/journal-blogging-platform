<?php //-->
use \Michelf\MarkdownExtra;

/**
 * Converts the tags from the posts to HTML with href links
 *
 * @param array
 * @return string
 */
if ( ! function_exists('get_post_tags')) {
    function get_post_tags($post) {
        $tags = null;

        if ($post->tags) {
            // loop the tags
            foreach ($post->tags as $key => $tag) {
                $tags .= '<a href="/tag/'.$tag->slug.'">'.$tag->name.'</a>';
                $tags .= ($key < count($post->tags) - 1) ? ',' : null;
            }
        }

        return $tags;
    }
}

/**
 * Converts the published_at field of the post to readable date
 *
 * @param array
 * @param string
 * @return mixed
 */
if ( ! function_exists('post_date_time')) {
    function post_date_time($post, $format = 'F d, Y') {
        if (!$post || !$post->published_at) {
            return null;
        }

        return date($format, $post->published_at);
    }
}

/**
 * Sets the path url of a file or assets
 *
 * @param string
 * @return string
 */
if ( ! function_exists('theme_assets')) {
    function theme_assets($file = null)
    {
        // get the theme
        return asset('themes/casper/' . $file);
    }
}

/**
 * Converts a Markdown text to HTML
 *
 * @return string
 */
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
