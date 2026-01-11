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
        margin : 0 0.7rem 0 -0.9rem;
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

    .alp-header .alp-topbar .alp-left-icon {
        display: flex;
        justify-content: center;
        align-items: center;
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

    /* Dropodwn bahasa */
    .lang-dropdown {
        position: relative;
    }

    .lang-toggle {
        background: none;
        border: none;
        cursor: pointer;
        font: inherit;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .lang-menu {
        position: absolute;
        top: 120%;
        left: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        min-width: 140px;
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
        display: none;
        z-index: 999;
    }

    .lang-menu a {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        color: #333;
    }

    .lang-menu a:hover {
        background: #f5f5f5;
    }

    /* hp */
    @media (max-width: 767px) {

        .alp-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 10px;
            font-size: 12px;
            gap: 10px;
        }

        /* bahasa + notif */
        .alp-left-icon {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        /* welcome + logout */
        .right-auth {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .welcome-text {
            font-size: 11px;
        }

        .logout-form button {
            font-size: 11px;
            padding: 2px 6px;
        }

        /* badge notif */
        .alp-topbar .badge {
            font-size: 9px;
            padding: 2px 4px;
        }

        .alp-navbar {
            flex-wrap: wrap;
            gap: 14px;
            padding: 14px 16px;
        }

        .alp-logo {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .alp-logo a {
            font-size: 22px;
        }

        .category-tabs {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            gap: 20px;
            padding-bottom: 6px;
        }

        .category-tabs::-webkit-scrollbar {
            display: none;
        }

        /* SEARCH + ICONS */
        .alp-filter {
            width: 100%;
            display: flex;
            gap: 10px;
        }

        .alp-search {
            flex: 1;
        }

        .alp-search input {
            width: 100%;
        }

        .alp-icons {
            gap: 14px;
            font-size: 18px;
        }
    }

    /* Tablet */
    @media (min-width: 768px) and (max-width: 1023px) {

        .alp-navbar {
            gap: 22px;
        }

        /* CATEGORY */
        .category-tabs {
            gap: 30px;
            margin-right: 20px;
        }

        .alp-search input {
            width: 90px;
            padding-left: 6px;
            font-size: 9px;
        }

        .alp-search input::placeholder {
            content: 'Cari';
        }

        .alp-search button {
            padding: 0 10px;
        }

        .alp-icons {
            gap: 14px;
        }
    }
</style>

<header class="alp-header">
    {{-- TOP BAR --}}
    <div class="alp-topbar">
        <div class="alp-left-icon">
            {{-- bahasa --}}
            <div class="left-links lang-dropdown">
                <button class="lang-toggle" id="langToggle">
                    <i data-feather="globe"></i>
                    {{ __('ui.language') }}
                </button>

                <div class="lang-menu" id="langMenu">
                    <a href="/lang/id">🇮🇩 Indonesia</a>
                    <a href="/lang/en">🇺🇸 English</a>
                </div>
            </div>
            {{-- notification --}}
            <div class="position-relative">
                <a href="{{ route('notifications.index') }}" class="notification">
                    <i data-feather="bell"></i>

                    @auth
                        @php
                            $unread = auth()->user()->unreadNotifications()->count();
                        @endphp

                        @if($unread > 0)
                            <span class="position-absolute top-0 start-100 translate-middle
                                        badge rounded-pill bg-danger">
                                {{ $unread }}
                            </span>
                        @endif
                    @endauth
                </a>
            </div>
        </div>

        <div class="right-auth">
            @auth
                <span class="welcome-text">
                    {{ __('ui.welcome') }}, {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">{{ __('ui.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}">{{ __('ui.login') }}</a>
                <a href="{{ route('register') }}">{{ __('ui.register') }}</a>
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
                        {{ __('ui.add_product') }}
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
                {{ __('ui.all') }}
            </a>

            <a class="{{ $currentCategory == 'jaket' ? 'active' : '' }}"
            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => 'jaket'])) }}">
                {{ __('ui.jacket') }}
            </a>

            <a class="{{ $currentCategory == 'baju' ? 'active' : '' }}"
            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => 'baju'])) }}">
                {{ __('ui.shirt') }}
            </a>

            <a class="{{ $currentCategory == 'celana' ? 'active' : '' }}"
            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => 'celana'])) }}">
                {{ __('ui.pants') }}
            </a>
        </nav>

        {{-- SEARCH --}}
        <div class="alp-filter">
            <form action="{{ route('products.index') }}" method="GET" class="alp-search">
                <input type="text" name="search" placeholder="{{ __('ui.search') }}"
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

<div id="filterOverlay"></div>

<div id="filterSidebar">
    <div class="filter-header">
        <h3>{{ __('ui.filter') }}</h3>
        <button id="closeFilter">✕</button>
    </div>

    <form method="GET" action="{{ route('products.index') }}">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="category" value="{{ request('category') }}">

        {{-- SORT --}}
        <div class="filter-group">
            <h4>{{ __('ui.sort_by') }}</h4>

            <label>
                <input type="radio" name="sort" value="name"
                    {{ request('sort') === 'name' ? 'checked' : '' }}>
                {{ __('ui.filter_name') }}
            </label>

            <label>
                <input type="radio" name="sort" value="price"
                    {{ request('sort') === 'price' ? 'checked' : '' }}>
                {{ __('ui.filter_price') }}
            </label>
        </div>

        {{-- ORDER --}}
        <div class="filter-group">
            <h4>{{ __('ui.sort') }}</h4>

            <label>
                <input type="radio" name="order" value="asc"
                    {{ request('order') === 'asc' ? 'checked' : '' }}>
                {{ __('ui.a-z') }}
            </label>

            <label>
                <input type="radio" name="order" value="desc"
                    {{ request('order') === 'desc' ? 'checked' : '' }}>
                {{ __('ui.z-a') }}
            </label>
        </div>

        {{-- RANGE HARGA --}}
        <div class="filter-group">
            <h4>{{ __('ui.price') }}</h4>

            <input type="number" name="min_price" placeholder="Min"
                value="{{ request('min_price') }}">

            <input type="number" name="max_price" placeholder="Max"
                value="{{ request('max_price') }}">
        </div>

        <button type="submit" class="apply-filter">
            {{ __('ui.filter_true') }}
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
                            {{ __('ui.shirt') }}
                        @elseif($p->category_id == 2)
                            {{ __('ui.pants') }}
                        @elseif($p->category_id == 3)
                            {{ __('ui.jacket') }}
                        @endif
                    </small>

                    {{-- <small class="text-muted">
                        {{ __('ui.stock') }} : {{ $p->stock }}
                    </small> --}}

                    <strong class="text-success mt-1 mb-2">
                        Rp {{ number_format($p->price, 0, ',', '.') }}
                    </strong>

                    <div class="mt-auto card-button">
                        
                        @auth
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('products.edit', $p->id) }}"
                            class="btn btn-edit btn-warning btn-sm-custom w-50">
                            {{ __('ui.edit') }}
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
                            {{ __('ui.detail') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                {{ __('ui.product_not_found') }}
            </div>
        </div>
    @endforelse
    </div>
</div>

<footer class="alp-footer">
    @if ($products->hasPages())
        <div class="alp-pagination d-flex align-items-center pagination-top ms-2">
            <div>
                <h6>{{ __('ui.pagination_text') }}</h6>
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

    // Dropdown bahasa
    document.addEventListener('click', function (e) {
        const toggle = document.getElementById('langToggle');
        const menu = document.getElementById('langMenu');

        if (toggle.contains(e.target)) {
            menu.style.display =
                menu.style.display === 'block' ? 'none' : 'block';
        } else {
            menu.style.display = 'none';
        }
    });
</script>
@endsection

