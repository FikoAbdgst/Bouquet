<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    public function index()
    {
        return view('customer.cart');
    }

    public function checkStock(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        $products = Product::whereIn('id', $request->ids)
            ->get(['id', 'name', 'price', 'stock', 'is_active']);

        return response()->json($products);
    }

    public function uploadTemp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $path = $request->file('file')->store('temp-uploads', 'public');

        return response()->json([
            'path' => $path,
        ]);
    }

    public function editFields(Product $product): JsonResponse
    {
        if (!$product->is_active || $product->stock <= 0) {
            return response()->json(['error' => 'Produk tidak tersedia.'], 404);
        }

        $product->load('productCategory.fields.fieldOptions', 'primaryImage');

        return response()->json([
            'id'    => $product->id,
            'name'  => $product->name,
            'price' => $product->price,
            'stock' => $product->stock,
            'image' => $product->primaryImage ? Storage::url($product->primaryImage->image_url) : '',
            'fields' => $product->productCategory?->fields->map(function ($field) {
                $options = $field->fieldOptions->map(function ($o) {
                    return ['id' => $o->id, 'name' => $o->name, 'price' => $o->price];
                });

                if ($options->isEmpty() && $field->options) {
                    $options = collect(array_map('trim', explode(',', $field->options)))->map(function ($name) {
                        return ['id' => null, 'name' => $name, 'price' => 0];
                    });
                }

                return [
                    'id'          => $field->id,
                    'label'       => $field->label,
                    'type'        => $field->type,
                    'is_required' => $field->is_required,
                    'options'     => $options->values()->toArray(),
                ];
            }) ?? [],
        ]);
    }
}
