<?php

namespace Sneefr\Http\Controllers;

use Sneefr\Http\Requests;
use Sneefr\Models\Category;

class ItemsController extends Controller
{
    /**
     * Display the form to create a new item.
     */
    public function create()
    {
        $categories = Category::getTree();

        return view('ad.create', compact('categories'));
    }
}
