@extends('layout.app')
@section('title', 'Edit Role')
@section('page_title', 'Edit Role')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('master.role.update', $role->idrole) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Nama Role</label>
                        <input type="text" class="form-control" name="nama_role" value="{{ $role->nama_role }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('master.role') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection