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
    protected $fillable = [
        'title', 'description', 'cover_image', 'slug', 'status',
        'active', 'published_at'];

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
    protected $appends = [];

    /**
     * Post/Tag Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany('Journal\Post', 'post_tags', 'tag_id', 'post_id');
    }
}
