@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng ' . $order->order_code)

@section('content')
<div class="content">
    <div class="container" style="margin-top:25px;margin-bottom:40px;">
        @include('frontend.partials.order-timeline', ['order' => $order])
    </div>
</div>
@endsection

