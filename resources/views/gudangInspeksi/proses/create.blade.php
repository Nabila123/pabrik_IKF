 
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
                    <h1> Proses Inspeksi Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Proses Inspeksi Barang</li>
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
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title" style="float: right">
                                Pengerjaan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                  
                        <div class="card-body">
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                
                                <div class="row mb-5">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Kode Purchase </label>
                                            <select class="form-control col-md-7 col-xs-12 material" id="material" name="material" style="width: 100%; height: 38px;" required>
                                                <option> Pilih Satu </option>
                                                @for ($i = 0; $i < count($purchaseId); $i++)
                                                <option value="{{ $purchaseId[$i]['id'] }}">{{ $purchaseId[$i]['kode'] }}</option>
                                                @endfor    
                                            </select>                                          
                                            <input type="hidden" id="jenisId" name="jenisId" class="form-control jenisId disabled" readonly>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Operator</label>
                                        <div class="input-group">                                            
                                            <input type="hidden" id="operator" name="operator" value="{{ \Auth::user()->id }}" class="form-control operator disabled" readonly>
                                            <input type="text" id="namaOperator" name="namaOperator" value="{{ \Auth::user()->nama }}" class="form-control namaOperator disabled" readonly>
                                        </div>
                                    </div> 

                                    <div class="col-4">
                                        <label>Nama Suplier</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="namaSuplier" name="namaSuplier" class="form-control namaSuplier">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label>Jenis Kain</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="jenisId" name="jenisId" class="form-control jenisId">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label>Diameter</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="diameter" name="diameter" class="form-control diameter">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered textAlign">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="vertical-align: middle;">Roll</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Berat</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Pnj. Kain</th>
                                                    <th colspan="6">Kerusakan</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Keterangan</th>
                                                </tr>
                                                <tr>
                                                    <th>Lubang</th>
                                                    <th>Plex</th>
                                                    <th>Belang</th>
                                                    <th>Tanah</th>
                                                    <th>B. Sambung</th>
                                                    <th>Jarum</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                    <td> <input class="form-control" type="text" name="" id=""> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Simpan Data</button>
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

        $(document).on("change", ".material", function(){
            var materialId = $('#material').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('material/getSatuan') }}',
                data: {
                    'materialId' : materialId,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    $('.satuan').val(data.satuan);
                    $('.jenisId').val(data.jenisId);
                    console.log(data.satuan);
                }
            })
        });
        
        $(document).ready( function () {
            $('#example2').DataTable( {
                "responsive": true,
            });            
        });
    </script>
@endpush