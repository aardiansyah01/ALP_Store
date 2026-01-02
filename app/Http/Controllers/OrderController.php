<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'proses');

        $query = Order::with(['items.product'])
            ->where('user_id', Auth::id());

        if ($tab === 'selesai') {
            $query->where('status', 'completed');
        } elseif ($tab === 'dibatalkan') {
            $query->where('status', 'cancelled');
        } else {
            $query->whereIn('status', ['pending', 'shipped', 'delivered']);
        }

        $orders = $query->latest()->get();

        return view('orders.index', compact('orders', 'tab'));
    }

    // user klik "Selesai"
    public function complete(Order $order)
    {
        $this->authorizeOrder($order);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Pesanan belum bisa diselesaikan');
        }

        $order->update([
            'status' => 'completed'
        ]);

        return redirect()
        ->route('orders.index', ['tab' => 'selesai'])
        ->with('success', 'Pesanan berhasil diselesaikan');
    }

    // user klik "Batalkan"
    public function cancel(Order $order)
    {
        // ❌ cegah double cancel
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Pesanan sudah dibatalkan');
        }

        DB::transaction(function () use ($order) {

            // 🔁 kembalikan stok
            foreach ($order->items as $item) {

                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    $product->increment('stock', $item->qty);
                }
            }

            // ❌ update status order
            $order->update([
                'status' => 'cancelled'
            ]);
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan & stok dikembalikan');
    }

    // beli lagi (tambah qty)
    public function buyAgain(Order $order)
    {
        $this->authorizeOrder($order);

        foreach ($order->items as $item) {

            $cart = Cart::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'size' => $item->size,
                ],
                [
                    'quantity' => 0,
                    'price' => $item->product->price, // ✅ WAJIB
                ]
            );

            $cart->increment('quantity', $item->qty);
        }

        return redirect()->route('cart.index');
    }

    private function authorizeOrder(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
    }
}


