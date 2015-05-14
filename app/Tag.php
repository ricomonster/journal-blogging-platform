<?php //-->
namespace Journal;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package Journal
 */
class Tag extends Model
{
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tag', 'slug'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'id', 'updated_at'];

    /**
     * Post relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function posts()
    {
        return $this->belongsToMany('Journal\Post', 'post_tag', 'tag_id', 'post_id');
    }
}
