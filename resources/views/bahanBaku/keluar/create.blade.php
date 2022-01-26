 
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
                    <h1>Gudang Bahan Baku</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Bahan Baku</li>
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
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data" action="{{ route('bahan_baku.supply.store') }}">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">   
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <div class="row
                                                ">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Gudang Request</label>
                                                            <select class="form-control col-md-7 col-xs-12 gudangRequest" id="gudangRequest" name="gudangRequest" style="width: 100%; height: 38px;" required>
                                                                <option value="">Pilih Gudang</option>
                                                                    <option value="rajut">Gudang Rajut</option>
                                                                    <option value="cuci">Gudang Cuci</option>
                                                                    <option value="compact">Gudang Compact</option>
                                                            </select>                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Material</label>
                                                            <select class="form-control materialId" id="materialId" name="materialId" style="width: 100%; height: 38px;" required>
                                                                <option value="">Pilih Material</option>
                                                                @foreach($dataMaterial as $mat)
                                                                    <option value="{{$mat->id}}">{{$mat->nama}}</option>
                                                                @endforeach
                                                            </select>   
                                                            <input type="hidden" name="jenisId" id="jenisId" class="jenisId">                                        
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Kode Purchase</label>
                                                            <select class="form-control kodePurchase" id="kodePurchase" name="kodePurchase" style="width: 100%; height: 38px;" required>
                                                                <option value="">Pilih Kode Purchase</option>
                                                            </select>     
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Jumlah</label>
                                                            <input type="text" class="form-control qty" required id="qty" name="qty"placeholder="qty" /> 
                                                        </div>
                                                    </div>
                                                    

                                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                </div>
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
        $(document).ready( function () {
            $('#example2').DataTable();
        });

        $('#kodePurchase').select2({
            theme: 'bootstrap4'
        });
        

        $(document).on("change", ".materialId", function(){
            var materialId = $('#materialId').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "get",
                url: '{{ url("bahan_baku/keluar/getJenis") }}/'+materialId,
                success: function(response){
                    var data = JSON.parse(response);
                    $('#jenisId').val(data);    
                }
            })

            $.ajax({
                type: "get",
                url: '{{ url("bahan_baku/keluar/getPurchase") }}/'+materialId,
                success: function(response){
                    var data = JSON.parse(response);
                    var opt ="";
                    for(var i =0;i < data.length;i++){
                        opt += "<option value="+data[i].id+">"+data[i].kode+"</option>"
                    }
                    console.log(opt);
                    $('#kodePurchase').append(opt);    
                }
            })

        });

        $(document).on("focusout", ".harga", function(){
            var harga = $('#harga').val();
            var jumlah = $('#jumlah').val();
            
            hargaTotal = (harga * jumlah);

            $('#totalHarga').val(hargaTotal);
        });

    </script>
@endpush