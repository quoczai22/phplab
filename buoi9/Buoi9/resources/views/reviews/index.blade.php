@extends('layouts.app')

@section('content')

<h3 class="text-center mb-4">Đánh giá & Bình luận (Rating & Comment)</h3>

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
        @endforeach
    </div>
@endif

<div class="row g-4">
    <!-- ================= CỘT 1: ĐÁNH GIÁ TRẠM XE ================= -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">&#128205; Đánh giá Trạm xe</h5>
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('reviews.station.store') }}" method="POST" class="mb-4 border-bottom pb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chọn trạm xe</label>
                            <select name="station_id" class="form-select" required>
                                <option value="">-- Chọn trạm xe --</option>
                                @foreach($stations as $st)
                                    <option value="{{ $st->id }}" {{ old('station_id') == $st->id ? 'selected' : '' }}>
                                        {{ $st->station_name }} ({{ $st->ward }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số sao đánh giá</label>
                            <select name="rating" class="form-select" required>
                                <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5 sao - Tuyệt vời)</option>
                                <option value="4">&#11088;&#11088;&#11088;&#11088; (4 sao - Tốt)</option>
                                <option value="3">&#11088;&#11088;&#11088; (3 sao - Bình thường)</option>
                                <option value="2">&#11088;&#11088; (2 sao - Kém)</option>
                                <option value="1">&#11088; (1 sao - Rất tệ)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bình luận</label>
                            <textarea name="comment" rows="3" class="form-control" placeholder="Nhập nhận xét của bạn về trạm xe..." required>{{ old('comment') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Gửi đánh giá Trạm xe</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để đánh giá trạm xe.
                    </div>
                @endauth

                <h6 class="fw-bold mb-3">Bình luận mới nhất về Trạm xe</h6>
                @forelse($stationReviews as $sr)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary">{{ $sr->station->station_name ?? 'Trạm đã xóa' }}</span>
                                <strong class="ms-1">{{ $sr->user->name ?? 'Ẩn danh' }}</strong>
                            </div>
                            <span class="text-warning fw-bold">
                                @for($i = 1; $i <= 5; $i++)
                                    {!! $i <= ($sr->rating ?? $sr->ratting ?? 0) ? '&#9733;' : '&#9734;' !!}
                                @endfor
                            </span>
                        </div>
                        <p class="mb-1 mt-2">{{ $sr->comment }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $sr->created_at ? $sr->created_at->format('d/m/Y H:i') : '' }}</small>
                            @auth
                                @if(Auth::id() === $sr->user_id || Auth::user()->isAdmin())
                                    <form action="{{ route('reviews.station.destroy', $sr->id) }}" method="POST" onsubmit="return confirm('Xóa bình luận này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link text-danger btn-sm p-0 text-decoration-none">Xóa</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Chưa có đánh giá trạm xe nào.</p>
                @endforelse

                <div class="d-flex justify-content-center mt-3">
                    {{ $stationReviews->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- ================= CỘT 2: ĐÁNH GIÁ XE ĐẠP ================= -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">&#128690; Đánh giá Xe đạp</h5>
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('reviews.bicycle.store') }}" method="POST" class="mb-4 border-bottom pb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chọn biển số xe đạp</label>
                            <select name="bicycle_id" class="form-select" required>
                                <option value="">-- Chọn xe đạp --</option>
                                @foreach($bicycles as $bk)
                                    <option value="{{ $bk->id }}" {{ old('bicycle_id') == $bk->id ? 'selected' : '' }}>
                                        {{ $bk->bike_code }} ({{ number_format($bk->rental_price_per_hour) }}đ/giờ)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số sao đánh giá</label>
                            <select name="rating" class="form-select" required>
                                <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5 sao - Xe chạy rất êm)</option>
                                <option value="4">&#11088;&#11088;&#11088;&#11088; (4 sao - Tốt)</option>
                                <option value="3">&#11088;&#11088;&#11088; (3 sao - Bình thường)</option>
                                <option value="2">&#11088;&#11088; (2 sao - Hơi cũ)</option>
                                <option value="1">&#11088; (1 sao - Cần bảo trì)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bình luận</label>
                            <textarea name="comment" rows="3" class="form-control" placeholder="Nhập nhận xét của bạn về tình trạng xe đạp..." required>{{ old('comment') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Gửi đánh giá Xe đạp</button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để đánh giá xe đạp.
                    </div>
                @endauth

                <h6 class="fw-bold mb-3">Bình luận mới nhất về Xe đạp</h6>
                @forelse($bicycleReviews as $br)
                    <div class="border rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-success">Xe: {{ $br->bicycle->bike_code ?? 'Đã xóa' }}</span>
                                <strong class="ms-1">{{ $br->user->name ?? 'Ẩn danh' }}</strong>
                            </div>
                            <span class="text-warning fw-bold">
                                @for($i = 1; $i <= 5; $i++)
                                    {!! $i <= ($br->rating ?? $br->ratting ?? 0) ? '&#9733;' : '&#9734;' !!}
                                @endfor
                            </span>
                        </div>
                        <p class="mb-1 mt-2">{{ $br->comment }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $br->created_at ? $br->created_at->format('d/m/Y H:i') : '' }}</small>
                            @auth
                                @if(Auth::id() === $br->user_id || Auth::user()->isAdmin())
                                    <form action="{{ route('reviews.bicycle.destroy', $br->id) }}" method="POST" onsubmit="return confirm('Xóa bình luận này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link text-danger btn-sm p-0 text-decoration-none">Xóa</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Chưa có đánh giá xe đạp nào.</p>
                @endforelse

                <div class="d-flex justify-content-center mt-3">
                    {{ $bicycleReviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

