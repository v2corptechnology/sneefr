<?php namespace Sneefr\Support;

use Illuminate\Support\Collection;
use Sneefr\Models\Place;
use Sneefr\Models\Shop;
use Sneefr\Models\User;

class FollowCollection extends Collection
{
    public function users() : Collection
    {
        return $this->filter(function ($follow) {
            return $follow instanceof User;
        });
    }

    public function places() : Collection
    {
        return $this->filter(function ($follow) {
            return $follow instanceof Place;
        });
    }

    public function shops() : Collection
    {
        return $this->filter(function ($follow) {
            return $follow instanceof Shop;
        });
    }

    public function identifiers() : array
    {
        return $this->pluck('id')->all();
    }
}
