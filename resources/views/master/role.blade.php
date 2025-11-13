@extends('layout.app')
@section('title', 'Data Role')
@section('page_title', 'Master Data Role')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-end">
        <a href="{{ route('master.role.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Role
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID Role</th>
                        <th scope="col">Nama Role</th>
                        <th scope="col" style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data_role as $role)
                    <tr>
                        <td>{{ $role->idrole }}</td>
                        <td>{{ $role->nama_role }}</td>
                        <td>
                            <form action="{{ route('master.role.destroy', $role->idrole) }}" method="POST" onsubmit="return confirm('PERHATIAN: Menghapus role bisa merusak data user. Anda yakin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus Permanen">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection