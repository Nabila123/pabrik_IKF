 
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
                    <h1> Reject From Control</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Reject From Control</li>
                        <li class="breadcrumb-item active">Update Data</li>
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
                                <input type="hidden" id="id" name="id" value="{{ $gdJahitReject->id }}" class="form-control id">        
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-header">
                                              <h3 class="card-title">Data Reject</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">  
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Kode Purchase </label>
                                                            <select class="form-control purchaseId" id="purchaseId" name="purchaseId" style="width: 100%; height: 38px;" >
                                                                <option> Pilih Salah Satu</option>
                                                                @for ($i = 0; $i < count($purchases); $i++)
                                                                    <option value="{{ $purchases[$i]['purchaseId'] }}"> {{ $purchases[$i]['kodePurchase'] }}</option>
                                                                @endfor 
                                                            </select> 
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Jenis Baju</label>
                                                            <select class="form-control jenisBaju" id="jenisBaju" name="jenisBaju" style="width: 100%; height: 38px;" >
                                                                
                                                            </select> 
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Ukuran Baju </label>
                                                            <select class="form-control ukuranBaju" id="ukuranBaju" name="ukuranBaju" style="width: 100%; height: 38px;" >
                                                
                                                            </select>
                                                        </div>
                                                    </div>       
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Jumlah Baju </label>                                                            
                                                            <input type="text" style="width:100px;" class="form-control jumlahBaju" name="jumlahBaju" id="jumlahBaju">
                                                            <input type="hidden" style="width:100px;" id="jumlahBajuOld">
                                                        </div>
                                                        <div id="requestOperatorId">

                                                        </div>
                                                    </div> 
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label>Keterangan <sup>(Optional)</sup></label>
                                                            <textarea name="keterangan" id="keterangan" class="form-control keterangan" rows="5" placeholder="Keterangan Reject ..."></textarea>
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
                                                    <th style="vertical-align: middle;">No</th>
                                                    <th style="vertical-align: middle;">Kode Purchase</th>
                                                    <th style="vertical-align: middle;">Jenis Baju</th>
                                                    <th style="vertical-align: middle;">Ukuran Baju</th>
                                                    <th style="vertical-align: middle;">Jumlah Baju</th>
                                                    <th style="vertical-align: middle;">Keterangan Reject</th>
                                                    <th style="vertical-align: middle;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data textAlign">
                                                <?php $no = 1; ?>
                                                @foreach ($jahitRejectDetail as $detail)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $detail->kodePurchase }}</td>
                                                        <td>{{ $detail->jenisBaju }}</td>
                                                        <td>{{ $detail->ukuranBaju }}</td>
                                                        <td>1</td>
                                                        <td>{{ $detail->keterangan }}</td>
                                                        <td align="center">
                                                            <a href="{{ route('GControl.reject.update.delete', [$gdJahitReject->id, $detail->id]) }}" class="btn btn-sm btn-block btn-danger" style="width:40px;"><span class="fa fa-trash"></span></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
        
        $('#pegawaiId').select2({
            theme: 'bootstrap4'
        });

        $(document).on("change", ".purchaseId", function(){
            var purchaseId = $('#purchaseId').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('GControl/getReject') }}',
                data: {
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
                url: '{{ url('GControl/getReject') }}',
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
            var posisi      = $('#posisi').val();
            var purchaseId  = $('#purchaseId').val();
            var jenisBaju   = $('#jenisBaju').val();
            var ukuranBaju  = $('#ukuranBaju').val();
            var jumlahBaju  = $('#jumlahBaju').val();
            var jumlah_data  = $('#jumlah_data').val();

            var operatorReqId   = [];
            var operatorReqPurchaseId = [];
            for(i=1; i<=jumlah_data; i++){
                operatorReqId[i]   = $('#operatorReqId_'+i+'').val();
                operatorReqPurchaseId[i]   = $('#purchaseId_'+i+'').val();
            }

            console.log(jumlah_data)
            console.log(operatorReqId)
            var _token = $('#_token').val();

            $('#requestOperatorId').html('');
            
            $.ajax({
                type: "post",
                url: '{{ url('GControl/getReject') }}',
                data: {
                    'posisi' : posisi,
                    'purchaseId' : purchaseId,
                    'jenisBaju' : jenisBaju,
                    'ukuranBaju' : ukuranBaju,
                    'operatorReqId' : operatorReqId,
                    'operatorReqPurchaseId' : operatorReqPurchaseId,
                    'groupBy' : "id",
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response) 
                    console.log(data);
                    $('#jumlahBaju').val(data['operator']['jumlahBaju']);
                    $('#jumlahBajuOld').val(data['operator']['jumlahBaju']);
                    for(var i = 0;i < data.operator.requestOperatorId.length; i++){
                        var dt ="<input type='hidden' name='requestOperatorId[]' value='"+data['operator']['requestOperatorId'][i]+"' id='requestOperatorId_"+i+"'>";
                        $('#requestOperatorId').append(dt);  
                    }

                }
            })
        });

        $(document).on("keyup", ".jumlahBaju", function(){
            var posisi      = $('#posisi').val();
            var purchaseId  = $('#purchaseId').val();
            var jenisBaju   = $('#jenisBaju').val();
            var ukuranBaju  = $('#ukuranBaju').val();
            var jumlahBaju  = $('#jumlahBaju').val();
            var jumlahBajuOld  = $('#jumlahBajuOld').val();
            var jumlah_data  = $('#jumlah_data').val();

            var operatorReqId   = [];
            var operatorReqPurchaseId = [];
            for(i=1; i<=jumlah_data; i++){
                operatorReqId[i]   = $('#operatorReqId_'+i+'').val();
                operatorReqPurchaseId[i]   = $('#purchaseId_'+i+'').val();
            }
            var _token = $('#_token').val();

            $('#requestOperatorId').html('');
            
            if(jumlahBaju <= jumlahBajuOld){
                $.ajax({
                    type: "post",
                    url: '{{ url('GControl/getReject') }}',
                    data: {
                        'posisi' : posisi,
                        'purchaseId' : purchaseId,
                        'jenisBaju' : jenisBaju,
                        'ukuranBaju' : ukuranBaju,
                        'jumlahBaju' : jumlahBaju,
                        'operatorReqId' : operatorReqId,
                        'operatorReqPurchaseId' : operatorReqPurchaseId,
                        'groupBy' : "id",
                        '_token': _token
                    },
                    success: function(response){
                        var data = JSON.parse(response) 
                        console.log(data);
                        $('#jumlahBaju').css({'border':'1px solid #ced4da'});
                        $('#jumlahBaju').val(data['operator']['jumlahBaju']);
                        for(var i = 0;i < data.operator.requestOperatorId.length; i++){
                            var dt ="<input type='hidden' name='requestOperatorId[]' value='"+data['operator']['requestOperatorId'][i]+"' id='requestOperatorId_"+i+"'>";
                            $('#requestOperatorId').append(dt);  
                        }
                    }
                })
            }else{
                $('#jumlahBaju').css({'border':'2px solid #e74c3c'});
            }
        });

        $(document).ready( function () {             
            $(document).on("click", "button.TData", function(e){
                e.preventDefault(); 

                var pegawaiId       = $('#pegawaiId').val();
                var pegawaiName     = $('#pegawaiId').find('option:selected').text();
                var purchaseId      = $('#purchaseId').val();
                var purchaseKode    = $('#purchaseId').find('option:selected').text();
                var jenisBaju       = $('#jenisBaju').val();
                var ukuranBaju      = $('#ukuranBaju').val();
                var jumlahBaju      = $('#jumlahBaju').val();
                var keterangan      = $('#keterangan').val();
                var operatorReqId   = [];
                for(i=0; i<jumlahBaju; i++){
                    operatorReqId[i]   = $('#requestOperatorId_'+i+'').val();
                }

                console.log(pegawaiId)

                var jumlah_data     = $('#jumlah_data').val();

                if(pegawaiId != "Pilih Satu Pegawai" && purchaseId != "" && jenisBaju != "" && ukuranBaju != "" && jumlahBaju != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<td>"+jumlah_data+"<input type='hidden' name='operatorReqId[]' value='"+operatorReqId+"' id='operatorReqId_"+jumlah_data+"'></td>";
                            table += "<td>"+purchaseKode+"<input type='hidden' name='purchaseId[]' value='"+purchaseId+"' id='purchaseId_"+jumlah_data+"'></td>";
                            table += "<td>"+jenisBaju+"<input type='hidden' name='jenisBaju[]' value='"+jenisBaju+"' id='jenisBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+ukuranBaju+"<input type='hidden' name='ukuranBaju[]' value='"+ukuranBaju+"' id='ukuranBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+jumlahBaju+"<input type='hidden' name='jumlahBaju[]' value='"+jumlahBaju+"' id='jumlahBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+keterangan+"<input type='hidden' name='keterangan[]' value='"+keterangan+"' id='keterangan_"+jumlah_data+"'></td>";
                                                        
                            table += "<td align='center'>";
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
                        
                        $('#jumlahBaju').val('');
                        $('#keterangan').val('');

                        $('#soom').prop('checked', false);
                        $('#jahit').prop('checked', false);
                        $('#bawahan').prop('checked', false);

                        $('#ketJahit').html('');

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