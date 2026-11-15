@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển & Doanh Thu | Fashion Store Admin')

@section('content')
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
                            <div class="body-text mb-2">Tổng số đơn hàng</div>
                            <h4>{{ $totalOrders }}</h4>
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
                            <div class="body-text mb-2">Tổng doanh thu bán hàng</div>
                            <h4>{{ vnd($totalAmount) }}</h4>
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
                            <div class="body-text mb-2">Đơn đang chờ đóng gói & Giao</div>
                            <h4>{{ $pendingOrders }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wg-chart-default">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap14">
                        <div class="image ic-bg">
                            <i class="icon-dollar-sign"></i>
                        </div>
                        <div>
                            <div class="body-text mb-2">Doanh thu đơn đang xử lý</div>
                            <h4>{{ vnd($pendingAmount) }}</h4>
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
                            <i class="icon-shopping-bag"></i>
                        </div>
                        <div>
                            <div class="body-text mb-2">Đơn đã giao thành công</div>
                            <h4>{{ $deliveredOrders }}</h4>
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
                            <div class="body-text mb-2">Doanh thu thực thu (Đã giao)</div>
                            <h4>{{ vnd($deliveredAmount) }}</h4>
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
                            <div class="body-text mb-2">Đơn hàng đã hủy</div>
                            <h4>{{ $canceledOrders }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wg-chart-default">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap14">
                        <div class="image ic-bg">
                            <i class="icon-dollar-sign"></i>
                        </div>
                        <div>
                            <div class="body-text mb-2">Doanh thu đơn bị hủy</div>
                            <h4>{{ vnd($canceledAmount) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wg-box">
        <div class="flex items-center justify-between">
            <h5>Biểu đồ tăng trưởng doanh thu theo tháng</h5>
            <a href="{{ route('admin.reports') }}" class="tf-button style-1">Xem Báo Cáo Chi Tiết</a>
        </div>
        <div class="flex flex-wrap gap40">
            <div>
                <div class="mb-2">
                    <div class="block-legend">
                        <div class="dot t1"></div>
                        <div class="text-tiny">Tổng doanh thu</div>
                    </div>
                </div>
                <div class="flex items-center gap10">
                    <h4>{{ vnd($totalAmount) }}</h4>
                    <div class="box-icon-trending up">
                        <i class="icon-trending-up"></i>
                        <div class="body-title number">+18.5%</div>
                    </div>
                </div>
            </div>
            <div>
                <div class="mb-2">
                    <div class="block-legend">
                        <div class="dot t2"></div>
                        <div class="text-tiny">Thực thu đã giao</div>
                    </div>
                </div>
                <div class="flex items-center gap10">
                    <h4>{{ vnd($deliveredAmount) }}</h4>
                    <div class="box-icon-trending up">
                        <i class="icon-trending-up"></i>
                        <div class="body-title number">+12.4%</div>
                    </div>
                </div>
            </div>
        </div>
        <div id="line-chart-8"></div>
    </div>
</div>

<div class="tf-section mb-30">
    <div class="wg-box">
        <div class="flex items-center justify-between">
            <h5>Đơn hàng mới nhận</h5>
            <div class="dropdown default">
                <a class="btn btn-secondary dropdown-toggle" href="{{ route('admin.orders') }}">
                    <span class="view-all">Xem toàn bộ</span>
                </a>
            </div>
        </div>
        <div class="wg-table table-all-user">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 120px">Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th class="text-center">Số điện thoại</th>
                            <th class="text-center">Tạm tính</th>
                            <th class="text-center">VAT</th>
                            <th class="text-center">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Ngày đặt</th>
                            <th class="text-center">Số SP</th>
                            <th class="text-center">Ngày giao</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td class="text-center"><strong>{{ $order->order_code }}</strong></td>
                            <td class="text-center">{{ $order->name }}</td>
                            <td class="text-center">{{ $order->phone }}</td>
                            <td class="text-center">{{ vnd($order->subtotal) }}</td>
                            <td class="text-center">{{ vnd($order->tax) }}</td>
                            <td class="text-center"><strong>{{ vnd($order->total) }}</strong></td>
                            <td class="text-center">
                                @if($order->status == 'ordered') <span class="badge bg-secondary">Mới đặt</span>
                                @elseif($order->status == 'processing') <span class="badge bg-info">Đang đóng gói</span>
                                @elseif($order->status == 'shipping') <span class="badge bg-primary">Đang giao</span>
                                @elseif($order->status == 'delivered') <span class="badge bg-success">Đã giao</span>
                                @else <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">{{ $order->items->sum('quantity') }}</td>
                            <td class="text-center">{{ $order->delivered_date ? date('d/m/Y', strtotime($order->delivered_date)) : '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.detail', $order->id) }}">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function (đ) {
        var tfLineChart = (function () {
            var chartBar = function () {
                var options = {
                    series: [{
                        name: 'Tổng doanh thu',
                        data: [120.00, 250.00, 180.00, 310.00, 420.00, 273.22, 508.12, 390.00, 460.00, {{ round($totalAmount, 2) }}, 0.00, 0.00]
                    }, {
                        name: 'Chờ xử lý',
                        data: [20.00, 40.00, 30.00, 50.00, 60.00, 120.00, 80.00, 40.00, 90.00, {{ round($pendingAmount, 2) }}, 0.00, 0.00]
                    }, {
                        name: 'Đã giao',
                        data: [100.00, 210.00, 150.00, 260.00, 360.00, 153.22, 428.12, 350.00, 370.00, {{ round($deliveredAmount, 2) }}, 0.00, 0.00]
                    }, {
                        name: 'Đã hủy',
                        data: [0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, {{ round($canceledAmount, 2) }}, 0.00, 0.00]
                    }],
                    chart: { type: 'bar', height: 325, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: false, columnWidth: '10px', endingShape: 'rounded' } },
                    dataLabels: { enabled: false },
                    legend: { show: false },
                    colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                    stroke: { show: false },
                    xaxis: {
                        labels: { style: { colors: '#212529' } },
                        categories: ['Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6', 'Thg 7', 'Thg 8', 'Thg 9', 'Thg 10', 'Thg 11', 'Thg 12'],
                    },
                    yaxis: { show: false },
                    fill: { opacity: 1 },
                    tooltip: { y: { formatter: function (val) { return "$ " + val; } } }
                };
                if ($("#line-chart-8").length > 0) {
                    new ApexCharts(document.querySelector("#line-chart-8"), options).render();
                }
            };
            return { load: function () { chartBar(); } };
        })();
        jQuery(window).on("load", function () { tfLineChart.load(); });
    })(jQuery);
</script>
@endpush
