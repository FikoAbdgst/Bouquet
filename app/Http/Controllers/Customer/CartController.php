<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('customer.cart');
    }

    public function checkStock(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        $products = Product::whereIn('id', $request->ids)
            ->get(['id', 'name', 'price', 'stock', 'is_active']);

        return response()->json($products);
    }
}
