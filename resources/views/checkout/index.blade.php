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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
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
                <h5>Pengiriman</h5>
                @livewire('princing-check')
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5>Metode Pembayaran</h5>

                <select name="payment_method" class="form-select" required>
                    <option value="bca">BCA (Bank Central Asia)</option>
                    <option value="bri">BRI (Bank Republik Indonesia)</option>
                    <option value="cod">COD (Cash On Delivery)</option>
                    <option value="Paypall">Paypall</option>
                    <option value="Dana">Dana</option>
                </select>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Ringkasan Pembayaran</h6>

                <div class="d-flex justify-content-between">
                    <span>Subtotal Produk</span>
                    <strong>Rp {{ number_format($subtotal) }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Ongkir</span>
                    <strong id="ongkir-text">Rp 0</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <strong>Total Bayar</strong>
                    <strong id="total-text">Rp {{ number_format($subtotal) }}</strong>
                </div>

                <a href="{{ route('cart.index') }}"
                    class="btn btn-md btn-secondary mt-3">
                    Batal
                </a>
                
                <button class="btn btn-danger btn-md mt-3">
                    Buat Pesanan
                </button>
            </div>
        </div>

        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
        <input type="hidden" name="total" id="total-input" value="{{ $subtotal + 15000 }}">

    </form>
</div>

<script>
    const ongkirText = document.getElementById('ongkir-text');
    const totalText = document.getElementById('total-text');
    const totalInput = document.getElementById('total-input');
    const subtotal = {{ $subtotal }};

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('shippingSelected', ({ cost }) => {
            ongkirText.innerText = 'Rp ' + cost.toLocaleString('id-ID');
            totalText.innerText = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
            totalInput.value = subtotal + cost;
        });
    });
</script>

@endsection


