@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Admin - {{ __('ui.admin_orders') }}</h3>

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
            <button class="btn btn-primary">{{ __('ui.find') }}</button>
        </div>
    </form>

    {{-- Tab --}}
    <div class="d-flex gap-4 border-bottom mb-4">
        @foreach([
            'dikemas' => 'Dikemas',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'dikembalikan' => 'Dikembalikan'
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

    {{-- List order --}}
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
                            <option value="pending" selected>{{ __('ui.process') }}</option>
                            <option value="shipped">{{ __('ui.deliver') }}</option>
                            <option value="cancelled">{{ __('ui.cancel') }}</option>

                        @elseif($order->status === 'shipped')
                            <option value="shipped" selected>{{ __('ui.deliver') }}</option>
                            <option value="delivered">Delivered</option>

                        @elseif($order->status === 'delivered')
                            <option value="delivered" selected>Delivered</option>

                        @endif
                    </select>

                    </form>
                @endif
            </div>

            <div class="card-body">

                {{-- List produk --}}
                @foreach($order->items as $item)
                    <div class="d-flex mb-3">
                        <img src="{{ asset('img/'.$item->product->image) }}"
                            width="60" class="rounded">

                        <div class="ms-3">
                            <strong>{{ $item->product->name }}</strong><br>
                            {{ __('ui.size') }}: {{ $item->size }} |
                            {{ __('ui.quantity') }}: {{ $item->qty }}<br>
                            Rp {{ number_format($item->subtotal,0,',','.') }}
                        </div>
                    </div>
                @endforeach

                <hr>

                {{-- List data penerima --}}
                <div class="small text-muted">
                    <strong>{{ __('ui.filter_name') }}:</strong> {{ $order->receiver_name }}<br>
                    <strong>{{ __('ui.user_location') }}:</strong>
                    {{ $order->address }},
                    {{ $order->village }},
                    {{ $order->city }}<br>

                    <strong>{{ __('ui.shipping') }}:</strong>
                    {{ $order->shipping_service }} —
                    Rp {{ number_format($order->shipping_cost,0,',','.') }}
                </div>

                <hr>

                <strong>{{ __('ui.total') }}:</strong>
                Rp {{ number_format($order->total,0,',','.') }}

            </div>

            {{-- jika Dikembalikan --}}
            @if($order->status === 'returned')
                <span class="badge bg-warning">{{ __('ui.refunded') }}</span>

                <div class="mt-4 p-3 border rounded bg-light">

                    <h6 class="fw-bold mb-3 text-warning">{{ __('ui.refund_detail') }}</h6>

                    <div class="mb-2">
                        <strong>{{ __('ui.reason') }} :</strong><br>
                        <span class="text-muted">{{ $order->return->reason }}</span>
                    </div>

                    <div class="mb-3">
                        <strong>{{ __('ui.explain') }} :</strong>
                        <div class="text-muted small">
                            {{ $order->return->description }}
                        </div>
                    </div>

                    <strong>{{ __('ui.product_refunded') }} :</strong>
                    <ul class="small text-muted">
                        @foreach($order->return->items as $item)
                            <li>{{ $item->product->name }} — {{ $item->qty }} pcs</li>
                        @endforeach
                    </ul>

                    <div class="d-flex gap-3 mt-3">
                        @if($order->return->receipt_image)
                            <a class="btn btn-outline-primary btn-sm"
                            href="{{ asset('storage/'.$order->return->receipt_image) }}"
                            target="_blank">
                                {{ __('ui.resi_image') }}
                            </a>
                        @endif

                        @if($order->return->unboxing_video)
                            <a class="btn btn-outline-secondary btn-sm"
                            href="{{ asset('storage/'.$order->return->unboxing_video) }}"
                            target="_blank">
                                {{ __('ui.unboxing_videos') }}
                            </a>
                        @endif
                    </div>
                    <div class="d-flex gap-3 mt-3">
                        @if($order->status === 'returned' && $order->return)
                            @if($order->return->status === 'requested')
                                <form method="POST"
                                    action="{{ route('admin.returns.approve', $order->returnRequest) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success btn-sm">{{ __('ui.refund_acepted') }}</button>
                                </form>
        
                                <form method="POST"
                                    action="{{ route('admin.returns.reject', $order->returnRequest) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-danger btn-sm">{{ __('ui.refund_rejected') }}</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <a href="{{ route('products.index') }}"
        class="btn btn-md btn-secondary mt-2 mb-2">
            {{ __('ui.back') }}
    </a>

</div>
@endsection
