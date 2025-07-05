<!-- @extends('layouts.admin')

@section('content')
<div class="container">
    <h4 class="mb-3">Tất cả thông báo</h4>

    <ul class="list-group">
        @forelse ($notifications as $notification)
        <li class="list-group-item">
            <strong>{{ $notification->data['message'] ?? 'Thông báo' }}</strong><br>
            Mã đơn: #{{ $notification->data['order_id'] ?? 'N/A' }}<br>
            <small>{{ $notification->created_at->diffForHumans() }}</small>
        </li>
        @empty
        <li class="list-group-item">Bạn chưa có thông báo nào.</li>
        @endforelse
    </ul>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection -->
@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tất cả thông báo</h3>
            <!-- <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Trang chủ</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Phiếu giảm giá</div>
                    </li>
                </ul> -->
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Tìm kiếm..." class="" name="name" tabindex="2" value=""
                                aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>

            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>

                        </thead>
                        <tbody>
                            <ul class="list-group">
                                @forelse ($notifications as $notification)
                                <li class="list-group-item">
                                    <strong>{{ $notification->data['message'] ?? 'Thông báo' }}</strong><br>
                                    Mã đơn: #{{ $notification->data['order_id'] ?? 'N/A' }}<br>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </li>
                                @empty
                                <li class="list-group-item">Bạn chưa có thông báo nào.</li>
                                @endforelse
                            </ul>

                            <div class="mt-3">
                                {{ $notifications->links() }}
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

            </div>
        </div>
        @foreach ($notifications as $notification)
        <div class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
            <strong>{{ $notification->data['message'] }}</strong><br>
            <span>Mã đơn: #{{ $notification->data['order_id'] }}</span><br>
            <span class="text-muted">{{ $notification->created_at->diffForHumans() }}</span>
        </div>
        @endforeach

    </div>
</div>
@endsection