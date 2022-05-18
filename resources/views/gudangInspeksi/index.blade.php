 
@extends('layouts.app')

@push('page_css') 
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="#">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($dataStok as $stok)
                    <div class="col-12 col-sm-12 col-md-4">
                        <div class="info-box mb-4">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">{{$stok['nama']}}</span>
                                <span class="info-box-number"> {{$stok['qty']}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="info-box mb-4">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list-alt"></i></span>
            
                        <div class="info-box-content">
                            <span class="info-box-text">Ambil Dari Gudang</span>
                            <span class="info-box-number"> {{ $keluar }} </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-6">
                    <div class="info-box mb-4">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-upload"></i></span>
        
                        <div class="info-box-content">
                            <span class="info-box-text">Kembalikan Ke Gudang</span>
                            <span class="info-box-number"> {{ $masuk }} </span>
                        </div>
                    </div>
                </div>        
            </div>
        </div>
    </section>
@endsection


@push('page_scripts') 
@endpush