<?php //-->
namespace Journal;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {
    /**
     * Table associated with the model
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value'];
}
