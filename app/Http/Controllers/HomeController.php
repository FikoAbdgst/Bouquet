<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $bestSellers = Product::topSellers(3)->load('productCategory', 'primaryImage');
        $categories = Category::with('fields.fieldOptions')->orderBy('name')->get(['id', 'name', 'slug']);

        return view('welcome', compact('bestSellers', 'categories'));
    }
}
