<?php

namespace App\Http\Controllers\Admin;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCancelledMail;
use App\Notifications\OrderCancelledNotification;
use App\Models\User;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'dikemas');

        $query = Order::with(['items.product', 'user']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter tab
        match ($tab) {
            'dikemas'     => $query->where('status', 'pending'),
            'dikirim'     => $query->whereIn('status', ['shipped', 'delivered']),
            'selesai'     => $query->where('status', 'completed'),
            'dibatalkan'  => $query->where('status', 'cancelled'),
            'dikembalikan'=> $query->where('status', 'returned'),
        };

        $orders = $query->latest()->get();

        return view('admin.orders.index', compact('orders', 'tab'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Logika status
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Status pesanan sudah final');
        }

        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled'
        ]);

        if ($request->status === 'completed') {
            return back()->with('error', 'Selesaikan pesanan hanya bisa oleh user');
        }

        if ($request->status === 'delivered' && $order->status !== 'shipped') {
            return back()->with('error', 'Status tidak valid');
        }

        DB::transaction(function () use ($request, $order) {
            // Cancel
            if (
                $request->status === 'cancelled' &&
                $order->status !== 'cancelled'
            ) {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->qty);
                }
            }

            if ($request->status === 'cancelled') {
                foreach ($order->items as $item) {
                    $stock = \App\Models\ProductStock::where('product_id', $item->product_id)
                        ->where('size', $item->size)
                        ->first();

                    if ($stock) {
                        $stock->increment('stock', $item->qty);
                    }
                }
            }

            // Update status
            $order->update([
                'status' => $request->status
            ]);
        });

        if ($request->status === 'cancelled') {

            // email
            Mail::to($order->email)->send(
                new OrderCancelledMail($order)
            );

            // notif
            $order->user->notify(
                new OrderCancelledNotification(false, $order->id)
            );

            $admins = User::where('role', 'admin')
                ->where('id', '!=', $request->user()->id)
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(
                    new OrderCancelledNotification(true, $order->id)
                );
            }
        }

        return back()->with('success', 'Status pesanan diperbarui');
    }

    public function approveReturn(\App\Models\OrderReturn $orderReturn)
    {
        abort_if($orderReturn->status !== 'requested', 403);

        DB::transaction(function () use ($orderReturn) {
            $orderReturn->update([
                'status' => 'approved'
            ]);
        });

        $orderReturn->user->notify(
            new \App\Notifications\OrderReturnApprovedNotification(
                $orderReturn->order_id
            )
        );

        return back()->with('success', 'Pengembalian diterima');
    }

    public function rejectReturn(\App\Models\OrderReturn $orderReturn)
    {
        abort_if($orderReturn->status !== 'requested', 403);

        $orderReturn->update([
            'status' => 'rejected'
        ]);

        $orderReturn->user->notify(
            new \App\Notifications\OrderReturnRejectedNotification(
                $orderReturn->order_id
            )
        );

        return back()->with('success', 'Pengembalian ditolak');
    }
}
