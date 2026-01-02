@extends('layouts.app')

@section('title', 'Product List')

@section('content')

<style>

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* 5 card per row */
    .col-5-custom {
        width: 20%;
    }

    .container, .container-lg, .container-md, .container-sm, .container-xl {
        max-width: 1240px;
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

    .card-button{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 1px;
    }

    /* Kecilkan tombol di card */
    .btn-sm-custom {
        padding: 7px 35px;
        font-size: 13px;
    }

    .btn-cart-custom{
        padding: 3px 8px;
    }

    .btn-cart-custom a{
        outline: none;
        text-decoration: none;
        color: #000
    }

    .btn-edit{
        margin : 0 1rem 0 -0.8rem;
    }

    /* Kecilkan pagination */
    svg {
        width: 1rem;
    }

    /* RESET */
    .alp-header a {
        text-decoration: none;
        color: inherit;
    }

    .alp-header {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background-color: azure;
        padding-bottom: 1rem;
        margin-bottom: 4.5rem;
    }

    /* TOP BAR */
    .alp-topbar {
        display: flex;
        justify-content: space-between;
        padding: 8px 40px;
        font-size: 14px;
        background-color: rgba(240, 255, 255, 0.744);
    }

    .alp-topbar a {
        margin-right: 15px;
        color: #000;
    }

    .right-auth {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logout-form button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    /* MAIN NAVBAR */
    .alp-navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        padding: 18px 40px;
        border-top: 1px solid #eee;
    }

    /* LOGO */
    .alp-logo a {
        font-size: 26px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .alp-logo .btn-add {
        font-size: 15px;
        margin-left: 15px;
    }

    /* MENU */
    .category-tabs {
    display: flex;
    gap: 30px;
    align-items: center;
    }

    .category-tabs a {
        text-decoration: none;
        color: #000;
        font-weight: 600;
        padding-bottom: 6px;
        position: relative;
    }

    .category-tabs a::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 0;
        background: #000;
        transition: 0.3s;
    }

    .category-tabs a:hover::after,
    .category-tabs a.active::after {
        width: 100%;
    }

    .alp-filter{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* SEARCH */
    .alp-search {
        flex: 0 0 auto;
        display: flex;
        width: fit-content;
    }

    .alp-search input {
        padding: 10px 0 10px 5px;
        border: none;
        outline: none;
        border: 0px 1px 1px 1px solid #ddd;
        border-radius: 10px 0 0 10px;
        border-right: 0px;
        width: 70%;
    }

    .alp-search button {
        padding: 0 15px;
        border: none;
        background: white;
        cursor: pointer;
        border: 1px solid #ddd;
        border-radius: 0px 10px 10px 0;
        border-left: 0px;
    }

    /* ICONS */
    .alp-icons {
        display: flex;
        gap: 18px;
        font-size: 20px;
    }

    .alp-icons button {
        background: none;
        border: none;
        cursor: pointer;
    }

    /* OVERLAY */
    #filterOverlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        pointer-events: none;
        transition: 0.3s;
        z-index: 998;
    }

    #filterOverlay.active {
        opacity: 1;
        pointer-events: all;
    }

    /* SIDEBAR */
    #filterSidebar {
        position: fixed;
        top: 0;
        right: -420px;
        width: 380px;
        height: 100vh;
        background: #fff;
        padding: 25px;
        transition: 0.3s ease;
        z-index: 999;
        display: flex;
        flex-direction: column;
    }

    #filterSidebar.active {
        right: 0;
    }

    /* HEADER */
    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-header button {
        background: none;
        border: none;
        font-size: 22px;
        cursor: pointer;
    }

    /* GROUP */
    .filter-group {
        margin-bottom: 25px;
    }

    .filter-group h4 {
        margin-bottom: 10px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 8px;
        cursor: pointer;
    }

    /* INPUT */
    .filter-group input[type="number"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
    }

    /* BUTTON */
    .apply-filter {
        margin-top: auto;
        padding: 14px;
        background: black;
        color: white;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }

    .alp-footer {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background-color: azure;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .alp-footer .alp-pagination{
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.5rem;
    }
    
</style>

{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> --}}

{{-- <div class="alp-header mb-4">

    {{-- TOP BAR --}}
    {{-- <div class="top-bar d-flex justify-content-between align-items-center px-4">
        <div class="top-left">
            {{-- <a href="#"><h6>Home | </h6></a> --}}
            {{-- @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('orders.index') }}" title="Pesanan Saya">
                        <i data-feather="package"></i>
                    </a>
                @else
                    <a href="{{ route('orders.index') }}" title="Pesanan Saya">
                        <i data-feather="package"></i>
                    </a>
                @endif
            @endauth
        </div> --}}

        {{-- <div class="top-right">
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
    </div> --}}

    {{-- MAIN BAR --}}
    {{-- <div class="main-bar d-flex align-items-center px-4 py-3">
        {{-- <div class="bag-icon text-white fs-4">
            <i data-feather="shopping-bag"> </i>
        </div> --}}
        {{-- <div class="logo me-4">ALP STORE</div> --}}

        {{-- SEARCH --}}
        {{-- <form method="GET" class="search-box d-flex flex-grow-1 me-4">
            <input type="text" name="search" class="form-control"
                placeholder="Cari di ALP STORE" value="{{ $search }}">
            <button class="btn btn-light">
                <i data-feather="search"> </i>
            </button> --}}
        {{-- </form> --}}

        {{-- CART ICON --}}
        {{-- <div class="cart-icon text-white fs-4">
            <a href="{{ route('cart.index') }}" class="cart-icon position-relative">
                <i data-feather="shopping-cart"> </i>
            </a>
        </div>
    </div> --}}

    {{-- FILTER BAR --}}
    {{-- <div class="filter-bar d-flex justify-content-between align-items-center px-4 py-3">
        <div class="">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                        + Tambah Produk
                    </a>
                @endif
            @endauth --}}

            {{-- FILTER HARGA --}}
            {{-- <button class="btn btn-primary btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#filterRange">
                Filter Harga
            </button>
        </div> --}}

        {{-- <div class="d-flex align-items-center"> --}}

            {{-- SORTING --}}
            {{-- <form method="GET" class="d-flex">
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
                </select> --}}

                {{-- <button class="btn btn-dark btn-sm">Urutkan</button>
            </form> --}}
            {{-- PAGINATION --}}
            {{-- @if ($products->hasPages())
            <div class="d-flex align-items-center pagination-top ms-2">
                <span class="me-2">
                    {{ $products->currentPage() }}/{{ $products->lastPage() }}
                </span>

                <a href="{{ $products->previousPageUrl() ?? '#' }}"
                class="btn btn-light btn-sm me-1 {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    ‹
                </a> --}}

                {{-- <a href="{{ $products->nextPageUrl() ?? '#' }}"
                class="btn btn-light btn-sm {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                    ›
                </a>
            </div>
            @endif
        </div>
    </div> --}}
{{-- </div>--}}

<header class="alp-header">
    {{-- TOP BAR --}}
    <div class="alp-topbar">
        <div class="left-links">
            <a href="#">
                <i data-feather="globe"></i>
                Bahasa
            </a>
        </div>

        <div class="right-auth">
            @auth
                <span class="welcome-text">
                    Welcome, {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>

    {{-- MAIN NAVBAR --}}
    <div class="alp-navbar">
        {{-- LOGO --}}
        <div class="alp-logo">
            <a href="{{ route('products.index') }}">ALP STORE</a>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn-add btn btn-success btn-sm">
                        + Tambah Produk
                    </a>
                @endif
            @endauth
        </div>

        {{-- CATEGORY MENU --}}
        @php
            $currentCategory = request('category');
        @endphp

        <nav class="category-tabs">
            <a class="{{ !$currentCategory ? 'active' : '' }}"
            href="{{ route('products.index', request()->except('category','page')) }}">
                Semua
            </a>

            <a class="{{ $currentCategory == 'jaket' ? 'active' : '' }}"
            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => 'jaket'])) }}">
                Jaket
            </a>

            <a class="{{ $currentCategory == 'baju' ? 'active' : '' }}"
            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => 'baju'])) }}">
                Baju
            </a>

            <a class="{{ $currentCategory == 'celana' ? 'active' : '' }}"
            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => 'celana'])) }}">
                Celana
            </a>
        </nav>

        {{-- SEARCH --}}
        <div class="alp-filter">
            <form action="{{ route('products.index') }}" method="GET" class="alp-search">
                <input type="text" name="search" placeholder="Cari di ALP STORE"
                    value="{{ request('search') }}">
                <button type="submit">
                    <i data-feather="search"></i>
                </button>
            </form>

            {{-- ICONS --}}
            <div class="alp-icons">
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.orders.index') }}" title="Pesanan Saya">
                            <i data-feather="package"></i>
                        </a>
                    @else
                        <a href="{{ route('orders.index') }}" title="Pesanan Saya">
                            <i data-feather="package"></i>
                        </a>
                    @endif
                    <a href="{{ route('cart.index') }}" title="Keranjang">
                        <i data-feather="shopping-cart"></i>
                    </a>
                    <button id="openFilter" title="Filter">
                        <i data-feather="menu"></i>
                    </button>
                @endauth
            </div>
        </div>
    </div>
</header>


{{-- FILTER RANGE --}}
{{-- <div class="container_top">
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
</div> --}}
{{-- SIDEBAR FILTER --}}
<div id="filterOverlay"></div>

<div id="filterSidebar">
    <div class="filter-header">
        <h3>Filter & Urutkan</h3>
        <button id="closeFilter">✕</button>
    </div>

    <form method="GET" action="{{ route('products.index') }}">
        {{-- PERTAHANKAN SEARCH & CATEGORY --}}
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="category" value="{{ request('category') }}">

        {{-- SORT --}}
        <div class="filter-group">
            <h4>Urutkan Berdasarkan</h4>

            <label>
                <input type="radio" name="sort" value="name"
                    {{ request('sort') === 'name' ? 'checked' : '' }}>
                Nama
            </label>

            <label>
                <input type="radio" name="sort" value="price"
                    {{ request('sort') === 'price' ? 'checked' : '' }}>
                Harga
            </label>
        </div>

        {{-- ORDER --}}
        <div class="filter-group">
            <h4>Urutan</h4>

            <label>
                <input type="radio" name="order" value="asc"
                    {{ request('order') === 'asc' ? 'checked' : '' }}>
                A–Z / Murah → Mahal
            </label>

            <label>
                <input type="radio" name="order" value="desc"
                    {{ request('order') === 'desc' ? 'checked' : '' }}>
                Z–A / Mahal → Murah
            </label>
        </div>

        {{-- PRICE RANGE --}}
        <div class="filter-group">
            <h4>Harga</h4>

            <input type="number" name="min_price" placeholder="Min"
                value="{{ request('min_price') }}">

            <input type="number" name="max_price" placeholder="Max"
                value="{{ request('max_price') }}">
        </div>

        <button type="submit" class="apply-filter">
            Terapkan Filter →
        </button>
    </form>
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
<div class="container-fluid px-1 px-lg-1">
    <div class="row g-3">
    @forelse($products as $p)
        <div class="col-5-custom">
            <div class="card h-100 shadow-sm">

                {{-- GAMBAR --}}
                @if($p->image)
                    <img src="{{ asset('img/' . $p->image) }}"
                        class="card-img-top"
                        style="height: 250px; object-fit: cover;">
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

                    <small class="text-muted">
                        Stock : {{ $p->stock }}
                    </small>

                    <strong class="text-success mt-1 mb-2">
                        Rp : {{ number_format($p->price, 0, ',', '.') }}
                    </strong>

                    <div class="mt-auto card-button">
                        
                        @auth
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('products.edit', $p->id) }}"
                            class="btn btn-edit btn-warning btn-sm-custom w-50">
                            Edit 
                        </a>
                        @else
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $p->id }}">
                            
                            <button type="submit" class="btn btn-outline-warning btn-cart-custom">
                                <i data-feather="shopping-cart"></i>
                                +
                            </button>
                        </form>
                        @endif
                        @else
                            <button type="submit" class="btn btn-outline-danger btn-cart-custom">
                                <a href="{{ route('login') }}">
                                    <i data-feather="shopping-cart"></i>
                                    +
                                </a>
                            </button>
                        @endauth
                        
                        <a href="{{ route('products.show', $p->id) }}"
                        class="btn btn-outline-secondary btn-sm-custom">
                            Detail
                        </a>
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
</div>

<footer class="alp-footer">
    @if ($products->hasPages())
        <div class="alp-pagination d-flex align-items-center pagination-top ms-2">
            <div>
                <h6>Lanjut Ke Halaman Sebelah</h6>
            </div>
            <div>
                <span class="me-2">
                    {{ $products->currentPage() }}/{{ $products->lastPage() }}
                </span>

                <a href="{{ $products->previousPageUrl() ?? '#' }}"
                class="btn btn-outline-warning btn-md me-1 {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    ‹
                </a>

                <a href="{{ $products->nextPageUrl() ?? '#' }}"
                class="btn btn-outline-warning btn-md {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                    ›
                </a>
            </div>
        </div>
    @endif
</footer>

<script>
    const openFilter = document.getElementById('openFilter');
    const closeFilter = document.getElementById('closeFilter');
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('filterOverlay');

    openFilter.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
    });

    closeFilter.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });
</script>
@endsection

