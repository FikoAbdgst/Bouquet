<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $bestSellers = Product::topSellers(3);

        return view('welcome', compact('bestSellers'));
    }
}
