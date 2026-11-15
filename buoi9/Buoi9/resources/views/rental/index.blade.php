@extends('layouts.app')

@section('content')

<h3 class="mb-4">Thuê xe</h3>

<div class="d-flex justify-content-center">
    <form method="POST" action="{{ route('rental.store') }}" style="width: 350px;">
        @csrf

        <div class="border p-3">
            <select id="ward" class="form-select border-0">
                <option value="">Chọn Phường</option>
                @foreach($wards as $w)
                    <option value="{{ $w['name'] }}">{{ $w['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="border p-3 border-top-0">
            <select name="station_id" id="station" class="form-select border-0">
                <option value="">Chọn Trạm</option>
            </select>
        </div>

        {{-- Dropdown chọn nhanh xe đang có tại trạm (AJAX API) --}}
        <div class="border p-3 border-top-0 bg-light">
            <select id="bikeSelect" class="form-select border-0 bg-light">
                <option value="">-- Chọn nhanh xe tại trạm --</option>
            </select>
        </div>

        <div class="border p-3 border-top-0">
            <input type="text" name="bike_code" id="bikeCodeInput" class="form-control border-0"
                placeholder="Nhập biển số...">
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-dark px-5 py-2">
                Thuê
            </button>
        </div>
    </form>
</div>

<script>
// 1. Khi thay đổi phường, xã thì danh sách trạm xe thay đổi theo
document.getElementById('ward').addEventListener('change', function () {
    let ward = this.value;

    fetch(`/stations-by-ward?ward=${encodeURIComponent(ward)}`)
        .then(res => res.json())
        .then(data => {
            let html = '<option value="">Chọn Trạm</option>';

            data.forEach(s => {
                html += `<option value="${s.id}">${s.station_name} (${s.bicycles_count} xe)</option>`;
            });

            document.getElementById('station').innerHTML = html;
            document.getElementById('bikeSelect').innerHTML = '<option value="">-- Chọn nhanh xe tại trạm --</option>';
            document.getElementById('bikeCodeInput').value = '';
        });
});

// 2. Khi chọn trạm xe -> gọi API lấy danh sách xe đạp có sẵn tại trạm đó
document.getElementById('station').addEventListener('change', function () {
    let stationId = this.value;
    let bikeSelect = document.getElementById('bikeSelect');

    if (!stationId) {
        bikeSelect.innerHTML = '<option value="">-- Chọn nhanh xe tại trạm --</option>';
        return;
    }

    fetch(`/bikes-by-station?station_id=${encodeURIComponent(stationId)}`)
        .then(res => res.json())
        .then(bikes => {
            let html = '<option value="">-- Chọn nhanh xe tại trạm --</option>';

            bikes.forEach(b => {
                let price = Number(b.rental_price_per_hour).toLocaleString('vi-VN');
                html += `<option value="${b.bike_code}">${b.bike_code} (${price}đ/giờ)</option>`;
            });

            bikeSelect.innerHTML = html;
        });
});

// 3. Khi chọn xe từ dropdown -> tự động điền biển số vào ô input bike_code
document.getElementById('bikeSelect').addEventListener('change', function () {
    if (this.value) {
        document.getElementById('bikeCodeInput').value = this.value;
    }
});
</script>

@endsection
