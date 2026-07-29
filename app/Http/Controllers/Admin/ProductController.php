<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('primaryImage', 'productCategory')->latest()->paginate(10);
        $bestSellerIds = Product::topSellers(3)->pluck('id');
        return view('admin.products.index', compact('products', 'bestSellerIds'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'category' => $validated['category'] ?? null,
                'category_id' => $validated['category_id'],
                'stock' => $validated['stock'],
                'is_active' => true,
            ]);

            if ($request->hasFile('images')) {
                $primaryIndex = $request->input('primary_image_index', 0);

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_primary' => ($index === $primaryIndex),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $product->load('images', 'productCategory');
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'primary_image_index' => ['nullable', 'integer'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'category' => $validated['category'] ?? null,
                'category_id' => $validated['category_id'],
                'stock' => $validated['stock'],
                'is_active' => $request->boolean('is_active', true),
            ]);

            if (!empty($validated['delete_images'])) {
                $imagesToDelete = $product->images()->whereIn('id', $validated['delete_images'])->get();
                foreach ($imagesToDelete as $img) {
                    \Storage::disk('public')->delete($img->image_url);
                    $img->delete();
                }
            }

            if ($request->hasFile('images')) {
                $existingCount = $product->images()->count();
                $primaryIndex = $request->input('primary_image_index');

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    $isPrimary = ($primaryIndex !== null && ($existingCount + $index) == $primaryIndex);

                    if ($isPrimary) {
                        $product->images()->update(['is_primary' => false]);
                    }

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'is_primary' => $isPrimary,
                    ]);
                }
            }

            if ($request->has('primary_image_index') && !$request->hasFile('images')) {
                $product->images()->update(['is_primary' => false]);
                $allImages = $product->images()->get();
                $primaryIdx = $request->input('primary_image_index');
                if (isset($allImages[$primaryIdx])) {
                    $allImages[$primaryIdx]->update(['is_primary' => true]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dinonaktifkan.');
    }

    public function restore(Product $product)
    {
        $product->update(['is_active' => true]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diaktifkan kembali.');
    }
}
