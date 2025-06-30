@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách tài khoản</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Trang chủ</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Danh sách tài khoản</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" action="{{ route('admin.users.search') }}" method="GET">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm..." class="" name="name" tabindex="2" value="{{ request()->input('name', '') }}"
                                    aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    @auth
                        @if (Auth::user()->role === 'admin')
                            <a class="tf-button style-1 w208" href="{{ route('admin.user.add') }}">
                                <i class="icon-plus"></i> Thêm mới
                            </a>
                        @endif
                    @endauth
                </div>
                <div class="wg-table table-all-user">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Trạng thái</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th>Ảnh đại diện</th>
                                    @auth
                                        @if (Auth::user()->role === 'admin')
                                            <th>Thao tác</th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role == 'admin')
                                                <span class="text-primary">Quản trị viên</span>
                                            @elseif ($user->role == 'staff')
                                                <span class="text-secondary">Nhân viên</span>
                                            @else
                                                <span class="text-success">Người dùng</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->status == "active")
                                                <span class="text-success">Hoạt động</span>
                                            @else
                                                <span class="text-danger">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>
                                            @if ($user->avatar)
                                                <img src="{{ asset($user->avatar) }}" alt="Avatar" width="50">
                                            @else
                                                <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" width="50">
                                            @endif
                                        </td>
                                        @auth
                                            @if (Auth::user()->role === 'admin')
                                                <td>
                                                    <div class="list-icon-function">
                                                        <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}">
                                                            <div class="item edit">
                                                                <i class="icon-edit-3"></i>
                                                            </div>
                                                        </a>
                                                        <form action="{{ route('admin.user.delete', ['id' => $user->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="item text-danger delete"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                                <i class="icon-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        @endauth

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>
        </div>
    </div>
@endsection