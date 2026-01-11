@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')

<style>
    @media (max-width: 480px) {

        .table {
            font-size: 13px;
        }

        .table-responsive,
        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        thead {
            display: none;
        }

        tbody tr {
            width: 100%;
            max-width: 100%;
        }

        tbody td {
            width: 100%;
        }

        tbody td img {
            margin-left: auto;
            margin-right: auto;
        }

        .cart-checkbox {
            transform: scale(1.1);
        }
        
        tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #555;
        }

        .btn-sm {
            padding: 2px 6px;
            font-size: 12px;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }

        .text-end {
            text-align: left !important;
        }

        .cart-summary,
        .container > hr + .d-flex {
            background: #f8f9fa;
            padding: 14px;
            border-radius: 12px;
            margin-top: 16px;
        }

        .container h5.text-success {
            font-size: 18px;
            font-weight: bold;
        }

        .text-end form {
            display: flex;
            gap: 10px;
            width: 100%;
            margin-top: 12px;
        }

        .text-end form a,
        .text-end form button {
            flex: 1;
            padding: 10px;
            font-size: 14px;
            border-radius: 8px;
        }

        .cart-item,
        tbody tr {
            padding: 14px 0;
        }

        tbody td {
            padding: 10px 6px !important;
            vertical-align: middle;
        }

        tbody select.form-select {
            width: 100% !important;
            min-height: 38px;
            font-size: 14px;
            padding: 6px 10px;
            line-height: 1.4;
            background-color: #fff;
        }

        tbody select option {
            font-size: 14px;
        }

        tbody td strong {
            display: block;
            margin-bottom: 6px;
        }

        tbody td:not(:last-child) {
            margin-bottom: 6px;
        }

        tbody td .d-flex {
            gap: 8px !important;
        }

        tbody td form button.btn-danger {
            margin-top: 6px;
        }

        .cart-size-select,
        .cart-size-select option,
        select.form-select {
            font-size: 14px !important;
            color: #000 !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }

        select.form-select {
            -webkit-appearance: menulist !important;
            appearance: menulist !important;
            background-color: #fff !important;
        }

        .cart-size-select {
            min-width: 70px !important;
            padding: 6px 28px 6px 10px !important;
        }

        select.form-select {
            background-image: none !important;
        }
    }
</style>

<div class="container mt-4">

    <h4 class="mb-3">{{ __('ui.cart') }}</h4>

    @if($cartItems->count() === 0)
        <div class="alert alert-warning">
            {{ __('ui.cart_empty') }}
        </div>

        <a href="{{ route('products.index') }}"
            class="btn btn-secondary">
                {{ __('ui.back') }}
        </a>
    @else
        <form>

            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" disabled>
                        </th>
                        <th>{{ __('ui.img') }}</th>
                        <th>{{ __('ui.product') }}</th>
                        <th>{{ __('ui.size') }}</th>
                        <th width="120">{{ __('ui.price') }}</th>
                        <th width="80">{{ __('ui.quantity') }}</th>
                        <th width="120">{{ __('ui.subtotal') }}</th>
                        <th width="80">{{ __('ui.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                        <td>
                            <input type="checkbox"
                                class="form-check-input cart-checkbox"
                                data-id="{{ $item->id }}"
                                {{ $item->is_selected ? 'checked' : '' }}
                            >
                        </td>

                        <td>
                            <img
                                src="{{ asset('img/' . $item->product->image) }}"
                                width="60"
                                height="60"
                                style="object-fit: cover"
                                class="rounded"
                            >
                        </td>    

                        <td>
                            <strong>{{ $item->product->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $item->product->category->name ?? '-' }}
                            </small>
                        </td>

                        <td>
                            @if($item->product->sizes && count($item->product->sizes) > 0)
                                <form action="{{ route('cart.updateSize', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <select name="size"
                                            class="form-select form-select-sm w-50 cart-size-select"
                                            onchange="this.form.submit()">
                                        @foreach($item->product->sizes as $size)
                                            <option value="{{ $size }}"
                                                {{ $item->size === $size ? 'selected' : '' }}>
                                                {{ $size }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">

                                {{-- Kurang --}}
                                <form action="{{ route('cart.updateQuantity', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="decrease">
                                    <button class="btn btn-sm btn-outline-secondary">−</button>
                                </form>

                                <span class="mx-2">{{ $item->quantity }}</span>

                                {{-- Tambah --}}
                                <form action="{{ route('cart.updateQuantity', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="action" value="increase">
                                    <button class="btn btn-sm btn-outline-secondary">+</button>
                                </form>

                            </div>
                        </td>

                        <td>
                            <strong>
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </strong>
                        </td>

                        <td>
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    {{ __('ui.delete') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>
                        Total ({{ $cartItems->count() }} {{ __('ui.product') }})
                    </strong>
                </div>

                <div>
                    {{-- <h6>Total ({{ $totalProduct }} produk)</h6> --}}

                    <h5 class="text-success">
                        Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                    </h5>
                </div>
            </div>

            <div class="text-end mt-3">
                <form action="{{ route('checkout.index') }}" method="GET">
                    @csrf

                    @foreach ($cartItems->where('is_selected', true) as $item)
                        <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                    @endforeach
                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary">
                        {{ __('ui.back') }}
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        {{ __('ui.checkout') }}
                    </button>
                </form>
            </div>

        </form>
    @endif

</div>

<script>
    document.querySelectorAll('.cart-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            fetch(`/cart/${this.dataset.id}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                location.reload();
            });
        });
    });
</script>
@endsection
