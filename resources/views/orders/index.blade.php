@extends('layouts.app')

@section('content')

<style>
    @media (max-width: 480px) {

        /* container tab */
        .orders-tab,
        .orders-tab-mobile,
        .d-flex.gap-4.border-bottom {
            gap: 14px !important;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 6px;
            scrollbar-width: none; /* firefox */
        }

        .d-flex.gap-4.border-bottom::-webkit-scrollbar {
            display: none; /* chrome */
        }

        /* tab item */
        .d-flex.gap-4.border-bottom a {
            font-size: 13px;
            padding-bottom: 6px;
        }
    }
</style>

<div class="container my-4">

    <h3 class="mb-4">{{ __('ui.my_orders') }}</h3>

    {{-- Tab --}}
    <div class="d-flex gap-4 border-bottom mb-4">
        <a href="{{ route('orders.index', ['tab'=>'proses']) }}"
           class="{{ $tab==='proses' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            {{ __('ui.process') }}
        </a>

        <a href="{{ route('orders.index', ['tab'=>'dikirim']) }}"
           class="{{ $tab==='dikirim' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            {{ __('ui.deliver') }}
        </a>

        <a href="{{ route('orders.index', ['tab'=>'selesai']) }}"
           class="{{ $tab==='selesai' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            {{ __('ui.completed') }}
        </a>

        <a href="{{ route('orders.index', ['tab'=>'dibatalkan']) }}"
           class="{{ $tab==='dibatalkan' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            {{ __('ui.cancelled') }}
        </a>

        <a href="{{ route('orders.index', ['tab' => 'dikembalikan']) }}"
            class="{{ $tab === 'dikembalikan' ? 'fw-bold text-primary border-bottom border-primary pb-2' : 'text-muted' }}">
            {{ __('ui.refunded') }}
        </a>
    </div>

    @forelse($orders as $order)

    <div class="card mb-4">

        {{-- Card header --}}
        <div class="card-header d-flex justify-content-between">
            <span>
                {{ $order->created_at->format('d M Y') }}
            </span>
            <span class="badge bg-info">
                {{ strtoupper($order->status) }}
            </span>
        </div>

        {{-- Card product --}}
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
                        {{ __('ui.size') }}: {{ $item->size }} · {{ __('ui.quantity') }}: {{ $item->qty }}
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

            {{-- Button --}}
            <div class="mt-3 d-flex gap-2 justify-content-end">
                @if($tab === 'proses')
                    {{-- Batalkan --}}
                    <a href="#" class="btn btn-outline-info btn-sm">{{ __('ui.pay_now') }}</a>
                    @if(in_array($order->status, ['pending']))
                        <form method="POST" action="{{ route('orders.cancel',$order) }}">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm">{{ __('ui.cancel') }}</button>
                        </form>
                    @endif
                @elseif($order->status === 'delivered')
                {{-- Terima --}}
                    <form method="POST" action="{{ route('orders.complete', $order) }}">
                        @csrf
                        <button class="btn btn-success btn-sm">
                            {{ __('ui.orders_completed') }}
                        </button>
                    </form>
                @elseif($tab === 'selesai')
                {{-- beli lagi atau kembalikan --}}
                    <form method="POST" action="{{ route('orders.buyAgain',$order) }}">
                        @csrf
                        <button class="btn btn-primary btn-sm">{{ __('ui.buy_again') }}</button>
                    </form>

                    <a href="{{ route('orders.return.create', $order) }}"
                        class="btn btn-warning">  
                        {{ __('ui.refund') }}
                    </a>
                    @endif
            </div>
        </div>

        {{-- Card alamat --}}
        <div class="card-body border-top">
            <strong>{{ $order->receiver_name }}</strong><br>
            {{ $order->address }},
            {{ $order->village }},
            {{ $order->city }}
        </div>

        {{-- Total --}}
        <div class="card-body border-top">
            <div class="d-flex justify-content-between">
                <span>{{ __('ui.subtotal') }}</span>
                <span>Rp {{ number_format($order->subtotal) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>{{ __('ui.shipping') }}</span>
                <span>Rp {{ number_format($order->shipping_cost) }}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between fw-bold">
                <span>{{ __('ui.total') }}</span>
                <span>Rp {{ number_format($order->total) }}</span>
            </div>
        </div>

        {{-- Jika dibatalkan --}}
        @if($order->status === 'returned' && $order->return)
            <div class="mt-4 p-3 border rounded bg-light">

                <h6 class="fw-bold mb-3 text-warning">{{ __('ui.refund_detail') }}</h6>

                <div class="mb-2">
                    <strong>{{ __('ui.reason') }}:</strong><br>
                    <span class="text-muted">{{ $order->return->reason }}</span>
                </div>

                <div class="mb-3">
                    <strong>{{ __('ui.explain') }}:</strong>
                    <div class="text-muted small">
                        {{ $order->return->description }}
                    </div>
                </div>

                <strong>{{ __('ui.product_refunded') }}:</strong>
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

                {{-- Tombol & Popup --}}
                @if($order->return->status === 'approved')
                    @php
                        $deadline = \Carbon\Carbon::parse($order->return->updated_at)
                            ->addDays(14)
                            ->format('d M Y');
                    @endphp

                    <div class="mt-3">
                        <button
                            class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#returnGuideModal-{{ $order->id }}">
                            {{ __('ui.see_howto_refund') }}
                        </button>
                    </div>

                    <div class="modal fade"
                        id="returnGuideModal-{{ $order->id }}"
                        tabindex="-1"
                        aria-hidden="true">

                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold text-success">
                                        {{ __('ui.refund_acepted') }}
                                    </h5>
                                    <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <h4 class="fw-bold mb-3">
                                        {{ __('ui.refund_acepted_details') }}
                                    </h4>

                                    <p>
                                        {{ __('ui.refund_step') }}:
                                    </p>

                                    <ol>
                                        <li>{{ __('ui.refund_step1') }}</li>
                                        <li>{{ __('ui.refund_step2') }}</li>
                                        <li>{{ __('ui.refund_step3') }}:
                                            <ol>
                                                <li>Nama Penerima : Aditya ardian</li>
                                                <li>Alamat : Gudang ALP Store </li>
                                                <li>Kabupaten : Sidoarjo</li>
                                                <li>Kecamatan : Porong</li>
                                                <li>Desa : Juwet</li>
                                                <li>RT/RT : 03/11</li>
                                            </ol>
                                        </li>
                                        <li>{{ __('ui.refund_step4') }}</li>
                                    </ol>

                                    <div class="alert alert-warning mt-3">
                                        {{ __('ui.refund_massage1') }}
                                        <strong>{{ $deadline }}</strong>.
                                        {{ __('ui.refund_massage2') }}
                                        <strong>{{ __('ui.refund_massage3') }}</strong>.
                                    </div>

                                    <p>
                                        {{ __('ui.refund_alert1') }}
                                    </p>

                                    <p class="mb-0">
                                        {{ __('ui.refund_alert2') }}
                                    </p>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                        {{ __('ui.close') }}
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($order->return && $order->return->status === 'rejected')
            <div class="mt-3 alert alert-danger">
                <strong>{{ __('ui.refund_rejected') }}</strong><br>
                {{ __('ui.refund_rejected2') }}
            </div>
        @endif
    </div>
    @empty
    <div class="alert alert-info">{{ __('ui.orders_empty') }}</div>
    @endforelse
</div>
<a  href="{{ route('products.index') }}"
    class="btn btn-md btn-secondary mb-1">
    {{ __('ui.back') }}
</a>
@endsection
