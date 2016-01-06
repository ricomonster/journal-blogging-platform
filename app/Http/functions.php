<?php //-->
use \Michelf\MarkdownExtra;

/**
 * Converts the tags from the posts to HTML with href links
 *
 * @param Post Object
 * @param string
 * @return string
 */
if ( ! function_exists('get_post_tags')) {
    function get_post_tags($post, $delimiter = ',') {
        $tags = null;

        if ($post->tags) {
            // loop the tags
            foreach ($post->tags as $key => $tag) {
                $tags .= '<a href="'.url('/tag/'.$tag->slug).'">'.$tag->name.'</a>';
                $tags .= ($key < count($post->tags) - 1) ? $delimiter.' ' : null;
            }
        }

        return $tags;
    }
}

/**
 * Converts the published_at field of the post to readable date
 *
 * @param Post Object
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
        $theme = \DB::table('settings')->where('setting', '=', 'theme')
            ->first();

        return asset('themes/'.$theme->value.'/' . $file);
    }
}

/**
 * Sets the script to be used for Disqus
 */
if ( ! function_exists('disqus')) {
    function disqus()
    {
        $disqus = \DB::table('settings')->where('setting', '=', 'disqus')
            ->first();

        return $disqus->value;
    }
}

/**
 * Converts a Markdown text to HTML
 *
 * @return string
 */
if ( ! function_exists('markdown'))
{
    function markdown($str)
    {
        $parser = new MarkdownExtra;
        $parser->no_markup = false;

        return $parser->transform($str);
    }
}

/**
 * Trims the post content to make it like an excerpt of the post.
 *
 * @param Post Object
 * @param int
 * @return string
 */
if ( ! function_exists('excerpt')) {
    function excerpt($post, $limit = 100) {
        // check if there's an object markdown in the post parameter
        if (!$post->markdown) {
            // return empty
            return null;
        }

        // convert to markdown
        $markdowned = markdown($post->markdown);

        // remove the tags
        $excerpt = strip_tags($markdowned);

        if(str_word_count($excerpt) > $limit) {
            $limit = $limit + 1;
            $words = explode(' ', $excerpt, $limit);
            array_pop($words);
            $excerpt = implode(' ', $words) . '...';
        }

        return $excerpt;
    }
}
