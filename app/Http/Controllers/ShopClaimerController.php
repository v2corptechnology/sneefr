<?php

namespace Sneefr\Http\Controllers;

use Illuminate\Http\Request;
use Sneefr\Models\Shop;

class ShopClaimerController extends Controller
{
    public function store(Shop $shop)
    {
        return $shop;
    }
}
