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

                <p><strong>Color:</strong> {{ $product->color }}</p>

                {{-- SIZE DROPDOWN --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Size</label>
                    <select name="size" class="form-select" required>
                        @if(is_array($product->sizes))
                            @foreach($product->sizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        @else
                            <option value="">Ukuran tidak tersedia</option>
                        @endif
                    </select>
                </div>

                {{-- STOCK --}}
                <p><strong>Stock:</strong> {{ $product->stock }}</p>

                {{-- LOKASI --}}
                <p><strong>Lokasi:</strong> {{ $product->location }}</p>

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
                        Back
                    </a>

                    {{-- ADMIN --}}
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="btn btn-warning">
                                Edit Product
                            </a>

                        {{-- USER LOGIN --}}
                        @else
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <button type="submit" class="btn btn-primary">
                                    + Keranjang
                                </button>
                            </form>
                        @endif

                    {{-- BELUM LOGIN --}}
                    @else
                        <a href="{{ route('login') }}"
                           class="btn btn-primary">
                            Login untuk + Keranjang
                        </a>
                    @endauth

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


