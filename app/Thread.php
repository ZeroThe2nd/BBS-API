<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int            $id
 * @property string         $title
 * @property int            $board_id
 * @property int            $user_id
 * @property mixed          $posts
 * @property mixed          $threads
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
    ];

    /** @var array */
    protected $dates = [];

    protected $casts = [
        "id"         => "integer",
        "board_id"   => "integer",
        "created_by" => "integer",
        "updated_by" => "integer",
        "deleted_by" => "integer",
    ];

    /** @var array */
    public static $rules = [
        "title"      => "required|string",
        "board_id"   => "required|numeric",
        "created_by" => "required|numeric",
        //        "updated_by"  => "numeric",
        //        "deleted_by"  => "numeric",
    ];

    /**
     * Get posts in thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany("App\Post")->with(['created_by', 'updated_by']);
    }

    /**
     * Get parent threads
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function board()
    {
        return $this->belongsTo('App\Board');
    }

    /**
     * Get parent threads
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function deleted_by()
    {
        return $this->belongsTo('App\User', 'deleted_by', 'id');
    }
}
