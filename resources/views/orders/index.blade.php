@extends('layouts.app')

@section('content')
<div class="container my-4">

    <h3 class="mb-4">Pesanan Saya</h3>

    @forelse ($orders as $order)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span>
                    <strong>Order #{{ $order->id }}</strong>
                </span>
                <span class="badge bg-warning text-dark">
                    {{ strtoupper($order->status) }}
                </span>
            </div>

            <div class="card-body">
                @foreach ($order->items as $item)
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img
                            src="{{ asset('img/' . $item->product->image) }}"
                            width="60"
                            height="60"
                            class="rounded"
                            style="object-fit: cover"
                        >

                        <div class="ms-3 flex-grow-1">
                            <strong>{{ $item->product->name }}</strong><br>
                            <small>Ukuran: {{ $item->size }}</small><br>
                            <small>Jumlah: {{ $item->qty }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            Belum ada pesanan.
        </div>
    @endforelse

</div>
@endsection
