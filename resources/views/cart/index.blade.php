@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mt-4">

    <h4 class="mb-3">Keranjang Belanja</h4>

    @if($cartItems->count() === 0)
        <div class="alert alert-warning">
            Keranjang belanja masih kosong
        </div>
    @else
        <form>

            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">
                            <input type="checkbox" disabled>
                        </th>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th width="120">Harga</th>
                        <th width="80">Jumlah</th>
                        <th width="120">Subtotal</th>
                        <th width="80">Aksi</th>
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
                                            class="form-select form-select-sm w-50"
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
                                    Hapus
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
                        Total ({{ $cartItems->count() }} produk)
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
                        Back
                    </a>
                    
                    <button type="submit" class="btn btn-primary">
                        Checkout
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
