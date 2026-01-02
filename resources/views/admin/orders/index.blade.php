@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Admin - Semua Pesanan</h3>

    {{-- FILTER --}}
    <form class="row mb-4" method="GET">
        <input type="hidden" name="tab" value="{{ $tab }}">

        <div class="col-md-4">
            <input type="text"
                name="search"
                class="form-control"
                placeholder="Cari ID / Nama User"
                value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>

    <div class="d-flex gap-4 border-bottom mb-4">
        @foreach([
            'dikemas' => 'Dikemas',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ] as $key => $label)

            <a href="{{ route('admin.orders.index',
                array_merge(request()->except('tab'), ['tab'=>$key])
            ) }}"
            class="{{ $tab === $key
                ? 'fw-bold text-primary border-bottom border-primary pb-2'
                : 'text-muted' }}">

                {{ $label }}

            </a>
        @endforeach
    </div>

    {{-- LIST ORDER --}}
    @foreach($orders as $order)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <strong>Order #{{ $order->id }} — {{ $order->user->name }}</strong>

                @if(in_array($order->status, ['completed','cancelled']))
                    <span class="badge bg-secondary">
                        {{ strtoupper($order->status) }}
                    </span>
                @else
                    <form method="POST"
                        action="{{ route('admin.orders.updateStatus', $order) }}">
                        @csrf
                        @method('PATCH')

                        <select name="status"
                            onchange="this.form.submit()"
                            class="form-select form-select-sm">

                        @if($order->status === 'pending')
                            <option value="pending" selected>Dikemas</option>
                            <option value="shipped">Dikirim</option>
                            <option value="cancelled">Batalkan</option>

                        @elseif($order->status === 'shipped')
                            <option value="shipped" selected>Dikirim</option>
                            <option value="delivered">Delivered</option>

                        @elseif($order->status === 'delivered')
                            <option value="delivered" selected>Delivered</option>

                        @endif
                    </select>

                    </form>
                @endif
            </div>

            <div class="card-body">

                {{-- LIST PRODUK --}}
                @foreach($order->items as $item)
                    <div class="d-flex mb-3">
                        <img src="{{ asset('img/'.$item->product->image) }}"
                            width="60" class="rounded">

                        <div class="ms-3">
                            <strong>{{ $item->product->name }}</strong><br>
                            Ukuran: {{ $item->size }} |
                            Qty: {{ $item->qty }}<br>
                            Rp {{ number_format($item->subtotal,0,',','.') }}
                        </div>
                    </div>
                @endforeach

                <hr>

                {{-- DATA PENERIMA --}}
                <div class="small text-muted">
                    <strong>Penerima:</strong> {{ $order->receiver_name }}<br>
                    <strong>Alamat:</strong>
                    {{ $order->address }},
                    {{ $order->village }},
                    {{ $order->city }}<br>

                    <strong>Ongkir:</strong>
                    {{ $order->shipping_service }} —
                    Rp {{ number_format($order->shipping_cost,0,',','.') }}
                </div>

                <hr>

                <strong>Total:</strong>
                Rp {{ number_format($order->total,0,',','.') }}

            </div>
        </div>
    @endforeach

    <a href="{{ route('products.index') }}"
        class="btn btn-md btn-secondary mt-2 mb-2">
            Back
    </a>

</div>
@endsection
