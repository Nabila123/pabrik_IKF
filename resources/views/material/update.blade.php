 
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
                    <h1> Tambah Data Material</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Material</li>
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
                                            <label>Jenis Barang</label>
                                            <select class="form-control" name="jenisId" required>
                                                <option value="">-- Pilih Jenis Barang --</option>
                                                @foreach($jeniss as $jenis)
                                                    <?php $selected = ($material->jenisId == $jenis->id) ? 'selected' : ''; ?>
                                                    <option value="{{$jenis->id}}" {{$selected}}>{{$jenis->nama}}</option>
                                                @endforeach
                                            </select>                                             
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label>Nama</label>
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Material" value="{{$material->nama}}" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Satuan</label>
                                            <input class="form-control" name="satuan"  type="text" placeholder="Satuan" value="{{$material->satuan}}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <select class="form-control" name="keterangan" required>
                                                <?php $selected = ($material->keterangan == 'Bahan Baku') ? 'selected' : ''; ?>
                                                <option value="Bahan Baku" {{$selected}}>Bahan Baku</option>
                                                <?php $selected = ($material->keterangan == 'Bahan Bantu / Merchandise') ? 'selected' : ''; ?>
                                                <option value="Bahan Bantu / Merchandise" {{$selected}}>Bahan Bantu / Merchandise</option>
                                            </select>  
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Simpan Data</button>
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