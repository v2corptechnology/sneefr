<?php namespace Sneefr\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
    protected $fillable = ['name', 'child_of'];

    public static function getTree()
    {
        $all = self::all()->pluck('child_of', 'id')->map(function ($item, $key) {
            return [
                'id'       => $key,
                'child_of' => (int) $item,
                'name'     => trans("category.{$key}"),
            ];
        })->groupBy(function ($item, $key) {
            return $item['child_of'];
        });

        return $all->get(0)->mapWithKeys(function ($item) {
            return [$item['name'] => $item['id']];
        })->sortBy(function($item, $key) {
            return $key;
        })->map(function($item, $key) use ($all) {
            return $all->get($item)->sortBy('name')->pluck('name', 'id');
        })
            ->prepend(trans('ad_form.create.category_placeholder'))
            ->toArray();
    }

    public function scopeParent($query)
    {
        return $query->whereNull('child_of');
    }

    public function childrens()
    {
        return $this->hasMany(self::class, 'child_of');
    }


    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_categorie');
    }
}
