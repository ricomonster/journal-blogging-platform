<?php

namespace Journal;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'biography', 'avatar_url', 'cover_url',
        'location', 'website', 'slug', 'login_at', 'active'];

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
     * Makes the author permalink
     *
     * @return string
     */
    public function getPermalinkAttribute()
    {
        return '/author/'.$this->attributes['slug'];
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
     * Post Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('Journal\Post');
    }
}
