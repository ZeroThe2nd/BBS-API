<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property mixed          $thread
 * @property mixed          $user
 */
class Post extends Model
{
    use SoftDeletes;

    /** @var array */
    protected $fillable = [
        "content",
        "thread_id",
        "user_id",
    ];

    protected $casts = [
        "id"         => "integer",
        "thread_id"  => "integer",
        "created_by" => "integer",
        "updated_by" => "integer",
        "deleted_by" => "integer",
    ];

    /** @var array */
    protected $dates = [];

    /** @var array */
    public static $rules = [
        "content"    => "required|string",
        "thread_id"  => "required|numeric",
        "created_by" => "required|integer",
    ];

    /**
     * Get the associated user
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

    /**
     * Get parent thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo('App\Thread', 'thread_id', 'id');
    }
}
