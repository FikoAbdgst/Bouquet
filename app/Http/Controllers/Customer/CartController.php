<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $productIds = array_keys($cart);
        $products = Product::active()
            ->whereIn('id', $productIds)
            ->with('primaryImage')
            ->get()
            ->keyBy('id');

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            if (isset($products[$productId])) {
                $product = $products[$productId];
                $lineTotal = $product->price * $quantity;
                $subtotal += $lineTotal;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        if (!$product->is_active || $product->stock <= 0) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = $this->getCart();

        $existingQty = $cart[$product->id] ?? 0;
        $newQty = $existingQty + $quantity;

        if ($newQty > $product->stock) {
            $newQty = $product->stock;
        }

        $cart[$product->id] = $newQty;
        $this->saveCart($cart);

        return back()->with('success', '"' . $product->name . '" ditambahkan ke keranjang.');
    }

    public function remove($productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
