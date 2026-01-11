@extends('layouts.app')

@section('content')
<div class="container">
    <h4>{{ __('ui.refund') }}</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" 
          action="{{ route('orders.return.store', $order) }}"
          enctype="multipart/form-data">

        @csrf

        <h5>{{ __('ui.product_refunded') }}</h5>

        @foreach ($order->items as $item)
            <div class="border p-3 mb-2">
                <label>
                    <input type="checkbox"
                           name="items[{{ $loop->index }}][product_id]"
                           value="{{ $item->product_id }}">
                    {{ $item->product->name }}
                </label>

                <input type="number"
                       name="items[{{ $loop->index }}][qty]"
                       min="1"
                       max="{{ $item->qty }}"
                       class="form-control mt-1"
                       placeholder="Jumlah (max {{ $item->qty }})">
            </div>
        @endforeach

        <div class="mb-3">
            <label>{{ __('ui.reason') }}</label>
            <input type="text" name="reason" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>{{ __('ui.explain') }}</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>{{ __('ui.resi_image') }}</label>
            <input type="file" name="receipt_image" class="form-control" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label>{{ __('ui.unboxing_videos') }}</label>
            <input type="file" name="unboxing_video" class="form-control" accept="video/*" required>
        </div>

        <button class="btn btn-danger">
            {{ __('ui.add_refunded') }}
        </button>
    </form>
</div>
@endsection
