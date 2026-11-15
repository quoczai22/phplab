@extends('layouts.app')

@section('content')

<h3 class="mb-3">Trả xe</h3>

@if($rental)

    @php
        $minutes = round(abs(now()->diffInMinutes($rental->rented_at)), 2);
        $total = $minutes * $rental->price_per_hour / 60;
    @endphp

    {{-- THÔNG TIN --}}
    <div class="mb-4">
        <p><strong>Thông tin xe đang thuê:</strong></p>
        <ul>
            <li>Thời gian bắt đầu thuê: {{ $rental->rented_at }}</li>
            <li>Biển số xe: {{ $rental->bicycle->bike_code }}</li>
            <li>Tên trạm lấy xe: {{ $rental->rentStation->station_name }}</li>
            <li>Số phút thuê: {{ $minutes }}</li>
            <li>Số tiền thuê: {{ number_format($total) }} VND</li>
        </ul>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('rental.return') }}">
        @csrf

        <p><strong>Chọn trạm trả xe:</strong></p>

        <div class="d-flex justify-content-center">
            <div style="width: 350px;">

                {{-- PHƯỜNG --}}
                <div class="border p-3">
                    <select id="ward" class="form-select border-0">
                        <option value="">Chọn Phường</option>
                        @foreach($wards as $w)
                            <option value="{{ $w['name'] }}">{{ $w['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- TRẠM --}}
                <div class="border p-3 border-top-0">
                    <select name="station_id" id="station" class="form-select border-0">
                        <option value="">Chọn Trạm</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- BUTTON --}}
        <div class="text-center mt-4">
            <button class="btn btn-dark px-5 py-2">
                Trả xe và thanh toán
            </button>
        </div>
    </form>

@else
    <div class="alert alert-warning">
        Bạn chưa thuê xe
    </div>
@endif

{{-- SCRIPT --}}
<script>
document.getElementById('ward')?.addEventListener('change', function () {
    let ward = this.value;

    fetch(`/stations-by-ward?ward=${encodeURIComponent(ward)}`)
        .then(res => res.json())
        .then(data => {
            let html = '<option value="">Chọn Trạm</option>';

            data.forEach(s => {
                html += `<option value="${s.id}">${s.station_name}</option>`;
            });

            document.getElementById('station').innerHTML = html;
        });
});
</script>

@endsection

