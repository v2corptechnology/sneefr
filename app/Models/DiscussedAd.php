<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;
use Sneefr\Contracts\Entities\DiscussedAd as DiscussedAdContract;

class DiscussedAd extends Model implements DiscussedAdContract
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'discussion_ads';

    /**
     * @var array Fields that must be handled as date.
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array The attributes we can mass assign.
     */
    protected $fillable = ['discussion_id', 'ad_id'];

    /**
     * The discussion has one ad relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ad()
    {
        return $this->hasOne(Ad::class, 'id', 'ad_id');
    }

    /**
     * Get the discussed ad id.
     *
     * @return int
     */
    public function adId() : int
    {
        return (int) $this->ad_id;
    }
}
