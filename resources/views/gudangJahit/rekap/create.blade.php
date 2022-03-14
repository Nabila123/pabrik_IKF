 
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

        tr th{
            max-width:100%;
            white-space:nowrap;
        }

        tr td{
            max-width:100%;
            white-space:nowrap;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Rekapan Jahit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Rekapan Jahit</li>
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
                            <h3 class="card-title" style="float: left">
                                Operator : <label style="font-size: 15px;">  {{ \Auth::user()->nama }} </label>
                                <input type="hidden" name="namaOperator" value="{{ \Auth::user()->nama }}">
                            </h3>
                            <h3 class="card-title" style="float: right">
                                Pengerjaan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                  
                        <div class="card-body">
                            @if(isset($message))
                                <div class="alert alert-danger not-found">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <strong>Perhatian!</strong> {{$message}}
                                </div>
                            @endif
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" id="operator" name="operator" value="{{ \Auth::user()->id }}" class="form-control operator">        
                                
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Posisi Jahit </label>
                                            <select class="form-control posisi" id="posisi" name="posisi" style="width: 100%; height: 38px;" >
                                                <option> Pilih Satu </option>
                                                <option value="soom">Soom</option>
                                                <option value="jahit">Jahit</option>
                                                <option value="bawahan">Bawahan</option>
                                            </select>                                                                                  
                                        </div>
                                    </div>                                    

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Nama Pegawai</label>
                                            <select class="form-control pegawaiId" id="pegawaiId" name="pegawaiId" style="width: 100%; height: 38px;" >
                                                
                                            </select>                                                                                  
                                        </div>
                                    </div> 

                                    <div class="col-4">
                                        <label>Tanggal</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="tanggal" name="tanggal" value="{{ date('d F Y') }}" class="form-control tanggal disable" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-header">
                                              <h3 class="card-title">Data Jahit</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">  
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Kode Purchase </label>
                                                            <select class="form-control purchaseId" id="purchaseId" name="purchaseId" style="width: 100%; height: 38px;" >
                                                                
                                                            </select> 
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Jenis Baju</label>
                                                            <select class="form-control jenisBaju" id="jenisBaju" name="jenisBaju" style="width: 100%; height: 38px;" >
                                                                
                                                            </select> 
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Ukuran Baju </label>
                                                            <select class="form-control ukuranBaju" id="ukuranBaju" name="ukuranBaju" style="width: 100%; height: 38px;" >
                                                
                                                            </select>
                                                            <input type="hidden" name="requestOperatorId" id="requestOperatorId">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" id="TData" class='btn btn-success btn-flat-left TData' style="float: left">Tambah Data</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                        <table id="JahitData" class="table table-bordered dataTables_scrollBody textAlign JahitData">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Posisi Jahit</th>
                                                    <th>Nama Pegawai</th>
                                                    <th>Kode Purchase</th>
                                                    <th>Jenis Baju</th>
                                                    <th>Ukuran Baju</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data textAlign">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 mt-3">
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
        
        $('#posisi').select2({
            theme: 'bootstrap4'
        });

        $(document).on("change", ".posisi", function(){
            var posisi = $('#posisi').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('GJahit/getPegawai') }}',
                data: {
                    'posisi' : posisi,
                    'groupBy' : "purchaseId",
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    var pegawaiId ="<option value=''>Pilih Pegawai</option>";
                    for(var i = 0;i < data.pegawai.length;i++){
                        pegawaiId += "<option value="+data['pegawai'][i]['id']+">"+data['pegawai'][i]['nama']+"</option>";
                    }
                    $('#pegawaiId').html(pegawaiId);

                    var purchaseId ="<option value=''>Pilih Kode Purchase</option>";
                    for(var i = 0;i < data.operator.length;i++){
                        purchaseId += "<option value="+data['operator'][i]['purchaseId']+">"+data['operator'][i]['kodePurchase']+"</option>";
                    }
                    $('#purchaseId').html(purchaseId);                   
                }
            })
        });

        $(document).on("change", ".purchaseId", function(){
            var posisi = $('#posisi').val();
            var purchaseId = $('#purchaseId').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('GJahit/getPegawai') }}',
                data: {
                    'posisi' : posisi,
                    'purchaseId' : purchaseId,
                    'groupBy' : "jenisBaju",
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    var jenisBaju ="<option value=''>Pilih Jenis Baju</option>";
                    for(var i = 0;i < data.operator.length;i++){
                        jenisBaju += "<option value="+data['operator'][i]['jenisBaju']+">"+data['operator'][i]['jenisBaju']+"</option>";
                    }
                    $('#jenisBaju').html(jenisBaju);                 
                }
            })
        });

        $(document).on("change", ".jenisBaju", function(){
            var posisi = $('#posisi').val();
            var purchaseId = $('#purchaseId').val();
            var jenisBaju = $('#jenisBaju').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('GJahit/getPegawai') }}',
                data: {
                    'posisi' : posisi,
                    'purchaseId' : purchaseId,
                    'jenisBaju' : jenisBaju,
                    'groupBy' : "ukuranBaju",
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    var ukuranBaju ="<option value=''>Pilih Ukuran Baju</option>";
                    for(var i = 0;i < data.operator.length;i++){
                        ukuranBaju += "<option value="+data['operator'][i]['ukuranBaju']+">"+data['operator'][i]['ukuranBaju']+"</option>";
                    }
                    $('#ukuranBaju').html(ukuranBaju);    
                }
            })
        });

        $(document).on("change", ".ukuranBaju", function(){
            var posisi = $('#posisi').val();
            var purchaseId = $('#purchaseId').val();
            var jenisBaju = $('#jenisBaju').val();
            var ukuranBaju = $('#ukuranBaju').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('GJahit/getPegawai') }}',
                data: {
                    'posisi' : posisi,
                    'purchaseId' : purchaseId,
                    'jenisBaju' : jenisBaju,
                    'ukuranBaju' : ukuranBaju,
                    'groupBy' : "id",
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response) 
                    $('#requestOperatorId').val(data['operator'][0]['requestOperatorId']);             
                }
            })
        });

        $(document).ready( function () {             
            $(document).on("click", "button.TData", function(e){
                e.preventDefault(); 

                var posisi          = $('#posisi').val();
                var pegawaiId       = $('#pegawaiId').val();
                var pegawaiName     = $('#pegawaiId').find('option:selected').text();
                var purchaseId      = $('#purchaseId').val();
                var purchaseKode    = $('#purchaseId').find('option:selected').text();
                var jenisBaju       = $('#jenisBaju').val();
                var ukuranBaju      = $('#ukuranBaju').val();
                var operatorReqId   = $('#requestOperatorId').val();

                var jumlah_data     = $('#jumlah_data').val();

                if(posisi != "" && pegawaiId != "" && purchaseId != "" && jenisBaju != "" && ukuranBaju != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<td>"+jumlah_data+"<input type='hidden' name='operatorReqId[]' value='"+operatorReqId+"' id='operatorReqId_"+jumlah_data+"'></td>";
                            table += "<td>"+posisi+"<input type='hidden' name='posisi[]' value='"+posisi+"' id='posisi_"+jumlah_data+"'></td>";
                            table += "<td>"+pegawaiName+"<input type='hidden' name='pegawaiId[]' value='"+pegawaiId+"' id='pegawaiId_"+jumlah_data+"'></td>";
                            table += "<td>"+purchaseKode+"<input type='hidden' name='purchaseId[]' value='"+purchaseId+"' id='purchaseId_"+jumlah_data+"'></td>";
                            table += "<td>"+jenisBaju+"<input type='hidden' name='jenisBaju[]' value='"+jenisBaju+"' id='jenisBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+ukuranBaju+"<input type='hidden' name='ukuranBaju[]' value='"+ukuranBaju+"' id='ukuranBaju_"+jumlah_data+"'></td>";
                            
                            table += "<td>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                        table += "</tr>";

                        $('#posisi').val('');
                        $('#posisi option[value=""]').attr('selected','selected');

                        $('#pegawaiId').val('');
                        $('#pegawaiId option[value=""]').attr('selected','selected');

                        $('#purchaseId').val('');
                        $('#purchaseId option[value=""]').attr('selected','selected');

                        $('#jenisBaju').val('');
                        $('#jenisBaju option[value=""]').attr('selected','selected');

                        $('#ukuranBaju').val('');
                        $('#ukuranBaju option[value=""]').attr('selected','selected');

                }else{
                    alert("Inputan Tidak Boleh Ada Yang Kosong");
                }

                $('#JahitData tbody.data').append(table);
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