<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int            $id
 * @property mixed          $threads
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Board extends Model
{
    use SoftDeletes;

    /** @var array */
    protected $fillable = [
        "title",
        "description",
        "user_id",
    ];

    /** @var array */
    protected $dates = [];

    /** @var array */
    public static $rules = [
        "title"       => "required|string",
        "description" => "required|string",
        "user_id"     => "required|numeric",
    ];

    /**
     * Get threads in board
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany("App\Thread");
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
