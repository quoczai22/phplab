@extends('layouts.app')

@section('content')

<h3>Thêm xe đạp</h3>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)
            <div>{{ $e }}</div>
        @endforeach
    </div>
@endif

<form action="{{ route('admin.bicycles.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Biển số xe</label>
        <input type="text" name="bike_code" class="form-control">
    </div>

    <div class="mb-3">
        <label>Trạm</label>
        <select name="station_id" class="form-control">
            <option value="">Không có trạm</option>
            @foreach($stations as $s)
                <option value="{{ $s['id'] }}">
                    {{ $s['station_name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Giá thuê xe</label>
        <input type="number" name="price" class="form-control">
    </div>

    <button class="btn btn-primary">Lưu</button>
</form>

@endsection

