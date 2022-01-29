 
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
                    <h1> Pemindahan Ke Gudang Compact</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Pemindahan Barang</li>
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
                                Pemindahan <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                 
                        <div class="card-body">
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" name="id" id="id" value="{{ $gCuciRequest->id }}">           
                                
                                <div class="row mb-5">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Nama Barang</label>
                                            <select class="form-control col-md-7 col-xs-12 material" id="material" name="material" style="width: 100%; height: 38px;" required>
                                                @foreach($materials as $material)
                                                    @if ($material->id == 2)
                                                        <option value="{{$material->id}}">{{$material->nama}}</option>
                                                    @endif
                                                @endforeach
                                            </select>                                          
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Satuan</label>
                                        <div class="input-group">                                            
                                            <input type="hidden" id="jenisId" name="jenisId" class="form-control jenisId disabled" readonly>
                                            <input type="text" id="satuan" name="satuan" class="form-control satuan disabled" readonly>
                                        </div>
                                    </div>                                                                  
                                </div>
                                <div class="row mb-5">
                                    <div class="col-12">
                                        <table id="example2" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kode Purchase</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($gCuciRequestDetail as $detail)
                                                    <tr>
                                                        <td>{{ $detail->purchase->kode }}</td>
                                                        <td>{{ $detail->qty }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Ajukan Pemindahan</button>
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

        $(document).ready( function () {
            $('#example2').DataTable( {
                "responsive": true,
            });

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
    </script>
@endpush