@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slider</h3>
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
                        <div class="text-tiny">Slider</div>
                    </li>
                </ul>
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
                    <a class="tf-button style-1 w208" href="{{ route('admin.slide.add') }}"><i class="icon-plus"></i>Thêm mới</a>
                </div>
                <div class="wg-table table-all-user">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hình ảnh</th>
                                <th>Dòng giới thiệu</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
                                <th>Link</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>3</td>
                                <td class="pname">
                                    <div class="image">
                                        <img src="1718066840.html" alt="" class="image">
                                    </div>
                                </td>
                                <td>New Arrivals</td>
                                <td>Night Spring</td>
                                <td>Dresses</td>
                                <td>https://www.google.com</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="http://localhost:8000/admin/slider/3/edit">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="http://localhost:8000/admin/slider/3/delete" method="POST">
                                            <input type="hidden" name="_token"
                                                value="8LNRTO4LPXHvbK2vgRcXqMeLgqtqNGjzWSNru7Xx" autocomplete="off"> <input
                                                type="hidden" name="_method" value="DELETE">
                                            <div class="item text-danger delete">
                                                <i class="icon-trash-2"></i>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>
        </div>
    </div>
@endsection