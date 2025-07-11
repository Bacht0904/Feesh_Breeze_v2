<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('app.name', 'Laravel')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="surfside media" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('mation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico')}}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
<<<<<<<<< Temporary merge branch 1
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote.min.css" rel="stylesheet">
=========
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
>>>>>>>>> Temporary merge branch 2
    @stack('styles')
    <style>
        #toast-container>.toast {
            font-size: 18px !important;
            /* tăng cỡ chữ */
            padding: 20px 30px !important;
            /* tăng padding */
            border-radius: 8px !important;
            /* bo góc nhẹ */
        }
    </style>

</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">

                <!-- <div id="preload" class="preload-container">
    <div class="preloading">
        <span></span>
    </div>
</div> -->

                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="{{route('admin.index')}}" id="site-logo-inner">
                            <img class="" id="logo_header_mobile" alt="" src={{ asset('images/logo/logo.png') }}
                                data-light={{ asset('images/logo/logo.png') }} data-dark={{ asset('images/logo/logo.png') }}
                                data-width="154px" data-height="52px" data-retina={{ asset('images/logo/logo.png') }}>
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Main Home</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{route('admin.index')}}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Trang chủ</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-shopping-cart"></i></div>
                                        <div class="text">Sản phẩm</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.product.add') }}" class="">
                                                <div class="text">Thêm sản phẩm</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.products') }}" class="">
                                                <div class="text">Danh sách sản phẩm</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Thương hiệu</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.brand.add') }}" class="">
                                                <div class="text">Thêm thương hiệu</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.brands') }}" class="">
                                                <div class="text">Danh sách thương hiệu</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-layers"></i></div>
                                        <div class="text">Loại sản phẩm</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{ route('admin.category.add') }}" class="">
                                                <div class="text">Thêm loại sản phẩm</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.categories')}}" class="">
                                                <div class="text">Danh sách loại sản phẩm</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.orders') }}" class="">
                                        <div class="icon"><i class="icon-file-plus"></i></div>
                                        <div class="text">Đơn hàng</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.sliders') }}" class="">
                                        <div class="icon"><i class="icon-image"></i></div>
                                        <div class="text">Slider</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.banners') }}" class="">
                                        <div class="icon"><i class="icon-image"></i></div>
                                        <div class="text">Banner</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('admin.coupons') }}" class="">
                                        <div class="icon"><i class="icon-grid"></i></div>
                                        <div class="text">Phiếu giảm giá</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a href="{{ route('admin.users') }}" class="">
                                        <div class="icon"><i class="icon-user"></i></div>
                                        <div class="text">Người dùng</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a href="{{ route('admin.settings') }}" class="">
                                        <div class="icon"><i class="icon-settings"></i></div>
                                        <div class="text">Cài đặt</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <form method="post" action="{{route('logout')}}" id="logout-form">
                                        @csrf
                                        <a href="{{route('logout')}}" class=""
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <div class="icon"><i class="icon-log-out"></i></div>

                                            <div class="text">Đăng xuất</div>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section-content-right">

                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="{{ route('admin.index') }}">
                                    <img class="" id="logo_header_mobile" alt="" src={{ asset('images/logo/logo.png') }}
                                        data-light={{ asset('images/logo/logo.png') }} data-dark={{ asset('images/logo/logo.png') }}
                                        data-width="154px" data-height="52px" data-retina={{ asset('images/logo/logo.png') }}>
                                </a>
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>
                            </div>
                            <div class="header-grid">

                                <!-- <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">1</span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Thông báo</h6>
                                            </li>
                                            <li>
                                                <div class="message-item item-1">
                                                    <div class="image">
                                                        <i class="icon-noti-1"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Discount available</div>
                                                        <div class="text-tiny">Morbi sapien massa, ultricies at rhoncus
                                                            at, ullamcorper nec diam</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-3">
                                                    <div class="image">
                                                        <i class="icon-noti-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Order shipped successfully</div>
                                                        <div class="text-tiny">Integer aliquam eros nec sollicitudin
                                                            sollicitudin</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-4">
                                                    <div class="image">
                                                        <i class="icon-noti-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Order pending: <span>ID 305830</span>
                                                        </div>
                                                        <div class="text-tiny">Ultricies at rhoncus at ullamcorper</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li><a href="#" class="tf-button w-full">View all</a></li>
                                        </ul>
                                    </div>
                                </div> -->

                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">{{ auth()->user()->unreadNotifications->count() }}</span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <!-- <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Thông báo</h6>
                                            </li>

                                            @forelse(auth()->user()->unreadNotifications as $notification)
                                            <li>
                                                <div class="message-item">
                                                    <div class="image">
                                                        <i class="icon-noti-{{ $loop->iteration }}"></i> {{-- bạn có thể tùy chọn icon khác nhau --}}
                                                    </div>
                                                    <div>
                                                        @if(isset($notification->data['order_id']))
                                                        <a href="{{ route('admin.order.detail', $notification->data['order_id']) }}">
                                                            {{ $notification->data['message'] ?? 'Thông báo mới' }}
                                                        </a>
                                                        @else
                                                        {{ $notification->data['message'] ?? 'Thông báo mới' }}
                                                        @endif

                                                        <div class="text-tiny">
                                                            Đơn hàng #{{ $notification->data['order_id'] ?? '---' }}<br>
                                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @empty
                                            <li>
                                                <span class="dropdown-item">Không có thông báo mới</span>
                                            </li>
                                            @endforelse

                                            <li>
                                                <a href="{{ route('notifications') }}" class="tf-button w-full">Xem tất cả</a>
                                            </li>
                                        </ul> -->
                                        <ul class="dropdown-menu dropdown-menu-end has-content" aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Thông báo</h6>
                                            </li>

                                            @forelse(auth()->user()->unreadNotifications as $notification)
                                            @php
                                            $orderId = $notification->data['order_id'] ?? null;
                                            $message = $notification->data['message'] ?? 'Thông báo mới';
                                            @endphp

                                            <li>
                                                <a href="{{ $orderId ? route('admin.order.detail', $orderId) : '#' }}"
                                                    class="dropdown-item d-flex align-items-start gap-2 small text-wrap">
                                                    <i class="icon-noti-{{ $loop->iteration }}"></i>

                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold text-dark">{{ $message }}</div>
                                                        <div class="text-muted">
                                                            Đơn hàng #{{ $orderId ?? '---' }}<br>
                                                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                            @empty
                                            <li><span class="dropdown-item text-muted">Không có thông báo mới</span></li>
                                            @endforelse

                                            <li>
                                                <a href="{{ route('notifications') }}" class="tf-button w-full text-center">Xem tất cả</a>
                                            </li>
                                        </ul>

                                    </div>
                                </div>





                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="">
                                                    <img src="{{ Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)) ? asset(Auth::user()->avatar) : asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                                                    <span class="text-tiny">{{ Auth::user()->role }}</span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="{{ route('admin.settings') }}" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Tài khoản</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.contacts') }}" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-mail"></i>
                                                    </div>
                                                    <div class="body-title-2">Liên hệ</div>
                                                    <div class="number">{{ $contactCount }}</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.comments') }}" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-message-square"></i>
                                                    </div>
                                                    <div class="body-title-2">Bình luận</div>
                                                </a>
                                            </li>

                                            <li>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>

                                                <a href="{{ route('logout') }}" class="user-item"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <div class="icon">
                                                        <i class="icon-log-out"></i>
                                                    </div>
                                                    <div class="body-title-2">Đăng xuất</div>
                                                </a>

                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        @yield('content')

                        <div class="bottom-page">
                            <div class="body-text">Feesh_Breeze_V2 2025 © Made by DucVu</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- jQuery (bắt buộc trước Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if(session('status'))
        toastr.success("{{ session('status') }}");
        @endif

        @if($errors -> any())
        toastr.error("{{ $errors->first() }}");
        @endif
    </script>
    <script>
        function confirmStatusChange(status) {
            Swal.fire({
                title: 'Bạn có chắc?',
                text: "Thay đổi trạng thái đơn hàng thành '" + status + "'?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('statusInput').value = status;
                    document.getElementById('orderStatusForm').submit();
                }
            })
        }
    </script>
>>>>>>>>> Temporary merge branch 2

    @stack('scripts')
</body>

</html>