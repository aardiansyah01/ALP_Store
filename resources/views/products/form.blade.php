@extends('layouts.app')

@section('title', isset($product) ? 'Edit Product' : 'Create Product')

@section('content')

<h2>{{ isset($product) ? 'Edit Product' : 'Create New Product' }}</h2>
<hr>

<form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST">
    @csrf
    @if(isset($product))
        @method('PUT')
    @endif

    <div class="mb-3">
        <label class="form-label">Nama produk</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="price" class="form-control"
               value="{{ old('price', $product->price ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Warna</label>
        <input type="text" name="color" class="form-control"
               value="{{ old('color', $product->color ?? '') }}" required>
    </div>

    @php
    $sizes = ['S','M','L','XL','XXL'];
    $selectedSizes = old('sizes', $product->sizes ?? []);
    @endphp

    <div class="mb-3">
        <label class="form-label">Ukuran</label>
        <div class="d-flex gap-3 flex-wrap">
            @foreach($sizes as $size)
                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="sizes[]"
                        value="{{ $size }}"
                        {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $size }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control"
            value="{{ old('stock', $product->stock ?? 0) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Lokasi</label>
        <input type="text" name="location" class="form-control"
            value="{{ old('location', $product->location ?? '') }}"
            placeholder="Gudang / Rak / Toko">
    </div>

    {{-- DROPDOWN KATEGORI --}}
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $id => $name)
                <option value="{{ $id }}"
                    {{ old('category_id', $product->category_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Gambar</label>
        <input type="text" name="image" class="form-control"
               value="{{ old('image', $product->image ?? '') }}">
        <small class="text-muted">
            Masukkan nama file gambar di folder <b>/public/img/</b>
        </small>
    </div>

    @if(isset($product) && $product->image)
        <div class="mb-3">
            <label class="form-label">Gambar Saat Ini</label><br>
            <img src="{{ asset('img/' . $product->image) }}" width="200" class="border rounded">
        </div>
    @endif

    <button type="submit" class="btn btn-primary">
        {{ isset($product) ? 'Update Product' : 'Create Product' }}
    </button>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>

</form>

@endsection


