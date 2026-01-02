@extends('layouts.app')

@section('content')
<div class="container my-4">

    <h3 class="mb-4">Pesanan Saya</h3>

    {{-- TAB --}}
    <div class="d-flex gap-4 border-bottom mb-4">
        <a href="{{ route('orders.index', ['tab'=>'proses']) }}"
           class="{{ $tab==='proses' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            Proses
        </a>

        <a href="{{ route('orders.index', ['tab'=>'selesai']) }}"
           class="{{ $tab==='selesai' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            Selesai
        </a>

        <a href="{{ route('orders.index', ['tab'=>'dibatalkan']) }}"
           class="{{ $tab==='dibatalkan' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            Dibatalkan
        </a>
    </div>

    @forelse($orders as $order)

    <div class="card mb-4">

        {{-- CARD HEADER --}}
        <div class="card-header d-flex justify-content-between">
            <span>
                {{ $order->created_at->format('d M Y') }}
            </span>
            <span class="badge bg-info">
                {{ strtoupper($order->status) }}
            </span>
        </div>

        {{-- CARD PRODUK --}}
        <div class="card-body">
            @foreach($order->items as $item)
            <div class="d-flex gap-3 border-bottom py-3">
                <img src="{{ asset('img/'.$item->product->image) }}"
                     width="80" height="80"
                     class="rounded"
                     style="object-fit:cover">

                <div class="flex-grow-1">
                    <strong>{{ $item->product->name }}</strong>
                    <div class="text-muted small">
                        Ukuran: {{ $item->size }} · Qty: {{ $item->qty }}
                    </div>
                    <div class="text-muted small">
                        {{ $order->payment_method }}
                    </div>
                </div>

                <div class="text-end">
                    <div>{{ $order->shipping_service }}</div>
                    <strong>Rp {{ number_format($order->subtotal) }}</strong>
                </div>
            </div>
            @endforeach

            {{-- ACTION BUTTON --}}
            <div class="mt-3 d-flex gap-2 justify-content-end">
                @if($tab === 'proses')
                    {{-- Batalkan --}}
                    @if(in_array($order->status, ['pending']))
                        <form method="POST" action="{{ route('orders.cancel',$order) }}">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">Batalkan</button>
                        </form>
                    @endif
                    @if($order->status === 'delivered')
                    <form method="POST" action="{{ route('orders.complete', $order) }}">
                        @csrf
                        <button class="btn btn-success btn-sm">
                            Pesanan Selesai
                        </button>
                    </form>
                    @endif
                @elseif($tab === 'selesai')

                    <form method="POST" action="{{ route('orders.buyAgain',$order) }}">
                        @csrf
                        <button class="btn btn-primary btn-sm">Beli Lagi</button>
                    </form>

                @endif
            </div>
        </div>

        {{-- CARD ALAMAT --}}
        <div class="card-body border-top">
            <strong>{{ $order->receiver_name }}</strong><br>
            {{ $order->address }},
            {{ $order->village }},
            {{ $order->city }}
        </div>

        {{-- CARD TOTAL --}}
        <div class="card-body border-top">
            <div class="d-flex justify-content-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->subtotal) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Ongkir</span>
                <span>Rp {{ number_format($order->shipping_cost) }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span>Rp {{ number_format($order->total) }}</span>
            </div>
        </div>

        {{-- TRACKING --}}
        <div class="card-footer">
            <button class="btn btn-outline-primary w-100">
                Lacak Pengiriman
            </button>
        </div>

    </div>
    @empty
    <div class="alert alert-info">Belum ada pesanan</div>
    @endforelse
</div>
<a  href="{{ route('products.index') }}"
    class="btn btn-md btn-secondary mb-1">
    Back
</a>
@endsection
