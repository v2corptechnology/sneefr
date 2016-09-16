<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reports';

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
    protected $fillable = ['user_id', 'reportable_id', 'reportable_type'];

    public function reportable()
    {
        return $this->morphTo();
    }
}
