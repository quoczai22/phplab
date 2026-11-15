@extends('layouts.app')

@section('content')

<h3>Trạm xe</h3>

<div class="text-center my-4">
    <select class="form-select w-50 mx-auto" id="wardSelect">
        <option value="">Tất cả phường</option>
        @foreach($wards as $ward)
            <option value="{{ $ward['name'] }}">{{ $ward['name'] }}</option>
        @endforeach
    </select>
</div>

<div id="stationList" class="mt-4">
    <div class="accordion" id="stationAccordion">
        @foreach($stations as $station)
            @php
                $available = $station->bicycles_count;
                $empty = $station->total_docks - $station->bicycles_count;
                $isActive = $station->status == 'active';
                $statusColor = $isActive ? 'text-success fw-bold' : 'text-danger fw-bold';
                $bikeColor = $available >= ($station->total_docks / 2)
                    ? 'text-success fw-bold' : 'text-danger fw-bold';
                $emptyColor = $empty >= ($station->total_docks / 2)
                    ? 'text-success fw-bold' : 'text-danger fw-bold';
            @endphp
            <div class="accordion-item station-item"
                data-ward="{{ $station->ward }}">

                <h2 class="accordion-header">
                    <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $station->id }}">
                        <span class="{{ $isActive ? 'text-success' : 'text-warning' }}">
                            {{ $station->station_name }}
                        </span>
                        &nbsp;
                        (
                            <span class="{{ $bikeColor }}">
                                Số xe : {{ $available }}
                            </span>
                            &nbsp; - &nbsp;
                            <span class="{{ $emptyColor }}">
                                Số chỗ trống : {{ $empty }}
                            </span>
                        )
                    </button>
                </h2>

                <div id="collapse{{ $station->id }}"
                    class="accordion-collapse collapse"
                    data-bs-parent="#stationAccordion">

                    <div class="accordion-body text-start">
                        <p>
                            <strong>Địa chỉ:</strong> {{ $station->address }}
                        </p>

                        <p>
                            <strong>Tổng số chỗ:</strong>
                            <span class="fw-bold">{{ $station->total_docks }}</span>
                        </p>

                        <p>
                            <strong>Số chỗ trống:</strong>
                            <span class="{{ $empty >= ($station->total_docks / 2) ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ $empty }}
                            </span>
                        </p>

                        <p>
                            <strong>Số xe hiện có:</strong>
                            <span class="{{ $available >= ($station->total_docks / 2) ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ $available }}
                            </span>
                        </p>

                        <p>
                            <strong>Trạng thái:</strong>
                            <span class="{{ $isActive ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                {{ $isActive ? 'Đang hoạt động' : 'Đang bảo trì' }}
                            </span>
                        </p>

                        <p>
                            <strong>Đánh giá trung bình:</strong>
                            <span class="text-warning fw-bold">
                                &#9733; {{ number_format($station->avg_rating ?? 0, 1) }} / 5.0
                            </span>
                            <small class="text-muted">({{ $station->total_reviews ?? 0 }} đánh giá)</small>
                        </p>

                        <hr>

                        <!-- COMMENT & RATING -->
                        <h6 class="fw-bold mb-3">&#128172; Bình luận & Đánh giá trạm xe</h6>

                        @auth
                            <form action="{{ route('reviews.station.store') }}" method="POST" class="mb-4 p-3 bg-light rounded border">
                                @csrf
                                <input type="hidden" name="station_id" value="{{ $station->id }}">

                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-auto">
                                        <label class="fw-semibold">Chọn số sao:</label>
                                    </div>
                                    <div class="col-auto">
                                        <select name="rating" class="form-select form-select-sm" required>
                                            <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5 sao - Tuyệt vời)</option>
                                            <option value="4">&#11088;&#11088;&#11088;&#11088; (4 sao - Tốt)</option>
                                            <option value="3">&#11088;&#11088;&#11088; (3 sao - Bình thường)</option>
                                            <option value="2">&#11088;&#11088; (2 sao - Kém)</option>
                                            <option value="1">&#11088; (1 sao - Rất tệ)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control" placeholder="Viết nhận xét của bạn về trạm {{ $station->station_name }}..." required>
                                    <button type="submit" class="btn btn-dark">Gửi đánh giá</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-secondary py-2">
                                Vui lòng <a href="{{ route('login') }}" class="fw-bold">đăng nhập</a> để gửi đánh giá và bình luận.
                            </div>
                        @endauth

                        {{-- Danh sách bình luận --}}
                        <div class="review-list">
                            @forelse($station->reviews as $rev)
                                <div class="border-bottom py-2 d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $rev->user->name ?? 'Người dùng ẩn danh' }}</strong>
                                        <span class="text-warning ms-2">
                                            @for($star = 1; $star <= 5; $star++)
                                                {!! $star <= ($rev->rating ?? $rev->ratting ?? 0) ? '&#9733;' : '&#9734;' !!}
                                            @endfor
                                        </span>
                                        <small class="text-muted ms-2">
                                            {{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}
                                        </small>
                                        <p class="mb-0 mt-1">{{ $rev->comment }}</p>
                                    </div>

                                    @auth
                                        @if(Auth::id() === $rev->user_id || Auth::user()->isAdmin())
                                            <form action="{{ route('reviews.station.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Xóa bình luận này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">Xóa</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            @empty
                                <p class="text-muted fst-italic mb-0">Chưa có bình luận nào cho trạm này.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>

<p id="emptyMsg" class="text-center text-muted d-none">
    Không có trạm
</p>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('wardSelect');
    const stations = document.querySelectorAll('.station-item');
    const emptyMsg = document.getElementById('emptyMsg');

    select.addEventListener('change', function () {
        const ward = this.value;
        let visible = 0;

        stations.forEach(item => {
            if (!ward || item.dataset.ward === ward) {
                item.style.display = '';
                visible++;
            } else {
                item.style.display = 'none';
            }
        });

        emptyMsg.classList.toggle('d-none', visible !== 0);
    });
});
</script>

@endsection
