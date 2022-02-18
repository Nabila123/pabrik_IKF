 
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
                    <h1>Supply Bahan Baku</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Supply Bahan Baku</li>
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
                                                <div class="row">                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Kode Purchase</label>
                                                            <select class="form-control col-md-7 col-xs-12 purchaseId" id="purchaseId" name="purchaseId" style="width: 100%; height: 38px;" required>
                                                                <option value="">Pilih Kode Purchase</option>
                                                                @foreach($purchases as $purchase)
                                                                    <option value="{{$purchase->id}}">{{$purchase->kode}}</option>
                                                                @endforeach
                                                            </select>                                           
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Nama Suplier</label>
                                                            <input class="form-control suplier" required id="suplier" name="suplier" type="text" placeholder="Nama Suplier" readonly>                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Total</label>
                                                            <input type="text" class="form-control total" required id="total" name="total"placeholder="Total" /> 
                                                        </div>
                                                    </div>


                                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                    <div class="col-12 right">
                                                        <table id="materialPO" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="textAlign" style="width: 7%;">No</th>
                                                                    <th class="textAlign">Nama Barang</th>
                                                                    <th class="textAlign">Jumlah Permintaan</th>
                                                                    <th class="textAlign">Jumlah Saat Ini</th>
                                                                    <th class="textAlign">Jumlah Datang</th>
                                                                    <th class="textAlign">Satuan </th>
                                                                    <th class="textAlign">Harga Satuan</th>
                                                                    <th class="textAlign">Amount</th>
                                                                    <th class="textAlign">Remark</th>
                                                                    <th class="textAlign">Diameter</th>
                                                                    <th class="textAlign">Gramasi</th>
                                                                    <th class="textAlign">Bruto </th>
                                                                    <th class="textAlign">Netto </th>
                                                                    <th class="textAlign">Tarra</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign"></tbody>                                                                                       
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

        $('#purchaseId').select2({
            theme: 'bootstrap4'
        });
        

        $(document).on("change", ".purchaseId", function(){
            var purchaseId = $('#purchaseId').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "get",
                url: '{{ url("adminPO/getSuplier") }}/'+purchaseId,
                success: function(response){
                    var data = JSON.parse(response)
                    $('#suplier').val(data);    
                }
            })

            $.ajax({
                type: "get",
                url: '{{ url("adminPO/getDetail") }}/'+purchaseId,
                success: function(response){
                    var data = JSON.parse(response)
                    // console.log(data);
                    var jumlah_data = $('#jumlah_data').val(0);
                    if(data){
                        var nomor = 1;
                        for(var i =0;i < data.length;i++)
                        {
                            var jumlah_data = $('#jumlah_data').val();
                            jumlah_data++;
                            $('#jumlah_data').val(jumlah_data);
                            var dt = "<tr  class='data_"+jumlah_data+"'>";
                            dt += "<td>"+nomor+"</td>";
                            dt += "<td>"+data[i].materialNama;
                            dt += "<input type='hidden' name='materialId[]' value='"+data[i].materialId+"' id='material_"+jumlah_data+"'>";
                            dt += '</td>';
                            dt += "<td>"+data[i].qty+"<input type='hidden' name='qty_permintaan[]' value='"+data[i].qty+"' id='qty_permintaan_"+jumlah_data+"'></td>";
                            dt += "<td>"+data[i].qty_saat_ini+"</td>";
                            dt += "<td><input type='text' name='qty_saat_ini[]' required style='width:60px;' class='form-control qty_saat_ini' id_data='"+jumlah_data+"' value='' id='qty_saat_ini_"+jumlah_data+"'>";
                            dt += '</td>';
                            dt += "<td>"+data[i].unit+"<input type='hidden' name='unit[]' value='"+data[i].unit+"' id='unit_"+jumlah_data+"'> </td>";
                            dt += "<td>"+data[i].unitPrice+"<input type='hidden' name='unitPrice[]' value='"+data[i].unitPrice+"' id='unitPrice_"+jumlah_data+"'> </td>";
                            dt += "<td>"+data[i].amount+"<input type='hidden' name='amount[]' value='"+data[i].amount+"' id='amount_"+jumlah_data+"'> </td>";
                            dt += "<td>"+data[i].remark+"<input type='hidden' name='remark[]' value='"+data[i].remark+"' id='remark_"+jumlah_data+"'> </td>";
                            if(data[i].materialId == 1){
                                dt += "<td><input type='text' required style='width:60px;' class='form-control diameter' id_data='"+jumlah_data+"' name='diameter_"+data[i].materialId+"[]' value='' id='diameter_"+jumlah_data+"'> </td>";
                                dt += "<td><input type='text' required style='width:60px;' class='form-control gramasi' id_data='"+jumlah_data+"' name='gramasi_"+data[i].materialId+"[]' value='' id='gramasi_"+jumlah_data+"'> </td>";
                                dt += "<td><input type='text' required style='width:60px;' class='form-control brutto' id_data='"+jumlah_data+"' name='brutto_"+data[i].materialId+"[]' value='' id='brutto_"+jumlah_data+"'> </td>";
                                dt += "<td><input type='text' required style='width:60px;' class='form-control netto' id_data='"+jumlah_data+"' name='netto_"+data[i].materialId+"[]' value='' id='netto_"+jumlah_data+"'> </td>";
                                dt += "<td><input type='text' required style='width:60px;' class='form-control tarra' id_data='"+jumlah_data+"' name='tarra_"+data[i].materialId+"[]' value='' id='tarra_"+jumlah_data+"'> </td>";
                            }else{
                                dt += '<input type="hidden" name="jumlah_roll_'+data[i].materialId+'" id="jumlah_roll_'+data[i].materialId+'" value="0"/>';
                                dt += '<td colspan="5"><button type="button" id="addRoll" materialId="'+data[i].materialId+'" data='+jumlah_data+' style="float: left" class="btn btn-info btn-flat-right addRoll">Tambah Data Roll</button><td>'
                            }
                            dt += '</tr>';
                            nomor++;
                            
                            $('#materialPO tbody.data').append(dt);    
                        }
                    }
                }
            })
        });

        $(document).on("click", ".addRoll", function(){
            var data = $(this).attr('data');
            var materialId = $(this).attr('materialId');
            var jumlah_roll = $('#jumlah_roll_'+materialId).val();
            jumlah_roll++;
            jumlah_data = data + 1;
            dt = '<tr id="'+jumlah_data+'">';
            dt += '<td colspan="9">Data Roll '+jumlah_roll+'</td>';
            dt += "<td><input type='text' required style='width:60px;' class='form-control diameter' name='diameter_"+materialId+"[]' value='' > </td>";
            dt += "<td><input type='text' required style='width:60px;' class='form-control gramasi' name='gramasi_"+materialId+"[]' value=''> </td>";
            dt += "<td><input type='text' required style='width:60px;' class='form-control brutto' name='brutto_"+materialId+"[]' value=''> </td>";
            dt += "<td><input type='text' required style='width:60px;' class='form-control netto' name='netto_"+materialId+"[]' value=''> </td>";
            dt += "<td><input type='text' required style='width:60px;' class='form-control tarra' name='tarra_"+materialId+"[]' value='' > </td>";
            dt += '</tr>';

            $('tr.data_'+data).after(dt);
            $('#jumlah_roll_'+materialId).val(jumlah_roll);
        });

        $(document).on("focusout", ".harga", function(){
            var harga = $('#harga').val();
            var jumlah = $('#jumlah').val();
            
            hargaTotal = (harga * jumlah);

            $('#totalHarga').val(hargaTotal);
        });

    </script>
@endpush