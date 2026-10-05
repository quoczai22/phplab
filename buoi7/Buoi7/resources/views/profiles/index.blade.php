@extends('layouts.app')
@section('content')
    <h2>Danh sách profile và User</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên User</th>
                <th>Address</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($profiles as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->user->name ?? $s->user_id }}</td>
                    <td>{{ $s->address }}</td>
                    <td>{{ $s->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection