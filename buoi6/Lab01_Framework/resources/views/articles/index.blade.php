@extends('layouts.app')
@section('title', 'Danh sách bài viết')

@section('content')
    <h2>Danh sách bài viết</h2>

    <!-- Hiển thị Flash Message ngay trên bảng nếu có -->
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $a)
                <tr>
                    <td>{{ $a['id'] }}</td>
                    <td>{{ $a['title'] }}</td>
                    <td>
                        <a href="{{ route('articles.show', $a['id']) }}">Xem</a> |
                        <a href="{{ route('articles.edit', $a['id']) }}">Sửa</a> |
                        
                        <!-- Form xoá an toàn bằng Confirm & Method Spoofing -->
                        <form action="{{ route('articles.destroy', $a['id']) }}" 
                              method="post" 
                              style="display:inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xoá bài viết này không?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; cursor: pointer;">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Chưa có bài viết.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @push('scripts')
        <script>
            // demo stack scripts
            console.log('Articles index loaded');
        </script>
    @endpush
@endsection
