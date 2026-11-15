@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 25px auto; padding: 0 15px;">

    {{-- HEADER --}}
    <div style="background: linear-gradient(135deg, #003366, #006699); color: #fff; padding: 24px 28px; border-radius: 14px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="margin: 0 0 6px 0; font-size: 24px;">🌐 Trung tâm API Thuê Xe Đạp (BikeGo & CityBikes)</h2>
            <p style="margin: 0; opacity: 0.9; font-size: 14px;">
                Tích hợp cả <b>Cách 1 (RESTful API & AJAX nội bộ)</b> và <b>Cách 2 (Public API quốc tế CityBikes)</b>
            </p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('rental.index') }}" style="background: #ffcc00; color: #003366; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px;">
                🚲 Trang Thuê Xe (AJAX)
            </a>
            <a href="{{ url('/') }}" style="background: rgba(255,255,255,0.18); color: #fff; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px;">
                🏠 Về Trang chủ
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #28a745; font-weight: 600;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #dc3545; font-weight: 600;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- PHẦN 1: RESTFUL API NỘI BỘ CỦA BIKEGO --}}
    <div style="background: #fff; border-radius: 12px; padding: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.08); margin-bottom: 28px;">
        <h3 style="margin-top: 0; color: #003366; border-bottom: 2px solid #f0f4f8; padding-bottom: 10px;">
            ⚡ Cách 1: Hệ thống RESTful API Nội bộ (BikeGo API v1)
        </h3>
        <p style="font-size: 14px; color: #555; margin-bottom: 16px;">
            Các endpoint trả về dữ liệu chuẩn JSON để sử dụng cho Mobile App, AJAX chọn xe theo trạm hoặc kiểm thử trên Postman/Trình duyệt:
        </p>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background: #f4f7fb; color: #003366; text-align: left;">
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Method</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Endpoint</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6;">Mô tả chức năng</th>
                        <th style="padding: 10px 12px; border-bottom: 2px solid #dee2e6; text-align: center;">Thử nghiệm</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 12px;"><span style="background: #28a745; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">GET</span></td>
                        <td style="padding: 10px 12px; font-family: monospace; color: #d63384;">/api/v1/stations</td>
                        <td style="padding: 10px 12px;">Lấy danh sách trạm xe, điểm đánh giá & số xe rảnh (hỗ trợ <code>?ward=</code>, <code>?keyword=</code>)</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <a href="{{ url('/api/v1/stations') }}" target="_blank" style="color: #006699; font-weight: bold; text-decoration: none;">Mở JSON ↗</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 12px;"><span style="background: #28a745; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">GET</span></td>
                        <td style="padding: 10px 12px; font-family: monospace; color: #d63384;">/api/v1/stations/{id}/bicycles</td>
                        <td style="padding: 10px 12px;">Lấy danh sách xe đạp đang sẵn sàng (<code>available</code>) tại một trạm</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <a href="{{ url('/api/v1/stations/1/bicycles') }}" target="_blank" style="color: #006699; font-weight: bold; text-decoration: none;">Mở JSON ↗</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 12px;"><span style="background: #28a745; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">GET</span></td>
                        <td style="padding: 10px 12px; font-family: monospace; color: #d63384;">/api/v1/bicycles</td>
                        <td style="padding: 10px 12px;">Tra cứu toàn bộ xe đạp (hỗ trợ lọc <code>?status=available&station_id=1</code>)</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <a href="{{ url('/api/v1/bicycles?status=available') }}" target="_blank" style="color: #006699; font-weight: bold; text-decoration: none;">Mở JSON ↗</a>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 12px;"><span style="background: #007bff; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">POST</span></td>
                        <td style="padding: 10px 12px; font-family: monospace; color: #d63384;">/api/v1/rent</td>
                        <td style="padding: 10px 12px;">Thuê xe qua API (Body JSON: <code>user_id</code>, <code>station_id</code>, <code>bike_code</code>)</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <button type="button" onclick="testInternalApi('/api/v1/bicycles?status=available')" style="background: #006699; color: #fff; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px;">Test Fetch</button>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px 12px;"><span style="background: #007bff; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">POST</span></td>
                        <td style="padding: 10px 12px; font-family: monospace; color: #d63384;">/api/v1/return</td>
                        <td style="padding: 10px 12px;">Trả xe qua API (Body JSON: <code>user_id</code>, <code>station_id</code>, <code>bike_code</code>)</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <button type="button" onclick="testInternalApi('/api/v1/stations')" style="background: #006699; color: #fff; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px;">Test Fetch</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 12px;"><span style="background: #17a2b8; color: #fff; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">GET</span></td>
                        <td style="padding: 10px 12px; font-family: monospace; color: #d63384;">/api/v1/citybikes?network={{ $network }}</td>
                        <td style="padding: 10px 12px;">API Proxy lấy dữ liệu thời gian thực từ mạng lưới xe đạp công cộng thế giới CityBikes</td>
                        <td style="padding: 10px 12px; text-align: center;">
                            <a href="{{ url('/api/v1/citybikes?network=' . $network) }}" target="_blank" style="color: #006699; font-weight: bold; text-decoration: none;">Mở JSON ↗</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Hộp hiển thị kết quả Test Fetch trực tiếp --}}
        <div id="apiTestBox" style="display: none; margin-top: 15px; background: #1e293b; color: #e2e8f0; padding: 14px; border-radius: 8px; font-family: monospace; font-size: 12px; max-height: 240px; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; color: #38bdf8; font-weight: bold;">
                <span id="apiTestTitle">Kết quả JSON:</span>
                <span onclick="document.getElementById('apiTestBox').style.display='none'" style="cursor: pointer; color: #f87171;">✖ Đóng</span>
            </div>
            <pre id="apiTestOutput" style="margin: 0; white-space: pre-wrap;"></pre>
        </div>
    </div>

    {{-- PHẦN 2: PUBLIC API QUỐC TẾ CITYBIKES (API.CITYBIK.ES) --}}
    <div style="background: #fff; border-radius: 12px; padding: 22px; box-shadow: 0 3px 12px rgba(0,0,0,0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid #f0f4f8; padding-bottom: 14px; margin-bottom: 18px;">
            <div>
                <h3 style="margin: 0; color: #003366;">
                    🌍 Cách 2: Dữ liệu Trực tiếp từ Public API Quốc tế (CityBikes v2)
                </h3>
                <p style="margin: 5px 0 0 0; font-size: 13px; color: #666;">
                    Nguồn: <code>http://api.citybik.es/v2/networks/{{ $network }}</code> —
                    Hệ thống: <b>{{ $networkInfo['name'] }}</b> ({{ $networkInfo['city'] }}, {{ $networkInfo['country'] }})
                </p>
            </div>

            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                {{-- Form chọn mạng lưới thành phố --}}
                <form method="GET" action="{{ route('api.explorer') }}" style="display: flex; gap: 8px; align-items: center;">
                    <select name="network" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 13px;">
                        <option value="bixi-toronto" {{ $network == 'bixi-toronto' ? 'selected' : '' }}>🇨🇦 BIXI Toronto (Canada)</option>
                        <option value="velib" {{ $network == 'velib' ? 'selected' : '' }}>🇫🇷 Vélib' Metropole (Paris, Pháp)</option>
                        <option value="santander-cycles" {{ $network == 'santander-cycles' ? 'selected' : '' }}>🇬🇧 Santander Cycles (London, Anh)</option>
                        <option value="nextbike-berlin" {{ $network == 'nextbike-berlin' ? 'selected' : '' }}>🇩🇪 Nextbike Berlin (Đức)</option>
                        <option value="citi-bike-nyc" {{ $network == 'citi-bike-nyc' ? 'selected' : '' }}>🇺🇸 Citi Bike NYC (New York, Mỹ)</option>
                    </select>
                </form>

                {{-- Form đồng bộ dữ liệu từ API về Database BikeGo --}}
                <form method="POST" action="{{ route('api.citybikes.import') }}">
                    @csrf
                    <input type="hidden" name="network" value="{{ $network }}">
                    <button type="submit" style="background: #28a745; color: #fff; border: none; padding: 9px 14px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 13px;">
                        📥 Đồng bộ 5 trạm & xe từ API này về CSDL BikeGo
                    </button>
                </form>
            </div>
        </div>

        @if($error)
            <div style="background: #fff3cd; color: #856404; padding: 14px; border-radius: 8px;">
                ⚠️ {{ $error }}
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 16px;">
                @foreach($stations as $st)
                    <div style="border: 1px solid #e3e8ef; border-radius: 10px; padding: 15px; background: #fafcff; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h4 style="margin: 0 0 8px 0; color: #003366; font-size: 15px;">
                                📍 {{ $st['name'] ?? 'Trạm xe' }}
                            </h4>
                            <p style="margin: 4px 0; font-size: 13px; color: #555;">
                                🗺️ Tọa độ: <code>{{ $st['latitude'] ?? '?' }}, {{ $st['longitude'] ?? '?' }}</code>
                            </p>
                            @if(!empty($st['extra']['address']))
                                <p style="margin: 4px 0; font-size: 13px; color: #555;">
                                    🏠 Địa chỉ: {{ $st['extra']['address'] }}
                                </p>
                            @endif
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; padding-top: 10px; border-top: 1px dashed #dce4ec; font-size: 13px;">
                            <span style="background: #d4edda; color: #155724; padding: 4px 10px; border-radius: 20px; font-weight: bold;">
                                🚲 Xe trống: {{ $st['free_bikes'] ?? 0 }}
                            </span>
                            <span style="background: #e2e3e5; color: #383d41; padding: 4px 10px; border-radius: 20px; font-weight: 600;">
                                🅿️ Chốt trống: {{ $st['empty_slots'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script>
function testInternalApi(endpoint) {
    const box = document.getElementById('apiTestBox');
    const title = document.getElementById('apiTestTitle');
    const output = document.getElementById('apiTestOutput');

    box.style.display = 'block';
    title.innerText = 'Đang gọi GET ' + endpoint + ' ...';
    output.innerText = 'Loading...';

    fetch(endpoint)
        .then(res => res.json())
        .then(data => {
            title.innerText = 'Kết quả GET ' + endpoint + ' (Hiển thị 5 mục đầu):';
            if (data.data && Array.isArray(data.data)) {
                data.data_preview = data.data.slice(0, 5);
                delete data.data;
            }
            output.innerText = JSON.stringify(data, null, 2);
        })
        .catch(err => {
            output.innerText = 'Lỗi: ' + err.message;
        });
}
</script>
@endsection

