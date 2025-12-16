@extends('layouts.app')

@section('content')
<div class="container my-4">

    <h3 class="mb-4">Checkout</h3>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        {{-- DATA PEMBELI --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Data Penerima</h5>

                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="receiver_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Alamat Lengkap</label>
                    <textarea name="address" class="form-control" rows="3" required
                        placeholder="Jalan atau tempat spesifik"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kota / Kabupaten</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Desa</label>
                        <input type="text" name="village" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Dusun</label>
                        <input type="text" name="dusun" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>RT</label>
                        <input type="text" name="rt" class="form-control" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>RW</label>
                        <input type="text" name="rw" class="form-control" required>
                    </div>
                </div>

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Produk Dipesan</h5>

                @foreach ($cartItems as $cart)
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img
                                src="{{ asset('img/' . $cart->product->image) }}"
                                width="60"
                                height="60"
                                style="object-fit: cover"
                                class="rounded"
                            >

                        <div class="flex-grow-1">
                            <strong>{{ $cart->product->name }}</strong><br>
                            <small>Ukuran: {{ $cart->size }}</small>
                        </div>

                        <div class="text-end">
                            <div>Rp {{ number_format($cart->product->price) }}</div>
                            <small>
                                x {{ $cart->quantity }}
                            </small>
                        </div>
                    </div>

                    {{-- kirim cart_id--}}
                    <input type="hidden" name="cart_ids[]" value="{{ $cart->id }}">
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5>Jasa Pengiriman</h5>

                <select name="shipping_cost" id="shipping_cost" class="form-select" required>
                    <option value="15000" data-service="ALP Express">ALP Express - Rp 15.000</option>
                    <option value="20000" data-service="J&T Singular">J&T Singular - Rp 20.000</option>
                    <option value="30000" data-service="SPX Some Day">SPX Some Day - Rp 30.000</option>
                </select>

                <input type="hidden" name="shipping_service" id="shipping_service" value="ALP Express">

            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5>Metode Pembayaran</h5>

                <select name="payment_method" class="form-select" required>
                    <option value="bca">BCA</option>
                    <option value="bri">BRI</option>
                    <option value="cod">COD</option>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-end">
                <p>Subtotal Produk: <strong>Rp {{ number_format($subtotal) }}</strong></p>
                <p>Ongkir: <strong id="ongkir-text">Rp 15.000</strong></p>
                <h4>Total Bayar:
                    <strong id="total-text">
                        Rp {{ number_format($subtotal + 15000) }}
                    </strong>
                </h4>
                <button class="btn btn-danger btn-lg mt-3">
                    Buat Pesanan
                </button>
            </div>
        </div>

        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
        <input type="hidden" name="total" id="total-input" value="{{ $subtotal + 15000 }}">

    </form>
</div>

<script>
    const shippingSelect = document.getElementById('shipping_cost');
    const ongkirText = document.getElementById('ongkir-text');
    const totalText = document.getElementById('total-text');

    const subtotal = {{ $subtotal }};

    shippingSelect.addEventListener('change', function () {
        const ongkir = parseInt(this.value);

        ongkirText.innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
        totalText.innerText = 'Rp ' + (subtotal + ongkir).toLocaleString('id-ID');
    });

    document.getElementById('shipping_cost').addEventListener('change', function () {
        const service = this.options[this.selectedIndex].dataset.service;
        document.getElementById('shipping_service').value = service;
    });
</script>
@endsection


