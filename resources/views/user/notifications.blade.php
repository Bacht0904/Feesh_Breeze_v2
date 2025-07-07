@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
  <div class="main-content-wrap" style="max-width: 600px; width: 100%;">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold m-0">Thông báo</h4>
    </div>

    @if($notifications->count())
      <div class="list-group rounded shadow-sm">
        @foreach ($notifications as $notification)
          @php
            $orderId = $notification->data['order_id'] ?? null;
            $message = $notification->data['message'] ?? 'Thông báo';
            $created = $notification->created_at->diffForHumans();
            $isUnread = is_null($notification->read_at);
          @endphp

          <a href="{{ $orderId ? route('orders.details', $orderId) : '#' }}"
             class="list-group-item d-flex justify-content-between align-items-center small notification-link {{ $isUnread ? 'fw-semibold bg-light' : 'text-muted' }}"
             style="padding: 0.5rem 0.75rem;"
             data-id="{{ $notification->id }}">
            <span class="text-truncate" style="max-width: 80%;">{{ $message }}</span>
            <small class="text-nowrap">{{ $created }}</small>
          </a>
        @endforeach
      </div>

      <div class="mt-3">
        {{ $notifications->links() }}
      </div>
    @else
      <div class="alert alert-secondary text-center">
        Bạn chưa có thông báo nào.
      </div>
    @endif

  </div>
</div>
@endsection
