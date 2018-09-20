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
    ];

    protected $casts = [
        "id"         => "integer",
        "created_by" => "integer",
        "updated_by" => "integer",
        "deleted_by" => "integer",
    ];

    /** @var array */
    protected $dates = [];

    /** @var array */
    public static $rules = [
        "title"       => "required|string",
        "description" => "required|string",
        "created_by"  => "required|numeric",
    ];

    /**
     * Get threads in threads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany("App\Thread")->with(['created_by', 'updated_by']);
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
