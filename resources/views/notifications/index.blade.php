@extends('layouts.app')

@section('content')

<style>
    .notification-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    margin-bottom: 12px;
    border-radius: 12px;
    background: #fff;
    text-decoration: none;
    color: #000;
    position: relative;
    transition: all .2s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,.04);
    }

    .notification-item:hover {
        background: #f8f9fa;
        transform: translateY(-1px);
    }

    .notification-item.unread {
        background: #eef4ff;
    }

    .notification-item .icon {
        font-size: 22px;
    }

    .notification-item .content {
        flex: 1;
    }

    .notification-item .message {
        font-weight: 500;
    }

    .notification-item .time {
        font-size: 13px;
        color: #6c757d;
        margin-top: 4px;
    }

    .notification-item .dot {
        width: 10px;
        height: 10px;
        background: #dc3545;
        border-radius: 50%;
    }
</style>

<div class="container my-5" style="max-width: 700px">

    <h4 class="mb-4 fw-bold">
       <i data-feather="bell"></i> Notifikasi
    </h4>

    @forelse ($notifications as $notification)
        <a href="{{ $notification->data['url'] }}"
           class="notification-item
                  {{ is_null($notification->read_at) ? 'unread' : '' }}"
                   >

            <div class="icon">
                @if(str_contains($notification->data['message'], 'Produk'))
                    <i data-feather="plus"></i>
                @elseif(str_contains($notification->data['message'], 'Dibatalkan'))
                    <i data-feather="x"></i>
                @else
                    <i data-feather="package"></i>
                @endif
            </div>

            <div class="content">
                <div class="message">
                    {{ $notification->data['message'] }}
                </div>
                <div class="time">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>

            @if(is_null($notification->read_at))
                <span class="dot"></span>
            @endif
        </a>
    @empty
        <div class="alert alert-light text-center text-muted">
            Tidak ada notifikasi
        </div>
    @endforelse

    <a href="{{ route('products.index') }}" class="btn btn-secondary">{{ __('ui.back') }}</a>
</div>
@endsection

