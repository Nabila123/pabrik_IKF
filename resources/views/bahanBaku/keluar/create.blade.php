 
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
                    <h1>Request Keluar Gudang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Request Keluar Gudang</li>
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
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data" action="{{ route('bahan_baku.keluar.store') }}">                    
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
                                                                    <option value=1>Gudang Rajut</option>
                                                                    <option value=2>Gudang Cuci</option>
                                                                    <option value=3>Gudang Inspeksi</option>
                                                            </select>                                           
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Barang</label>
                                                            <input type="text" class="form-control material" id="material" name="material" style="width: 100%; height: 38px;" readonly> 
                                                            <input type="hidden" name="jenisId" id="jenisId" class="jenisId">            
                                                            <input type="hidden" name="materialId" id="materialId" class="materialId">                                        
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Kode Purchase</label>
                                                            <select class="form-control kodePurchase" id="kodePurchase" name="kodePurchase" style="width: 100%; height: 38px;">
                                                                
                                                            </select>     
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Jumlah</label>
                                                            <input type="text" class="form-control qty" id="qty" name="qty"placeholder="qty" /> 
                                                            <input type="hidden" class="form-control qtyHidden" id="qtyHidden" name="qtyHidden"/> 
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="gStokId" id="gStokId" class="gStokId">
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
                                                                    <th class="textAlign">No</th>
                                                                    <th class="textAlign">Nama Barang</th>
                                                                    <th class="textAlign">Kode Purchase </th>
                                                                    <th class="textAlign">Jumlah</th>
                                                                    <th class="textAlign">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">
                                                                
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

        $('#kodePurchase').select2({
            theme: 'bootstrap4'
        });
        

        $(document).on("change", ".gudangRequest", function(){
            var gudangRequest = $('#gudangRequest').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "get",
                url: '{{ url("bahan_baku/keluar/getMaterial") }}/'+gudangRequest,
                success: function(response){
                    var data = JSON.parse(response);
                    console.log(data.material);
                    $('#materialId').val(data.material.id);
                    $('#material').val(data.material.nama);
                    $('#jenisId').val(gudangRequest);
                    var opt ="<option value=''>Pilih Kode Purchase</option>";
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
                url: '{{ url("bahan_baku/keluar/getGudang") }}/'+materialId+'/'+purchaseId,
                success: function(response){
                    var data = JSON.parse(response);
                    $('#qty').val(data.qty);    
                    $('#qtyHidden').val(data.qty);    
                    $('#gStokId').val(data.id);    
                }
            })
        });

        $(document).ready( function () {
            $(document).on("click", "button.TBarang", function(e){
                e.preventDefault();

                var material        = $('#materialId').val();
                var nama_material   = $('#material').val();
                var purchaseId      = $('#kodePurchase').val();
                var kodePurchase    = $('#kodePurchase').find('option:selected').text();
                var qty             = $('#qty').val();
                var gStokId         = $('#gStokId').val();

                var jumlah_data = $('#jumlah_data').val();
                var qtyHidden = $('#qtyHidden').val();
                if (qty > qtyHidden){
                    alert("Jumlah tidak dapat melebihi stok di Gudang saat ini!\nStok di Gudang : "+qtyHidden);
                }else{
                    if((nama_material != "Pilih Material / Bahan" || material != "") && qty != ""){
                        jumlah_data++;
                        $('#jumlah_data').val(jumlah_data);

                        var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<td>"+jumlah_data+"</td>";
                            table += "<input type='hidden' name='gStokIdArr[]' value='"+gStokId
                            +"' id='gStokId_"+jumlah_data+"'/>";
                            table += "<td>"+nama_material+"<input type='hidden' name='materialIdArr[]' value='"+material+"' id='material_"+jumlah_data+"'></td>";
                            table += "<td>"+kodePurchase+"<input type='hidden' name='purchaseIdArr[]' value='"+purchaseId+"' id='purchaseId_"+jumlah_data+"'></td>";
                            table += "<td>"+qty+"<input type='hidden' name='qtyArr[]' value='"+qty+"' id='jumlah_"+jumlah_data+"'></td>";
                            table += "<td>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                            table += "</tr>";

                        $('#kodePurchase option[value=""]').attr('selected','selected');
                        $('#kodePurchase').val('');
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