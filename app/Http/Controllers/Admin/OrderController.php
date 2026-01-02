<?php

namespace App\Http\Controllers\Admin;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'dikemas');

        $query = Order::with(['items.product', 'user']);

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                ->orWhereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // FILTER
        match ($tab) {
            'dikemas'    => $query->where('status', 'pending'),
            'dikirim'    => $query->whereIn('status', ['shipped', 'delivered']),
            'selesai'    => $query->where('status', 'completed'),
            'dibatalkan' => $query->where('status', 'cancelled'),
        };

        $orders = $query->latest()->get();

        return view('admin.orders.index', compact('orders', 'tab'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // ❌ tidak boleh ubah jika sudah final
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Status pesanan sudah final');
        }

        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled'
        ]);

        // ❌ admin tidak boleh set completed
        if ($request->status === 'completed') {
            return back()->with('error', 'Selesaikan pesanan hanya bisa oleh user');
        }

        // shipped -> delivered saja
        if ($request->status === 'delivered' && $order->status !== 'shipped') {
            return back()->with('error', 'Status tidak valid');
        }

        DB::transaction(function () use ($request, $order) {

            // 🔥 TAMBAHAN LOGIKA (INI SAJA YANG BARU)
            if (
                $request->status === 'cancelled' &&
                $order->status !== 'cancelled'
            ) {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->qty);
                }
            }

            // update status (kode lama kamu, tidak diubah)
            $order->update([
                'status' => $request->status
            ]);
        });

        return back()->with('success', 'Status pesanan diperbarui');
    }
}
