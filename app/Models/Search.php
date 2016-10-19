<?php

namespace Sneefr\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sneefr\Models\Traits\Likeable;
use Sneefr\Models\Traits\StaffFilterable;

class Search extends Model
{
    use Likeable;
    use SoftDeletes;
    use StaffFilterable;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'searches';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'body'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actions()
    {
        return $this->morphMany('Action', 'actionable');
    }

    /**
     * Get the searched content.
     *
     * @return string
     */
    public function body()
    {
        return (string) $this->body;
    }
}
