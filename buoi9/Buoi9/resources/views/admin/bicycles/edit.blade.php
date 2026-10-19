@extends('layouts.app')

@section('content')

<h3>Sửa xe đạp</h3>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)
            <div>{{ $e }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('admin.bicycles.update', $bicycle->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Biển số xe</label>
        <input type="text"
            name="bike_code"
            class="form-control"
            value="{{ old('bike_code', $bicycle->bike_code) }}" disabled>
    </div>

    {{-- trạm --}}
    <div class="mb-3">
        <label>Trạm</label>
        <select name="station_id" class="form-control">
            <option value="">Không có trạm</option>
            @foreach($stations as $s)
                <option value="{{ $s['id'] }}"
                    {{ old('station_id', $bicycle->station_id) == $s['id'] ? 'selected' : '' }}>
                    {{ $s['station_name'] }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- giá --}}
    <div class="mb-3">
        <label>Giá thuê xe</label>
        <input type="number"
            name="price"
            class="form-control"
            value="{{ old('price', $bicycle->rental_price_per_hour) }}">
    </div>

    {{-- trạng thái --}}
    <div class="mb-3">
        <label>Trạng thái</label>
        <select name="condition_status" class="form-control">
            <option value="active"
                {{ old('condition_status', $bicycle->condition_status) == 'active' ? 'selected' : '' }}>
                Bình thường
            </option>

            <option value="inactive"
                {{ old('condition_status', $bicycle->condition_status) == 'inactive' ? 'selected' : '' }}>
                Đang sửa
            </option>
        </select>
    </div>

    <button class="btn btn-primary">Cập nhật</button>
    <a href="{{ route('admin.bicycles.index') }}" class="btn btn-secondary">Quay lại</a>

</form>

@endsection

