@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">

        <div class="main-content-wrap">
            <div
                style="width: 100%; background-color: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 32px;">
                <h4 style="font-weight: 600; font-size: 18px; margin-bottom: 20px; text-align: center; color: #333;">
                    Thống kê dữ liệu bán hàng
                </h4>

                <form method="GET" action="" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">

                    <!-- Chọn tháng -->
                    <div>
                        <select name="month" id="month"
                            style="padding: 10px 14px; border-radius: 8px; border: 1px solid #ccc; min-width: 140px;">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month', date('m')) == $i ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Chọn năm -->
                    <div>
                        <select name="year" id="year"
                            style="padding: 10px 14px; border-radius: 8px; border: 1px solid #ccc; min-width: 140px;">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                    Năm {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Nút submit -->
                    <div>
                        <button type="submit"
                            style="padding: 10px 20px; background-color: #007bff; color: white; border-radius: 8px; border: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            <i class="icon-filter"></i>
                            Lọc dữ liệu
                        </button>
                    </div>

                </form>
            </div>

            <div class="tf-section-2 mb-30">

                <div class="flex gap20 flex-wrap-mobile">

                    <div class="w-half">

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng hóa đơn</div>
                                        <h4>{{$dashboardDatas[0]->total}}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Chờ xác nhận</div>
                                        <h4>{{ $dashboardDatas[0]->totalOrdered }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Đã xác nhận</div>
                                        <h4>{{ $dashboardDatas[0]->totalConfirmed }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Đã giao hàng</div>
                                        <h4>{{ $dashboardDatas[0]->totalDelivered }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-half">
                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng số tiền</div>
                                        <h4>{{ number_format($dashboardDatas[0]->totalAmount, 0, ',', '.') }} VNĐ</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng số tiền chờ xác nhận</div>
                                        <h4>{{ number_format($dashboardDatas[0]->totalOrderedAmount, 0, ',', '.') }} VNĐ
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng số tiền đã xác nhận</div>
                                        <h4>{{ number_format($dashboardDatas[0]->totalConfirmedAmount, 0, ',', '.') }} VNĐ
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng số tiền đã giao</div>
                                        <h4>{{ number_format($dashboardDatas[0]->totalDeliveredAmount, 0, ',', '.') }} VNĐ
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="wg-box">
                    <div class="flex items-center justify-between">
                        <h5>Doanh thu tháng</h5>
                    </div>
                    <div class="flex flex-wrap gap40">
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t1"></div>
                                    <div class="text-tiny">Tổng</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>{{ number_format($totalAmount, 0, ',', '.') }} VNĐ</h4>

                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t2"></div>
                                    <div class="text-tiny">Chờ xác nhận</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>{{ number_format($totalOrderedAmount, 0, ',', '.') }} VNĐ</h4>

                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t2"></div>
                                    <div class="text-tiny">Đã xác nhận</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>{{ number_format($totalConfirmedAmount, 0, ',', '.') }} VNĐ</h4>

                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t2"></div>
                                    <div class="text-tiny">Đã giao</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>{{ number_format($totalDeliveredAmount, 0, ',', '.') }} VNĐ</h4>

                            </div>
                        </div>
                    </div>
                    <div id="line-chart-8"></div>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        (function ($) {

            var tfLineChart = (function () {

                var chartBar = function () {

                    var options = {
                        series: [{
                            name: 'Tổng',
                            data: @json($amountM),
                        },
                        {
                            name: 'Chờ xác nhận',
                            data: @json($orderedAmountM),
                        },
                        {
                            name: 'Đã xác nhận',
                            data: @json($confirmedAmountM),
                        },
                        {
                            name: 'Đã giao',
                            data: @json($deliveredAmountM),
                        }],
                        chart: {
                            type: 'bar',
                            height: 325,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '10px',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                        stroke: { show: false },
                        xaxis: {
                            categories: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                            labels: {
                                style: { colors: '#212529' }
                            }
                        },
                        yaxis: {
                            show: false,
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
                                }
                            }
                        }
                    };

                    chart = new ApexCharts(
                        document.querySelector("#line-chart-8"),
                        options
                    );
                    if ($("#line-chart-8").length > 0) {
                        chart.render();
                    }
                };

                /* Function ============ */
                return {
                    init: function () { },

                    load: function () {
                        chartBar();
                    },
                    resize: function () { },
                };
            })();

            jQuery(document).ready(function () { });

            jQuery(window).on("load", function () {
                tfLineChart.load();
            });

            jQuery(window).on("resize", function () { });
        })(jQuery);
    </script>
    <!-- <script>
                                                            (function ($) {

                                                                    var tfLineChart = (function () {

                                                                        var chartBar = function () {

                                                                            var options = {
                                                                                series: [{
                                                                                    name: 'Total',
                                                                                    data: {{ $amountM }}
                                                                                }, {
                                                                                    name: 'Pending',
                                                                                    data: {{ $orderedAmountM }}
                                                                                },
                                                                                {
                                                                                    name: 'Confirmed',
                                                                                    data: {{ $confirmedAmountM }}
                                                                                }, {
                                                                                    name: 'Delivered',
                                                                                    data: {{ $deliveredAmountM }}
                                                                                }],
                                                                                chart: {
                                                                                    type: 'bar',
                                                                                    height: 325,
                                                                                    toolbar: {
                                                                                        show: false,
                                                                                    },
                                                                                },
                                                                                plotOptions: {
                                                                                    bar: {
                                                                                        horizontal: false,
                                                                                        columnWidth: '10px',
                                                                                        endingShape: 'rounded'
                                                                                    },
                                                                                },
                                                                                dataLabels: {
                                                                                    enabled: false
                                                                                },
                                                                                legend: {
                                                                                    show: false,
                                                                                },
                                                                                colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                                                                                stroke: {
                                                                                    show: false,
                                                                                },
                                                                                xaxis: {
                                                                                    labels: {
                                                                                        style: {
                                                                                            colors: '#212529',
                                                                                        },
                                                                                    },
                                                                                    categories: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T1O', 'T11', 'T12'],
                                                                                },
                                                                                yaxis: {
                                                                                    show: false,
                                                                                },
                                                                                fill: {
                                                                                    opacity: 1
                                                                                },
                                                                                tooltip: {
                                                                                    y: {
                                                                                        formatter: function (val) {
                                                                                            return "$ " + val + ""
                                                                                        }
                                                                                    }
                                                                                }
                                                                            };

                                                                            chart = new ApexCharts(
                                                                                document.querySelector("#line-chart-8"),
                                                                                options
                                                                            );
                                                                            if ($("#line-chart-8").length > 0) {
                                                                                chart.render();
                                                                            }
                                                                        };

                                                                        /* Function ============ */
                                                                        return {
                                                                            init: function () { },

                                                                            load: function () {
                                                                                chartBar();
                                                                            },
                                                                            resize: function () { },
                                                                        };
                                                                    })();

                                                                    jQuery(document).ready(function () { });

                                                                    jQuery(window).on("load", function () {
                                                                        tfLineChart.load();
                                                                    });

                                                                    jQuery(window).on("resize", function () { });
                                                                })(jQuery);
                                                        </script> -->
@endpush