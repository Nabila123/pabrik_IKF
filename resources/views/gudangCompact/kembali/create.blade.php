 
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
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title" style="float: right">
                                Pengembalian <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                 
                        <div class="card-body">
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" name="id" id="id" value="{{ $gCompactRequest->id }}">  
                                {{--  @for ($i = 0; $i < count($purchaseId); $i++)
                                    <input type="hidden" name="purchaseId[]" id="purchaseId" value="{{ $purchaseId[$i] }}"> 
                                @endfor         
                                
                                <div class="row mb-5">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Nama Barang</label>
                                            <select class="form-control col-md-7 col-xs-12 material" id="material" name="material" style="width: 100%; height: 38px;" readonly>
                                                @foreach($materials as $material)
                                                    @if ($material->id == 3)
                                                        <option value="{{$material->id}}">{{$material->nama}}</option>
                                                    @endif
                                                @endforeach
                                            </select>                                           
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Satuan</label>
                                        <div class="input-group">                                            
                                            <input type="hidden" id="jenisId" name="jenisId" class="form-control jenisId" required>
                                            <input type="text" id="satuan" name="satuan" class="form-control satuan disabled" readonly>
                                        </div>
                                    </div>                                                                  
                                </div>  --}}
                                <div class="row mb-5">
                                    <div class="col-12">
                                        <table id="example2" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="textAlign">Bahan</th>
                                                    <th class="textAlign">satuan</th>
                                                    <th class="textAlign">Nomor PO</th>
                                                    <th class="textAlign">Gramasi</th>
                                                    <th class="textAlign">Diamater</th>
                                                    <th class="textAlign">Berat</th>
                                                    <th class="textAlign">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody class="textAlign">
                                                @foreach ($gCompactRequestDetail as $detail)
                                                    <tr>
                                                        <td align="center">
                                                            <input type="hidden" name="materialId" value="{{ $material->id }}">
                                                            <input style='width:120px; text-align:center;' type="text" name="materialName" id="materialName" value="{{ $material->nama }}" class="form-control materialName disabled" readonly>
                                                        </td>
                                                        <td align="center">
                                                            <input style='width:60px; text-align:center;' type="text" name="satuan" id="satuan" value="{{ $material->satuan }}" class="form-control satuan disabled" readonly>
                                                        </td>
                                                        <td>{{ $detail->purchase->kode }}</td>
                                                        <td>{{ $detail->gramasi }}</td>
                                                        <td>{{ $detail->diameter }}</td>
                                                        <td>{{ $detail->berat }}</td>
                                                        <td>{{ $detail->qty }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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