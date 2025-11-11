@extends('layout.app')
@section('title', 'Data User')
@section('page_title', 'Master Data User & Role')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID User</th>
                        <th scope="col">Username</th>
                        <th scope="col">Nama Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_user as $user)
                    <tr>
                        <td>{{ $user->iduser }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->nama_role }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection