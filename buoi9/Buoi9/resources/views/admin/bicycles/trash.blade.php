@extends('layouts.app')

@section('content')

<h3>Thùng rác</h3>

<div class="mb-3">
    <a href="{{ route('admin.bicycles.index') }}" class="btn btn-secondary">← Quay lại</a>

    <button type="button" id="btnRestoreSelected" class="btn btn-success">
        Khôi phục đã chọn
    </button>

    <button type="button" id="btnDeleteSelected" class="btn btn-danger">
        Xóa đã chọn
    </button>

    <form action="{{ route('admin.bicycles.restoreAll') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-success"
            onclick="return confirm('Khôi phục tất cả?')">
            Khôi phục tất cả
        </button>
    </form>

    <form action="{{ route('admin.bicycles.forceDeleteAll') }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger"
            onclick="return confirm('Xóa vĩnh viễn tất cả hợp lệ?')">
            Xóa tất cả
        </button>
    </form>
</div>

<table class="table table-bordered text-center align-middle">
    <thead class="table-dark">
        <tr>
            <th>
                <input type="checkbox" id="checkAll">
            </th>
            <th>Biển số</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>

    <tbody>
        @forelse($bicycles as $bicycle)
        <tr>
            <td>
                <input type="checkbox" class="checkItem" value="{{ $bicycle->id }}">
            </td>

            <td>{{ $bicycle->bike_code }}</td>

            <td>
                <span class="badge bg-{{ $bicycle->condition_status == 'active' ? 'success' : 'secondary' }}">
                    {{ $bicycle->condition_status == 'active' ? "Bình thường" : "Đang sửa" }}
                </span>
            </td>

            <td>
                {{-- restore --}}
                <a href="{{ route('admin.bicycles.restore', $bicycle->id) }}"
                    class="btn btn-success btn-sm">
                    Khôi phục
                </a>

                {{-- force delete --}}
                @if($bicycle->rentals_count == 0)
                    <form action="{{ route('admin.bicycles.forceDelete', $bicycle->id) }}"
                        method="POST"
                        class="d-inline">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Xóa vĩnh viễn?')">
                            Xóa cứng
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm" disabled>
                        Không thể xóa
                    </button>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Thùng rác trống</td>
        </tr>
        @endforelse
    </tbody>
</table>

<form id="bulkActionForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="ids" id="idsInput">
</form>

<!-- Phân trang -->
<div class="d-flex justify-content-center">
    {{ $bicycles->links() }}
</div>

<script>
//Nhấn nút checkAll
document.getElementById('checkAll').addEventListener('click', function() {
    let items = document.querySelectorAll('.checkItem');
    items.forEach(i => i.checked = this.checked);
});

//Lấy id các button đã chọn
function getSelectedIds() {
    let ids = [];
    document.querySelectorAll('.checkItem:checked')
        .forEach(i => ids.push(i.value));
    return ids;
}

// restore các xe đạp đã chọn
document.getElementById('btnRestoreSelected').onclick = function() {
    let ids = getSelectedIds();
    if (ids.length === 0) return alert('Chưa chọn xe');

    let form = document.getElementById('bulkActionForm');
    document.getElementById('idsInput').value = ids;

    form.action = "{{ route('admin.bicycles.restoreSelected') }}";
    form.submit();
};

// delete các xe đạp đã chọn
document.getElementById('btnDeleteSelected').onclick = function() {
    if (!confirm('Xóa các xe đã chọn?')) return;

    let ids = getSelectedIds();
    if (ids.length === 0) return alert('Chưa chọn xe');

    let form = document.getElementById('bulkActionForm');
    document.getElementById('idsInput').value = ids;

    form.action = "{{ route('admin.bicycles.forceDeleteSelected') }}";

    let method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);

    form.submit();
};
</script>

@endsection

