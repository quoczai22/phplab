@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">&#11088; Đánh giá chuyến thuê xe</h5>
                <a href="{{ route('rental.history') }}" class="btn btn-sm btn-outline-light">&larr; Quay lại lịch sử</a>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="alert alert-light border mb-4">
                    <div><strong>Biển số xe:</strong> {{ $rental->bicycle->bike_code ?? '-' }}</div>
                    <div><strong>Trạm lấy xe:</strong> {{ $rental->rentStation->station_name ?? '-' }}</div>
                    <div><strong>Trạm trả xe:</strong> {{ $rental->returnStation->station_name ?? 'Chưa trả' }}</div>
                    <div><strong>Thời gian thuê:</strong> {{ $rental->rented_at }}</div>
                </div>

                <form action="{{ route('reviews.rental.store', $rental->id) }}" method="POST">
                    @csrf

                    <!-- 1. Đánh giá Xe đạp -->
                    <h6 class="fw-bold text-success mb-3">1. Đánh giá xe đạp ({{ $rental->bicycle->bike_code ?? '' }})</h6>
                    <div class="mb-3">
                        <label class="form-label">Số sao xe đạp</label>
                        <select name="bike_rating" class="form-select" required>
                            <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5 sao - Xe chạy rất êm)</option>
                            <option value="4">&#11088;&#11088;&#11088;&#11088; (4 sao - Tốt)</option>
                            <option value="3">&#11088;&#11088;&#11088; (3 sao - Bình thường)</option>
                            <option value="2">&#11088;&#11088; (2 sao - Hơi cũ)</option>
                            <option value="1">&#11088; (1 sao - Cần bảo trì)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Nhận xét về xe đạp</label>
                        <textarea name="bike_comment" rows="2" class="form-control" placeholder="Nhận xét về chất lượng xe đạp..." required>{{ old('bike_comment') }}</textarea>
                    </div>

                    <hr>

                    <!-- 2. Đánh giá Trạm xe -->
                    <h6 class="fw-bold text-primary mb-3">2. Đánh giá trạm xe (Tùy chọn)</h6>
                    <div class="mb-3">
                        <label class="form-label">Chọn trạm muốn đánh giá</label>
                        <select name="station_id" class="form-select">
                            @if($rental->rentStation)
                                <option value="{{ $rental->rentStation->id }}">
                                    Trạm lấy: {{ $rental->rentStation->station_name }}
                                </option>
                            @endif
                            @if($rental->returnStation && $rental->return_station_id != $rental->rent_station_id)
                                <option value="{{ $rental->returnStation->id }}">
                                    Trạm trả: {{ $rental->returnStation->station_name }}
                                </option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số sao trạm xe</label>
                        <select name="station_rating" class="form-select">
                            <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5 sao - Tuyệt vời)</option>
                            <option value="4">&#11088;&#11088;&#11088;&#11088; (4 sao - Tốt)</option>
                            <option value="3">&#11088;&#11088;&#11088; (3 sao - Bình thường)</option>
                            <option value="2">&#11088;&#11088; (2 sao - Kém)</option>
                            <option value="1">&#11088; (1 sao - Rất tệ)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Nhận xét về trạm xe (để trống nếu không đánh giá trạm)</label>
                        <textarea name="station_comment" rows="2" class="form-control" placeholder="Nhận xét về vị trí, chỗ đậu của trạm xe...">{{ old('station_comment') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-2">Gửi đánh giá & Bình luận</button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

