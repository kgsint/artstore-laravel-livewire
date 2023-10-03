<?php

namespace App\Http\Controllers;

class CategoryIndexController extends Controller
{
    public function __invoke()
    {
        return view('categories.index');
    }
}
