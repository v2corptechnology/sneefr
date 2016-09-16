<?php namespace Sneefr\Models;

use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;
use Illuminate\Database\Eloquent\Model;

class PlaceName extends Model
{
    use AlgoliaEloquentTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'place_names';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes we can mass assign.
     *
     * @var array
     */
    protected $fillable = ['id', 'place_id', 'language', 'name', 'formatted_address', 'address_components'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'address_components' => 'array',
    ];

    /*
     * Algolia related config
     */

    /**
     * Set whether or not Algolia has to auto-index
     * models when they are saved.
     *
     * @var bool
     */
    public static $autoIndex = true;

    /**
     * Set wheter or not Algolia creates different indexes
     * based on the environments. ex: modelName_{App::environnement()}
     *
     * @var bool
     */
    public static $perEnvironment = true;

    public $algoliaSettings = [
        'attributesToIndex' => [
            'name',
            'formatted_address',
        ],
        'customRanking' => [
            'asc(name)',
        ],
    ];

    /**
     * Select the data passed to algolia.
     *
     * @return array
     */
    public function getAlgoliaRecord()
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'formatted_address' => $this->formatted_address,
            'language'          => $this->language,
            'place_id'          => $this->place_id,
            'created_at'        => $this->created_at->timestamp,
            'updated_at'        => $this->updated_at->timestamp,
        ];
    }

    /**
     * Relationship to the place this name refers to.
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Get the short name of this place name.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the long name of this place name.
     *
     * @return string
     */
    public function getLongName() : string
    {
        return $this->formatted_address;
    }

}
