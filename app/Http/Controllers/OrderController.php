<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCancelledMail;
use App\Models\ProductStock;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\OrderCancelledNotification;
use App\Models\User;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'proses');

        $query = Order::with(['items.product', 'return'])
            ->where('user_id', Auth::id());

        match ($tab) {
            'proses' => $query->where('status', 'pending'),

            'dikirim' => $query->whereIn('status', [
                'shipped',
                'delivered'
            ]),

            'selesai' => $query->where('status', 'completed'),

            'dibatalkan' => $query->where('status', 'cancelled'),

            'dikembalikan' => $query->where('status', 'returned'),
        };

        $orders = $query->latest()->get();

        return view('orders.index', compact('orders', 'tab'));
    }

    // if klik Selesai
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

    // if klik Batalkan
    public function cancel(Order $order)
    {
  
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Pesanan sudah dibatalkan');
        }

        DB::transaction(function () use ($order) {

            // return stock
            foreach ($order->items as $item) {

                $stock = ProductStock::where('product_id', $item->product_id)
                    ->where('size', $item->size)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    $stock->increment('stock', $item->qty);
                }
            }

            $order->update([
                'status' => 'cancelled'
            ]);
        });

        // email
        Mail::to($order->email)->send(new OrderCancelledMail($order));

        // notif
        $order->user->notify(
            new OrderCancelledNotification(
                isAdmin: false,
                orderId: $order->id
            )
        );

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new OrderCancelledNotification(
                isAdmin: true,
                orderId: $order->id
            ));
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan & stok dikembalikan');
    }

    // beli lagi = +qty
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
                    'price' => $item->product->price,
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


