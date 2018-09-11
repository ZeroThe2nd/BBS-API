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

    protected $with = ['user'];

    /** @var array */
    protected $dates = [];

    /** @var array */
    public static $rules = [
        "content"   => "required|string",
        "thread_id" => "required|numeric",
    ];

    protected $casts = [
        'user_id'   => 'integer',
        'thread_id' => 'integer',
    ];

    /**
     * Get the associated user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo("App\User");
    }

    /**
     * Get parent thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }
}
