@extends('layout.app')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')


@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3 shadow">
            <div class="card-header">Omzet Bulan Ini (Oktober 2025)</div>
            <div class="card-body">
                <h5 class="card-title">Rp {{ number_format($omzet_bulan_ini, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>
</div>
@endsection  