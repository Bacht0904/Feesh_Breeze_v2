@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Danh sách đánh giá</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Trang chủ</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Đánh giá</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>Người đánh giá</th>
                                <th>Nội dung</th>
                                <th>Đánh giá</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->product->name ?? '---' }}</td>
                                    <td>{{ $review->user->name ?? 'Ẩn' }}</td>
                                    <td>{{ $review->comment }}</td>
                                    <td>{{ $review->rating ?? 'Chưa đánh giá' }}</td>
                                    <td>
                                        {{ $review->status }}
                                            
                                    </td>
                                    <td>
                                        <div class="list-icon-function">
                                            
                                            <form method="POST" action="{{ route('admin.review.delete', $review->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="item delete text-danger" type="submit" onclick="return confirm('Xóa đánh giá này?')">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
