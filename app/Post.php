<?php

namespace Journal;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['author_id', 'title', 'markdown', 'featured_image',
        'slug', 'status', 'active', 'published_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes/accessors that will be accessed via JSON or array
     *
     * @var array
     */
    protected $appends = ['created_at', 'permalink', 'updated_at'];

    /**
     * Converts created date/time to timestamp
     *
     * @return int
     */
    public function getCreatedAtAttribute()
    {
        return strtotime($this->attributes['created_at']);
    }

    /**
     * Converts updated date/time to timestamp
     *
     * @return int
     */
    public function getUpdatedAtAttribute()
    {
        return strtotime($this->attributes['updated_at']);
    }

    /**
     * User/Post Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo('Journal\User', 'author_id');
    }

    public function getPermalinkAttribute()
    {
        return url($this->attributes['slug']);
    }

    /**
     * Post/Tag Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('Journal\Tag', 'post_tags', 'post_id', 'tag_id');
    }
}
