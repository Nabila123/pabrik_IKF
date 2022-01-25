 
@extends('layouts.app')

@push('page_css') 
    <style>
        .textAlign {
            vertical-align: middle; 
            text-align: center;
            font-size: 15px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
            border-radius: 10px;
        }
        
        .dataTables_scrollBody::-webkit-scrollbar {
            width: 6px;
            height: 5px;
            background-color: #F5F5F5;
        }
        
        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background-color: #777;
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Pengambalian Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Pengembalian Barang</li>
                        <li class="breadcrumb-item active">Tambah Data</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">                        
                        <div class="card-body">
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" name="id" id="id" value="{{ $gCuciRequest->id }}">           
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Kode Purchase</label>
                                            <input class="form-control purchaseKode disable" value="{{ $gCuciRequest->purchase->kode }}"  id="purchaseKode" name="purchaseKode"  type="text" placeholder="Kode Purchase" readonly>                                            
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Tanggal Pengajuan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" id="pengajuanDate" name="pengajuanDate" class="form-control disable pengajuanDate" value="{{ date('d F Y') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask readonly>
                                        </div>
                                    </div>                                    
                                    <div class="col-6">
                                        <label>Nama Barang</label> 
                                        <div class="input-group">                                            
                                            <input type="text" value="{{ $gCuciRequest->material->nama }}" id="pengirimanDate" name="pengirimanDate" class="form-control"/>
                                        </div>                                        
                                    </div>
                                    <div class="col-6">
                                        <label>Satuan</label>
                                        <div class="input-group">                                            
                                            <input type="text" value="{{ $gCuciRequest->material->satuan }}" id="jatuhTempoDate" name="jatuhTempoDate" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Ajukan Pengembalian</button>
                                    </div>
                                </div>
                            </form>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('page_scripts') 
    <script type="text/javascript"> </script>
@endpush