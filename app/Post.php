<?php //-->
namespace Journal;

use Illuminate\Database\Eloquent\Model;
use Request;

class Post extends Model {
    /**
     * Table associated with the model
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['author_id', 'title', 'markdown', 'slug', 'status',
        'active', 'published_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['tag'];

    /**
     * The attributes/accessors that will be accessed via JSON or array
     *
     * @var array
     */
    protected $appends = ['tags', 'meta'];

    /**
     * User/Author Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('Journal\User', 'author_id');
    }

    /**
     * Tags Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tag()
    {
        return $this->belongsToMany('Journal\Tag', 'post_tag', 'post_id', 'tag_id');
    }

    public function getMetaAttribute()
    {
        return [
            'meta_title' => $this->attributes['title']
        ];
    }

    /**
     * Creates the permalink of a post
     *
     * @return string
     */
    public function getPermalinkAttribute()
    {
        return Request::root().'/post/'.$this->attributes['slug'];
    }

    /**
     * Customized the structure for the tags
     *
     * @return array
     */
    public function getTagsAttribute()
    {
        // get tags
        $postTags = $this->relations['tag'];

        // check if there
        if(!isset($postTags)) {
            return [];
        }

        $tagValues = [];
        // check if tags is not empty
        if(!empty($postTags)) {
            $tagValues = array();
            foreach($postTags as $key => $postTag) {
                $tagValues[] = $postTag->tag;
            }
        }

        return $tagValues;
    }
}
