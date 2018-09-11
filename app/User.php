<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int            $id
 * @property string         $username
 * @property string         $password
 * @property string         api_token
 * @property bool           $is_admin
 * @property mixed          $posts
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class User extends Model
{

    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'avatar',
    ];

    /** @var array */
    protected $hidden = [
        'password',
        'api_token',
    ];

    /** @var array */
    protected $dates = [];

    /** @var array */
    public static $rules = [
        "username" => "required|string|filled|unique:users,username",
        "password" => "required|string|filled",
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function boards()
    {
        return $this->hasMany("App\Board");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany("App\Thread");
    }

    /**
     * Get posts by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany("App\Post");
    }
}
