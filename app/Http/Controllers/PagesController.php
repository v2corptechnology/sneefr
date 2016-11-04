<?php

namespace Sneefr\Http\Controllers;

class PagesController extends Controller
{
    public function help()
    {
        return view('pages.help');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }
}
