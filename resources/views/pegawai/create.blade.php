 
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
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>No Absen</label>
                                            <input class="form-control" name="nip"  type="text" placeholder="No Absen" value="{{ $pegawai->nip }}" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Bagian Pegawai</label>
                                            <select class="form-control" name="kodeBagian" required>
                                                <option value="">-- Pilih Bagian Pegawai --</option>
                                                <option value="potong"> {{ ucfirst("potong") }} </option>                                                
                                                <option value="soom"> {{ ucfirst("soom") }} </option>                                                
                                                <option value="jahit"> {{ ucfirst("jahit") }} </option>                                                
                                                <option value="bawahan"> {{ ucfirst("bawahan") }} </option>                                                
                                                <option value="batil"> {{ ucfirst("Batil") }} </option>                                                
                                                <option value="control"> {{ ucfirst("control") }} </option>                                                
                                                <option value="setrika"> {{ ucfirst("setrika") }} </option>                                                
                                                <option value="bungkus"> {{ ucfirst("bungkus") }} </option>                                                
                                                <option value="gudang"> {{ ucfirst("gudang") }} </option>                                                
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Nama</label>
                                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Pegawai" value="{{ $pegawai->nama }}" required>
                                    </div>
                                    
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="laki" name="jenisKelamin" value="P" checked>
                                                <label for="laki" class="custom-control-label">Laki - Laki</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="perempuan" name="jenisKelamin" value="W">
                                                <label for="perempuan" class="custom-control-label">Perempuan</label>
                                            </div>
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
        $('#material').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush