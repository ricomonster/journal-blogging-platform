<?php //-->
namespace Journal;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Request;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes/accessors that will be accessed via JSON or array
     *
     * @var array
     */
    protected $appends = array('avatar_url');

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['email', 'password', 'name', 'biography', 'website', 'location',
		'avatar_url', 'cover_url', 'role', 'active', 'slug', 'last_login'];

	/**
	 * Post relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function posts()
	{
		return $this->hasMany('Journal\Post');
	}

    /**
     * Check if avatar url is empty, if empty it shows the default avatar
     *
     * @return string
     */
    public function getAvatarUrlAttribute() {
        return (empty($this->attributes['avatar_url'])) ?
            Request::root() . '/images/shared/default_avatar.png' :
            $this->attributes['avatar_url'];
    }
}
