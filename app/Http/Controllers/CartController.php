<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems
            ->where('is_selected', true)
            ->sum(fn ($item) => $item->price * $item->quantity);

        $totalProduct = $cartItems
            ->where('is_selected', true)
            ->count();

        return view('cart.index', compact(
            'cartItems',
            'total',
            'totalProduct'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        $size = null;

        if (!empty($product->sizes) && is_array($product->sizes)) {
            $size = $product->sizes[array_rand($product->sizes)];
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
                'size'       => $size,
                'price'      => $product->price,
                'quantity'   => 1,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function destroy($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Produk dihapus dari keranjang');
    }

    // Checkbox
    public function toggle(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->update([
            'is_selected' => !$cart->is_selected
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // qty
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:increase,decrease',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($request->action === 'increase') {
            $cart->increment('quantity');
        }

        if ($request->action === 'decrease') {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                // qty == 1 → hapus cart
                $cart->delete();
            }
        }

        return back();
    }

    public function updateSize(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|string',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Cek
        $existing = Cart::where('user_id', Auth::id())
            ->where('product_id', $cart->product_id)
            ->where('size', $request->size)
            ->where('id', '!=', $cart->id)
            ->first();

        if ($existing) {
            // Gabungkan qty
            $existing->increment('quantity', $cart->quantity);
            $cart->delete();
        } else {
            // Update size
            $cart->update([
                'size' => $request->size,
            ]);
        }

        return back()->with('success', 'Ukuran produk berhasil diubah');
    }

}
