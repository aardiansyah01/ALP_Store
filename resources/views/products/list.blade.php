@extends('layouts.app')

@section('title', 'Product List')

@section('content')

<style>
    /* 5 card per row */
    .col-5-custom {
        width: 20%;
    }

    @media (max-width: 1200px) {
        .col-5-custom {
            width: 25%; /* 4 kolom */
        }
    }

    @media (max-width: 992px) {
        .col-5-custom {
            width: 33.3333%; /* 3 kolom */
        }
    }

    @media (max-width: 768px) {
        .col-5-custom {
            width: 50%; /* 2 kolom */
        }
    }

    @media (max-width: 576px) {
        .col-5-custom {
            width: 100%; /* 1 kolom */
        }
    }

    /* Kecilkan tombol di card */
    .btn-sm-custom {
        padding: 6px 10px;
        font-size: 13px;
    }

    /* Kecilkan pagination */
    svg {
        width: 1rem;
    }

    .alp-header,
    .top-bar,
    .main-bar,
    .filter-bar {
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    }


    .alp-header a { color: #fff; text-decoration: none; font-size: 13px; }


    /* ===== TOP BAR ===== */
    .top-bar { background: #00f0f0; color: #fff; height: 36px; }

    .top-left a {
        font-size: 0.7rem;
        cursor: pointer;
    }
    
    .top-left a:hover {
        font-size: 1rem;
        color: #e0ed28
    }

    .top-right a:hover {
        color: #e0ed28
    }
    
    .top-bar .top-left {
        margin-top: 15px;
        display: flex; !important
        justify-content: center;
    }


    /* ===== MAIN BAR ===== */
    .main-bar { 
        background: #00f0f0;
        display: flex
     }
    .logo { color: #fff; font-size: 30px; font-weight: bold; white-space: nowrap; }


    /* ===== SEARCH BOX (SHOPEE STYLE) ===== */
    .search-box input {
    border-radius: 2px 0 0 2px;
    height: 38px;
    margin-left: 30%;
    }


    .search-box button {
    border-radius: 0 2px 2px 0;
    background: #fff;
    border-left: 1px solid #ddd;
    width: 48px;
    height: 38px;
    }


    .search-box i {
    color: #d0011b;
    }


    /* ===== CART ICON FIX ===== */
    .cart-icon {
    color: #fff;
    cursor: pointer;
    margin-bottom: 10px;
    margin-left: -15px;
    }


    .cart-icon a svg {
    width: 2.5rem;
    }

    .cart-icon a svg:hover {
    color: #e0ed28;
    }

    .bag-icon svg {
        width: 4rem;
    }

    /* ===== FILTER BAR ===== */
    .filter-bar { background: #00f0f0; }


    .filter-bar select {
    min-width: 120px;
    }

    /* PAGINATION ALA SHOPEE */
    .pagination-top span {
        font-size: 14px;
    }

    .pagination-top .btn {
        padding: 4px 10px;
        background-color: #00e0f0
    }

    .pagination-top .btn.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    .welcome {
        display: flex;
        margin-top: 5px;
    }

    .welcome .btn-logout {
        font-size: 1rem;
        outline: none;
        text-decoration: none;
        color: #fff;
        background: none;
        border: none;
    }
    
    .welcome .btn-logout:hover {
        color: #e0ed28;
    }

    .welcome h6 {
        margin-top: 3px;
    }


</style>

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> --}}

<div class="alp-header mb-4">

    {{-- TOP BAR --}}
    <div class="top-bar d-flex justify-content-between align-items-center px-4">
        <div class="top-left">
            <a href="#"><h6>Home | </h6></a>
            <a href="{{ route('orders.index') }}" class="nav-link">
                <h6>| Pesanan Saya</h6>
            </a>
        </div>

        <div class="top-right">
            @auth
            <div class="welcome">
                <h6>Welcome, {{ Auth::user()->name }} |</h6>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn-logout"> Logout</button>
                </form>
            </div>
            @else
                <a href="{{ route('login') }}">Login</a> |
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </div>
    </div>

    {{-- MAIN BAR --}}
    <div class="main-bar d-flex align-items-center px-4 py-3">
        <div class="bag-icon text-white fs-4">
            <i data-feather="shopping-bag"> </i>
        </div>
        <div class="logo me-4">| ALP STORE</div>

        {{-- SEARCH --}}
        <form method="GET" class="search-box d-flex flex-grow-1 me-4">
            <input type="text" name="search" class="form-control"
                placeholder="Cari di ALP STORE" value="{{ $search }}">
            <button class="btn btn-light">
                <i data-feather="search"> </i>
            </button>
        </form>

        {{-- CART ICON --}}
        <div class="cart-icon text-white fs-4">
            <a href="{{ route('cart.index') }}" class="cart-icon position-relative">
                <i data-feather="shopping-cart"> </i>
            </a>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="filter-bar d-flex justify-content-between align-items-center px-4 py-3">
        <div class="">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                        + Tambah Produk
                    </a>
                @endif
            @endauth

            {{-- FILTER HARGA --}}
            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#filterRange">
                Filter Harga
            </button>
        </div>

        <div class="d-flex align-items-center">

            {{-- SORTING --}}
            <form method="GET" class="d-flex">
                <input type="hidden" name="min_price" value="{{ $min_price }}">
                <input type="hidden" name="max_price" value="{{ $max_price }}">
                <input type="hidden" name="search" value="{{ $search }}">

                <select name="sort" class="form-select form-select-sm me-2">
                    <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Nama</option>
                    <option value="price" {{ $sort == 'price' ? 'selected' : '' }}>Harga</option>
                </select>

                <select name="order" class="form-select form-select-sm me-2">
                    <option value="asc" {{ $order == 'asc' ? 'selected' : '' }}>A → Z / Murah</option>
                    <option value="desc" {{ $order == 'desc' ? 'selected' : '' }}>Z → A / Mahal</option>
                </select>

                <button class="btn btn-dark btn-sm">Urutkan</button>
            </form>
            {{-- PAGINATION --}}
            @if ($products->hasPages())
            <div class="d-flex align-items-center pagination-top ms-2">
                <span class="me-2">
                    {{ $products->currentPage() }}/{{ $products->lastPage() }}
                </span>

                <a href="{{ $products->previousPageUrl() ?? '#' }}"
                class="btn btn-light btn-sm me-1 {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    ‹
                </a>

                <a href="{{ $products->nextPageUrl() ?? '#' }}"
                class="btn btn-light btn-sm {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                    ›
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- FILTER RANGE --}}
<div class="container_top">
    <div id="filterRange" class="collapse mb-4">
        <div class="card card-body">
            <form method="GET" class="row g-3">

                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="order" value="{{ $order }}">
                <input type="hidden" name="search" value="{{ $search }}">

                <div class="col-md-4">
                    <label class="form-label">Harga Minimum</label>
                    <input type="number" name="min_price" class="form-control form-control-sm"
                        value="{{ $min_price }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Maksimum</label>
                    <input type="number" name="max_price" class="form-control form-control-sm"
                        value="{{ $max_price }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary btn-sm w-100">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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


{{-- GRID CARD PRODUK --}}
<div class="row g-3">
@forelse($products as $p)
    <div class="col-5-custom">
        <div class="card h-100 shadow-sm">

            {{-- GAMBAR --}}
            @if($p->image)
                <img src="{{ asset('img/' . $p->image) }}"
                     class="card-img-top"
                     style="height: 180px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/300x180?text=No+Image"
                     class="card-img-top">
            @endif

            <div class="card-body d-flex flex-column p-3">
                <h6 class="card-title mb-1">{{ $p->name }}</h6>

                <small class="text-muted">
                    @if($p->category_id == 1)
                        Baju
                    @elseif($p->category_id == 2)
                        Celana
                    @elseif($p->category_id == 3)
                        Jaket
                    @endif
                </small>

                <strong class="text-success mt-1 mb-2">
                    Rp {{ number_format($p->price, 0, ',', '.') }}
                </strong>

                <div class="mt-auto">
                    <a href="{{ route('products.show', $p->id) }}"
                       class="btn btn-outline-primary btn-sm-custom w-100 mb-2">
                        Detail
                    </a>

                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('products.edit', $p->id) }}"
                            class="btn btn-warning btn-sm-custom w-100">
                                ✏️ Edit Produk
                            </a>
                        @else
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id }}">

                                <button type="submit" class="btn btn-primary w-100">
                                    + Keranjang
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                        class="btn btn-primary btn-sm-custom w-100">
                            Login untuk + Keranjang
                        </a>
                    @endauth

                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-warning text-center">
            Produk tidak ditemukan
        </div>
    </div>
@endforelse
</div>
@endsection

