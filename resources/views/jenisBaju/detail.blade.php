 
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
                    <h1> Detail Data Jenis Baju</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Jenis Baju</li>
                        <li class="breadcrumb-item active">Detail</li>
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
                            @if(isset($message))
                                <div class="alert alert-danger not-found">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <strong>Perhatian!</strong> {{$message}}
                                </div>
                            @endif
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}"> 
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Jenis</label>
                                            <input class="form-control" name="jenis"  type="text" placeholder="Jenis" value="{{$jenisBaju->jenis}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <input class="form-control" name="ukuran"  type="number" placeholder="Jenis" value="{{$jenisBaju->ukuran}}" readonly>
                                        </div>
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

    <script type="text/javascript">

    </script>
@endpush