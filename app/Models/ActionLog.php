<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    use \Sneefr\Models\Traits\StaffFilterable;

    /**
     * Event name given when an ad was viewed by someone
     */
    const AD_VIEW = 'ad.view';

    /**
     * Event name given when a Person enters his session
     */
    const USER_LOGIN = 'user.login';

    /**
     * Event name given when a Person leaves his session
     */
    const USER_LOGOUT = 'user.logout';

    /**
     * Event name given when a Person search through the search form
     */
    const USER_SEARCH = 'user.search';

    /**
     * Event name given when a Person profile was viewed by someone
     */
    const PROFILE_VIEW = 'user.view';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'action_logs';

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'context'];

    /**
     * This method overrides the Eloquent model one
     * to avoid it to try to update the inexistant
     * updated_at column
     */
    public function setUpdatedAtAttribute($value)
    {
        // Do nothing.
    }

    /**
     * Inform Eloquent we have a relationship with one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
