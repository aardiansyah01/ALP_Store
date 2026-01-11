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
        <label class="form-label">{{ __('ui.filter_name') }}</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('ui.description') }}</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('ui.price') }}</label>
        <input type="number" name="price" class="form-control"
               value="{{ old('price', $product->price ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('ui.colour') }}</label>
        <input type="text" name="color" class="form-control"
               value="{{ old('color', $product->color ?? '') }}" required>
    </div>

    @php
    $sizes = ['S','M','L','XL','XXL'];
    $selectedSizes = old('sizes', $product->sizes ?? []);
    @endphp

    <div class="mb-3">
        <label class="form-label">{{ __('ui.size') }}</label>
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
        <label class="form-label fw-bold">{{ __('ui.stock') }}</label>

        @php
            $sizes = ['S','M','L','XL','XXL'];
            $stocks = old(
                'stocks',
                isset($product)
                    ? $product->stocks->pluck('stock','size')->toArray()
                    : []
            );
        @endphp

        <div class="row">
            @foreach($sizes as $size)
                <div class="col-md-2">
                    <label class="form-label">{{ $size }}</label>
                    <input
                        type="number"
                        name="stocks[{{ $size }}]"
                        class="form-control"
                        min="0"
                        value="{{ $stocks[$size] ?? 0 }}"
                    >
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('ui.location') }}</label>
        <input type="text" name="location" class="form-control"
            value="{{ old('location', $product->location ?? '') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('ui.category') }}</label>
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
        <label class="form-label">{{ __('ui.filter_name') }}</label>
        <input type="text" name="image" class="form-control"
               value="{{ old('image', $product->image ?? '') }}">
        <small class="text-muted">
            {{ __('ui.img_file') }} <b>/public/img/</b>
        </small>
    </div>

    @if(isset($product) && $product->image)
        <div class="mb-3">
            <label class="form-label">{{ __('ui.img_now') }}</label><br>
            <img src="{{ asset('img/' . $product->image) }}" width="200" class="border rounded">
        </div>
    @endif

    <button type="submit" class="btn btn-primary">
        {{ isset($product) ? 'Update Product' : 'Create Product' }}
    </button>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('ui.back') }}</a>

</form>

@endsection


