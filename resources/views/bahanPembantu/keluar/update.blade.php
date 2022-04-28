 
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
                    <h1>Update Keluar Gudang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Request Keluar Gudang</li>
                        <li class="breadcrumb-item active">Update</li>
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
                                <input type="hidden" name="gudangKeluarId" id="gudangKeluarId" value="{{ $data->id }}">   
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Gudang Request</label>
                                                            <select class="form-control gudangRequest" id="gudangRequest" name="gudangRequest" style="width: 100%; height: 38px;" required>
                                                                    <option value="{{ $data->gudangRequestId }}">{{ $data->gudangRequest }}</option>
                                                            </select>                                           
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <div id="checkGudang">
                                                                <label>Barang</label>
                                                               <select class="form-control material" id="materialId" name="material" style="width: 100%; height: 38px;"> 
                                                                </select>
                                                                <input type="hidden" name="jenisId" id="jenisId" class="jenisId">                                        
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Nomor PO</label>
                                                            <select class="form-control kodePurchase" id="kodePurchase" name="kodePurchase" style="width: 100%; height: 38px;">
                                                                
                                                            </select>     
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">  
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <div id="Jumlah">
                                                                <label>Jumlah</label>
                                                                <input type="text" class="form-control qty" id="qty" name="qty"placeholder="qty" /> 
                                                                <input type="hidden" class="form-control qtyHidden" id="qtyHidden" name="qtyHidden"/>    
                                                            </div> 
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="gudangId" id="gudangId" class="gudangId">
                                                    <input type="hidden" name="gudangMaterialDetail" id="gudangMaterialDetail" class="gudangMaterialDetail">
                                                    <div class="col-12 right">
                                                        <div class="form-group">
                                                            <button type="button" id="TBarang" class='btn btn-success btn-flat-right TBarang'>Tambah Barang</button>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                    <div class="col-12 right">
                                                        <table id="requestKeluar" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="textAlign">Nama Barang</th>
                                                                    <th class="textAlign">Nomor PO </th>
                                                                    <th class="textAlign">Jumlah</th>
                                                                    <th class="textAlign">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">
                                                                @foreach ($dataDetail as $detail)
                                                                    <tr>
                                                                        <td>{{ $detail->material->nama }}</td>
                                                                        <td>{{ $detail->purchase->kode }}</td>
                                                                        <td>{{ $detail->qty }}</td>
                                                                        <td><a href="{{ route('GBahanPembantu.keluar.detail.delete', [$data->id, $detail->id, $gudangRequest]) }}" class='btn btn-sm btn-block btn-danger' style='width:40px;'><span class='fa fa-trash'></span></a></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>                                                                                       
                                                        </table>
                                                    </div>
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

        {{--  $('#kodePurchase').select2({
            theme: 'bootstrap4'
        });

        $('#diameter').select2({
            theme: 'bootstrap4'
        });  --}}
        

        $(document).ready( function () {
            var gudangRequest = $('#gudangRequest').val();
            var _token = $('#_token').val();
                  
            var dt = '<label>Jumlah</label>';
                dt += '<input type="text" class="form-control qty" id="qty" name="qty"placeholder="qty" />';
                dt += '<input type="hidden" class="form-control qtyHidden" id="qtyHidden" name="qtyHidden"/>';
            $('#Jumlah').html(dt);
            
            $.ajax({
                type: "get",
                url: '{{ url("GBahanPembantu/keluar/getMaterial") }}/'+gudangRequest+'/'+null,
                success: function(response){
                    var data = JSON.parse(response);
                    {{--  console.log(data.material);  --}}
                    var opt_material ='<option value="">Pilih Barang</option>';
                    for(var i =0;i < data.material.length;i++){
                        opt_material += "<option value="+data.material[i].id+">"+data.material[i].nama+"</option>"
                    }
                    $('#materialId').html(opt_material);
                    $('#jenisId').val(gudangRequest);       
                }
            })

        });

        $(document).on("change", ".material", function(){
            var materialId = $(this).val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "get",
                url: '{{ url("GBahanPembantu/keluar/getPurchase") }}/'+materialId,
                success: function(response){
                    var data = JSON.parse(response);
                    var opt ='<option value="">Pilih Nomor PO</option>';
                    for(var i =0;i < data.purchase.length;i++){
                        opt += "<option value="+data.purchase[i].id+">"+data.purchase[i].kode+"</option>"
                    }
                    $('#kodePurchase').html(opt);                   
                }
            })

        });

        $(document).on("change", ".kodePurchase", function(){
            var purchaseId = $('#kodePurchase').val();
            var materialId = $('#materialId').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "get",
                url: '{{ url("GBahanPembantu/keluar/getGudang") }}/'+materialId+'/'+purchaseId,
                success: function(response){
                    var data = JSON.parse(response);
                    {{--  console.log(data)  --}}
                    var diameter ="<option value=''>Pilih Diameter</option>";
                    for(var i = 0;i < data.diameter.length;i++){
                        diameter += "<option value="+data.diameter[i]+">"+data.diameter[i]+"</option>";
                    }
                    $('#diameter').html(diameter);   
                    $('#gudangId').val(data.gudangId); 
                }
            })
            
            $.ajax({
                type: "get",
                url: '{{ url("GBahanPembantu/keluar/getDetailMaterial") }}/'+materialId+'/'+purchaseId,
                success: function(response){
                    var data = JSON.parse(response);
                    console.log(data)
                    $('#qty').val(data.qty);    
                    $('#qtyHidden').val(data.qty);    
                    $('#gudangMaterialDetail').val(data.gudangMaterialDetail);    
                }
            })
        });

        $(document).ready( function () {
            $(document).on("click", "button.TBarang", function(e){
                e.preventDefault();

                var material                = $('#materialId').val();
                var nama_material           = $('#materialId').find('option:selected').text();
                var purchaseId              = $('#kodePurchase').val();
                var kodePurchase            = $('#kodePurchase').find('option:selected').text();
                var qty                     = $('#qty').val();
                var gudangId                = $('#gudangId').val();
                var gudangMaterialDetail    = $('#gudangMaterialDetail').val();

                var jumlah_data = $('#jumlah_data').val();
                var qtyHidden = $('#qtyHidden').val();
                if (parseFloat(qty) > parseFloat(berat)){
                    console.log(qty)
                    console.log(qtyHidden)
                }else{
                    if((nama_material != "Pilih Material / Bahan" || material != "") && qty != ""){
                        jumlah_data++;
                        $('#jumlah_data').val(jumlah_data);

                        var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<input type='hidden' name='gudangIdArr[]' value='"+gudangId+"' id='gudangId_"+jumlah_data+"'/>";
                            table += "<input type='hidden' name='gudangMaterialDetailArr[]' value='"+gudangMaterialDetail+"' id='gudangMaterialDetail_"+jumlah_data+"'/>";
                            table += "<td>"+nama_material+"<input type='hidden' name='materialIdArr[]' value='"+material+"' id='material_"+jumlah_data+"'></td>";
                            table += "<td>"+kodePurchase+"<input type='hidden' name='purchaseIdArr[]' value='"+purchaseId+"' id='purchaseId_"+jumlah_data+"'></td>";
                            table += "<input type='hidden' name='diameterArr[]' value='0' id='diameter_"+jumlah_data+"'>";
                            table += "<input type='hidden' name='gramasiArr[]' value='0' id='gramasi_"+jumlah_data+"'>";
                            table += "<input type='hidden' name='beratArr[]' value='0' id='berat_"+jumlah_data+"'><";
                            table += "<td>"+qty+"<input type='hidden' name='qtyArr[]' value='"+qty+"' id='jumlah_"+jumlah_data+"'></td>";
                            table += "<td>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                            table += "</tr>";
                            
                        $('#kodePurchase').find('option[value=""]').attr('selected','selected');
                        $('#kodePurchase').val('');
                        $('#diameter').find('option[value=""]').attr('selected','selected');
                        $('#diameter').val('');
                        $('#gramasi').find('option[value=""]').attr('selected','selected');
                        $('#gramasi').val('');
                        $('#qty').val('');
                    }else{
                        alert("Material Dan Jumlah Pemakaian Tidak Boleh Kosong");
                    }
                }

                $('#requestKeluar tbody.data').append(table);
            });
    
            $(document).on("click", "a.del", function(e){
                e.preventDefault();
                var sub = $(this).attr('idsub');
                var jumlahdata = $('#jumlah_data').val();
                
                jumlahdata--;
                $('#jumlah_data').val(jumlahdata);
                $('.data_'+sub+'').remove();
            });
        });

    </script>
@endpush