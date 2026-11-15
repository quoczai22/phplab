@extends('layouts.admin')

@section('title', 'Quản Lý Slider Banner | Admin')

@section('content')
<div class="flex items-center flex-wrap justify-between gap20 mb-27">
    <h3>Banner Trình Chiếu (Slider) ({{ $sliders->count() }})</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Bảng Điều Khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Banner & Slider</div></li>
    </ul>
</div>

<div class="wg-box">
    <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow"><h5>Danh Sách Banner Trang Chủ</h5></div>
        <a class="tf-button style-1 w208" href="{{ route('admin.sliders.add') }}"><i class="icon-plus"></i>Thêm banner mới</a>
    </div>
    <div class="wg-table table-all-user">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Hình ảnh</th>
                    <th>Khẩu hiệu (Tagline)</th>
                    <th>Tiêu đề chính</th>
                    <th>Tiêu đề phụ</th>
                    <th>Liên kết</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sliders as $slide)
                <tr>
                    <td>{{ $slide->id }}</td>
                    <td class="pname">
                        <div class="image">
                            <img src="{{ asset($slide->image) }}" alt="" class="image" style="width:80px;height:45px;object-fit:cover;">
                        </div>
                    </td>
                    <td>{{ $slide->tagline }}</td>
                    <td><strong>{{ $slide->title }}</strong></td>
                    <td>{{ $slide->subtitle }}</td>
                    <td><a href="{{ $slide->link }}" target="_blank">{{ $slide->link }}</a></td>
                    <td>
                        <div class="list-icon-function">
                            <form action="{{ route('admin.sliders.delete', $slide->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="item text-danger delete" style="border:none;background:none;" onclick="return confirm('Xóa banner này?')">
                                    <i class="icon-trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
