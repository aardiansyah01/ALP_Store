<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrderReturnController extends Controller
{
    public function create(Order $order)
    {
        // security, only status=completed
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'completed', 403);
        abort_if($order->return, 403);

        $order->load('items.product');

        return view('orders.return.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->return, 403);

        // filter item
        $items = collect($request->items)
            ->filter(fn ($item) =>
                isset($item['product_id']) &&
                isset($item['qty']) &&
                $item['qty'] > 0
            )
            ->values()
            ->toArray();

        if (count($items) === 0) {
            return back()
                ->withErrors(['items' => 'Pilih minimal 1 produk untuk dikembalikan'])
                ->withInput();
        }

        $request->merge(['items' => $items]);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',

            'reason' => 'required|string|max:255',
            'description' => 'required|string',

            'receipt_image' => 'required|image|max:2048',
            'unboxing_video' => 'required|mimes:mp4,mov,avi|max:20480',
        ]);

        DB::transaction(function () use ($request, $order) {

            $receiptPath = $request->file('receipt_image')
                ->store('returns/receipts', 'public');

            $videoPath = $request->file('unboxing_video')
                ->store('returns/videos', 'public');

            $return = OrderReturn::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'description' => $request->description,
                'receipt_image' => $receiptPath,
                'unboxing_video' => $videoPath,
                'status' => 'requested',
            ]);

            foreach ($request->items as $item) {
                OrderReturnItem::create([
                    'order_return_id' => $return->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                ]);
            }

            $order->update(['status' => 'returned']);
        });

        return redirect()
            ->route('orders.index', ['tab' => 'dikembalikan'])
            ->with('success', 'Pengajuan pengembalian berhasil dikirim');
    }
}
