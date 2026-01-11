@extends('layouts.app')

@section('content')
<div class="container my-4">

    <h3 class="mb-4">Checkout</h3>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf

        {{-- Data user --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>{{ __('ui.receiver_data') }}</h5>

                <div class="mb-3">
                    <label>{{ __('ui.full_name') }}</label>
                    <input type="text" name="receiver_name" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('ui.telephone') }}</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>{{ __('ui.user_location') }}</label>
                    <textarea name="address" class="form-control" rows="3" required
                        placeholder="Jalan atau tempat spesifik"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>{{ __('ui.city') }}</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('ui.village') }}</label>
                        <input type="text" name="village" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>{{ __('ui.hamlet') }}</label>
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
                <h5>{{ __('ui.product') }}</h5>

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
                            <small>{{ __('ui.size') }}: {{ $cart->size }}</small>
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
                <h5>{{ __('ui.shipping') }}</h5>
                @livewire('princing-check')
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-body">
                <h5>{{ __('ui.payment_method') }}</h5>

                <select name="payment_method" class="form-select" required>
                    <option value="bca">BCA (Bank Central Asia)</option>
                    <option value="bri">BRI (Bank Republik Indonesia)</option>
                    <option value="Paypall">Paypall</option>
                    <option value="Dana">Dana</option>
                </select>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">{{ __('ui.payment_summary') }}</h6>

                <div class="d-flex justify-content-between">
                    <span>{{ __('ui.subtotal') }} {{ __('ui.product') }}</span>
                    <strong>Rp {{ number_format($subtotal) }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>{{ __('ui.shipping') }}</span>
                    <strong id="ongkir-text">Rp 0</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between fs-5">
                    <strong>{{ __('ui.total') }}</strong>
                    <strong id="total-text">Rp {{ number_format($subtotal) }}</strong>
                </div>

                <a href="{{ route('cart.index') }}"
                    class="btn btn-md btn-secondary mt-3">
                    {{ __('ui.cancel') }}
                </a>
                
                <button class="btn btn-danger btn-md mt-3">
                    {{ __('ui.create_order') }}
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


