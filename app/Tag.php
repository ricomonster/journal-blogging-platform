<?php

namespace Journal;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * The attributes/accessors that will be accessed via JSON or array
     *
     * @var array
     */
    protected $appends = ['created_at', 'updated_at'];

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
     * Post/Tag Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany('Journal\Post', 'posts_tags', 'tag_id', 'post_id');
    }
}
