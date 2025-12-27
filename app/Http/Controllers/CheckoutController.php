<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->where('is_selected', 1)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih produk terlebih dahulu');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_name'    => 'required|string|max:100',
            'email'            => 'required|email',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string',
            'city'             => 'required|string',
            'village'          => 'required|string',
            'dusun'            => 'required|string',
            'rt'               => 'required|string',
            'rw'               => 'required|string',
            'shipping_service' => 'required|string',
            'shipping_cost'    => 'required|integer',
            'payment_method'   => 'required|string',
            'cart_ids'         => 'required|array',
        ]);

        $userId = Auth::id();

        $cartItems = Cart::with('product')
            ->where('user_id', $userId)
            ->whereIn('id', $request->cart_ids)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Produk tidak valid');
        }

        //subtotal produk
        $subtotal = 0;
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        //Ongkir
        $shippingCost = (int) $request->shipping_cost;

        //Total
        $total = $subtotal + $shippingCost;

            DB::transaction(function () use (
                $request,
                $userId,
                $subtotal,
                $shippingCost,
                $total,
                $cartItems,
            ) {
                // dd($request->all());
                $order = Order::create([
                    'user_id'          => $userId,
                    'receiver_name'    => $request->receiver_name,
                    'email'            => $request->email,
                    'phone'            => $request->phone,
                    'address'          => $request->address,
                    'city'             => $request->city,
                    'village'          => $request->village,
                    'dusun'            => $request->dusun,
                    'rt'               => $request->rt,
                    'rw'               => $request->rw,
                    'shipping_service' => $request->shipping_service,
                    'shipping_cost'    => $shippingCost,
                    'payment_method'   => $request->payment_method,
                    'subtotal'         => $subtotal,
                    'total'            => $total,
                    'status'           => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->product_id,
                        'size'       => $item->size,
                        'qty'        => $item->quantity,
                        'price'      => $item->product->price,
                        'subtotal'   => $item->product->price * $item->quantity,
                    ]);
                }

                // hapus cart setelah checkout
                Cart::whereIn('id', $cartItems->pluck('id'))->delete();
            });

        return redirect()->route('products.index')
            ->with('success', 'Pesanan berhasil dibuat');
    }
}

