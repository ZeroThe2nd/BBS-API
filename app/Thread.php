<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int            $id
 * @property string         $title
 * @property int            $board_id
 * @property int            $user_id
 * @property mixed          $posts
 * @property mixed          $board
 * @property mixed          $user
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Thread extends Model
{
    use SoftDeletes;

    /** @var array */
    protected $fillable = [
        "title",
        "board_id",
        "user_id",
    ];

    /** @var array */
    protected $dates = [];

    /** @var array */
    public static $rules = [
        "title"    => "required|string",
        "board_id" => "required|numeric",
        "user_id"  => "required|numeric",
    ];

    /**
     * Get posts in thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany("App\Post");
    }

    /**
     * Get parent board
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    /**
     * Get parent board
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
