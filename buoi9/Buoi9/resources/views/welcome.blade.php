@extends('layouts.app')

@section('content')

<!-- XẾP HẠNG TOP BIKER -->
<h4 class="mt-4">Top Bikers</h4>
<div class="d-flex justify-content-center gap-4 my-3">
    @foreach($topBikers->take(3) as $i => $u)
        <div class="p-3 border text-center top{{ $i+1 }}">
            <strong>Top {{ $i+1 }}</strong><br>

            {{ $u->name }} <br>
            {{ substr($u->phone, -3) }} <br>

            {{ $u->total_minutes }} phút <br>
            {{ $u->total_rentals }} lần
        </div>
    @endforeach
</div>

<table class="table table-bordered text-center">
    @foreach($topBikers->slice(3) as $i => $u)
        <tr class="hover-row">
            <td>Top {{ $i+4 }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ substr($u->phone, -3) }}</td>
            <td>{{ $u->total_minutes }} phút</td>
            <td>{{ $u->total_rentals }} lần</td>
        </tr>
    @endforeach
</table>


<!-- HẠ TẦNG TRẠM -->
<h4 class="mt-4">Let's BikeGo</h4>
<div class="row text-center border p-3">
    <div class="col">
        <p>Số xe hiện có</p>
        <h4>{{ $totalBikes }} xe</h4>
    </div>

    <div class="col">
        <p>Số trạm hiện có</p>
        <h4>{{ $totalStations }} trạm</h4>
    </div>
</div>

<h4 class="mt-4">Top Stations</h4>
<div class="carousel">
    <div class="carousel-track">
        @foreach($topStations as $s)
            <div class="station-card">
                <a href="{{ route('stations.index') }}">
                    <strong>{{ $s->station_name }}</strong>
                </a>

                <div>
                    ⭐ {{ round($s->station_reviews_avg_rating ?? $s->reviews_avg_rating ?? 0, 1) }}
                </div>

                <hr>

                @if ($s->stationReviews)
                    @foreach($s->stationReviews as $r)
                        <div>
                            ⭐ {{ $r->rating }} - {{ $r->comment }}
                        </div>
                    @endforeach
                @else
                    <p> Không có comment </p>
                @endif
            </div>
        @endforeach

    </div>
</div>

@endsection
