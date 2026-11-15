@extends('layouts.app')

@section('content')

<h3 class="mb-4 text-center">Lịch sử thuê xe</h3>

@if($rentals->isEmpty())
    <div class="text-center text-muted">
        Bạn chưa có lịch sử thuê xe
    </div>
@else

<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Biển số</th>
            <th>Trạm lấy</th>
            <th>Trạm trả</th>
            <th>Bắt đầu</th>
            <th>Kết thúc</th>
            <th>Số phút</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th>Đánh giá</th>
        </tr>
    </thead>
    <tbody>

        @foreach($rentals as $r)
            @php
                $end = $r->returned_at ?? now();
                $minutes = \Carbon\Carbon::parse($r->rented_at)->diffInMinutes($end);
                $amount = $minutes * ($r->price_per_hour / 60);
            @endphp

            <tr>
                <td>{{ $r->bicycle->bike_code ?? '-' }}</td>

                <td>{{ $r->rentStation->station_name ?? '-' }}</td>

                <td>
                    {{ $r->returnStation->station_name ?? 'Chưa trả' }}
                </td>

                <td>{{ $r->rented_at }}</td>

                <td>
                    {{ $r->returned_at ?? 'Đang thuê' }}
                </td>

                <td>{{ $minutes }}</td>

                <td>{{ number_format($amount) }} đ</td>

                <td>
                    @if($r->returned_at)
                        <span class="text-success">Đã trả</span>
                    @else
                        <span class="text-danger">Đang thuê</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('reviews.rental', $r->id) }}" class="btn btn-warning btn-sm">
                        &#9733; Đánh giá
                    </a>
                </td>
            </tr>
        @endforeach

    </tbody>
</table>

@endif

@endsection

