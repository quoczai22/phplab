@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Quản lý xe đạp</h3>

    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">&larr; Trang quản trị</a>
        <a href="{{ route('admin.bicycles.create') }}" class="btn btn-primary">Thêm xe đạp mới</a>
        <a href="{{ route('admin.bicycles.trash') }}" class="btn btn-danger">Thùng rác</a>
    </div>
</div>

<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Biển số</th>
            <th>Vị trí hiện tại</th>
            <th>Tình trạng</th>
            <th>Đánh giá</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bicycles as $bicycle)
        <tr>
            <td>{{ $bicycle->bike_code }}</td>
            <td>
                @if($bicycle->location_status == "at_station")
                    <span class="badge bg-success">
                        {{ $bicycle->station->station_name ?? 'Không có vị trí' }}
                    </span>
                @else
                    <span class="badge bg-warning text-dark">
                        Đang thuê
                    </span>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $bicycle->condition_status == 'active' ? 'success' : 'secondary' }}">
                    {{ $bicycle->condition_status == 'active' ? "Bình thường" : "Đang sửa" }}
                </span>
            </td>
            <td>
                &#9733; {{ number_format($bicycle->avg_rating, 1) }}
                <br>
                <small>({{ $bicycle->total_reviews ?? 0 }} đánh giá)</small>
            </td>
            <td>
                <a href="{{ route('admin.bicycles.edit', $bicycle->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                <form action="{{ route('admin.bicycles.destroy', $bicycle->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Phân trang -->
<div class="d-flex justify-content-center">
    {{ $bicycles->links() }}
</div>

@endsection

