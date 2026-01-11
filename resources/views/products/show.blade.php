@extends('layouts.app')

@section('title', 'Product Details')

@section('content')

<div class="container my-5">

    {{-- alert --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mx-4 mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mx-4 mb-4" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card shadow-sm p-4">
        <div class="row">

            {{-- IMAGE --}}
            <div class="col-md-5">
                @if($product->image)
                    <img src="{{ asset('img/' . $product->image) }}"
                         class="img-fluid rounded"
                         alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/400x400?text=No+Image"
                         class="img-fluid rounded">
                @endif
            </div>

            {{-- INFO --}}
            <div class="col-md-7">
                <h2 class="mb-3">{{ $product->name }}</h2>

                <p><strong>{{ __('ui.colour') }} :</strong> {{ $product->color }}</p>

                {{-- SIZE DROPDOWN --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('ui.size') }}</label>
                    <select name="size" class="form-select" required>
                        @foreach($product->stocks as $stock)
                            <option value="{{ $stock->size }}"
                                {{ $stock->stock == 0 ? 'disabled' : '' }}>
                                {{ $stock->size }}
                                {{ $stock->stock == 0 ? '(Habis)' : '(Stok: '.$stock->stock.')' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STOCK --}}
                {{-- <p><strong>Stock:</strong> {{ $product->stock }}</p> --}}

                {{-- LOKASI --}}
                <p><strong>{{ __('ui.location') }}:</strong> {{ $product->location }}</p>

                {{-- PRICE --}}
                <h3 class="text-success mb-3">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </h3>

                {{-- DESCRIPTION --}}
                <p>{{ $product->description }}</p>

                {{-- ACTION BUTTON --}}
                <div class="mt-4 d-flex gap-2">

                    {{-- BACK --}}
                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary">
                        {{ __('ui.back') }}
                    </a>

                    {{-- Admin --}}
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="btn btn-warning">
                                {{ __('ui.edit') }}
                            </a>

                        {{-- user login --}}
                        @else
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <button type="submit" class="btn btn-outline-warning btn-cart-custom">
                                    <i data-feather="shopping-cart"></i>
                                    +
                                </button>
                            </form>
                        @endif

                    {{-- belum login --}}
                    @else
                        <button type="submit" class="btn btn-outline-danger btn-cart-custom">
                            <a href="{{ route('login') }}">
                                <i data-feather="shopping-cart"></i>
                                +
                            </a>
                        </button>
                    @endauth

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


