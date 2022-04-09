 
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
                    <h1> Operator Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Operator Request</li>
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
                                <input type="hidden" id="operator" name="operator" value="{{ \Auth::user()->id }}" class="form-Setrika operator">        
                                
                                <div class="row mb-5">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Jenis Baju </label>
                                            <select class="form-Setrika jenisBaju" id="jenisBaju" name="jenisBaju" style="width: 100%; height: 38px;" >
                                                <option value="{{ $jenisBaju }} ">{{ strtoupper($jenisBaju) }} </option>
                                            </select>                                                                                  
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Nomor PO</label>
                                            <select class="form-Setrika purchaseId" id="purchaseId" name="purchaseId" style="width: 100%; height: 38px;" >
                                                
                                            </select>                                            
                                        </div>
                                    </div> 

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Ukuran Baju</label>
                                            <select class="form-Setrika ukuranBaju" id="ukuranBaju" name="ukuranBaju" style="width: 100%; height: 38px;" >
                                                
                                            </select>                                            
                                        </div>
                                    </div> 

                                    <div class="col-3">
                                        <label>Jumlah Baju</label>
                                        <div class="input-group">                                            
                                            <input type="number" id="jumlah" name="jumlah" class="form-Setrika jumlah " >
                                        </div>
                                        <div id="requestOperatorId">

                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <button type="button" id="TData" class='btn btn-success btn-flat-left TData' style="float: left">Tambah Data</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                        <table id="JahitData" class="table table-bordered textAlign JahitData">
                                            <thead>
                                                <tr>
                                                    <th style="vertical-align: middle;">No</th>
                                                    <th style="vertical-align: middle;">Kode Purchse</th>
                                                    <th style="vertical-align: middle;">Jenis Baju</th>
                                                    <th style="vertical-align: middle;">Ukuran Baju</th>
                                                    <th style="vertical-align: middle;">Jumlah</th>
                                                    <th style="vertical-align: middle;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data">
                                                <?php $no = 1; ?>
                                                @foreach ($operatorRequest as $detail)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $detail->purchase->kode }}</td>
                                                        <td>{{ strtoupper($detail->jenisBaju) }}</td>
                                                        <td>{{ $detail->ukuranBaju }}</td>
                                                        <td>{{ $detail->jumlah }}</td>
                                                        <td align="center">
                                                            @if ($gdSetrika == null)
                                                                <a href="{{ route('GSetrika.operator.update.delete', [$detail->purchaseId, $detail->jenisBaju, $detail->ukuranBaju]) }}" class="btn btn-sm btn-block btn-danger" style="width:40px;"><span class="fa fa-trash"></span></a>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-block btn-danger disabled" style="width:40px;"><span class="fa fa-trash"></span></button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
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
        
        {{--  $('#jenisBaju').select2({
            theme: 'bootstrap4'
        });  --}}

        $(document).ready( function () {
            var jenisBaju = $('#jenisBaju').val();
            var _token = $('#_token').val();

            console.log(purchaseId)
            
            $.ajax({
                type: "post",
                url: '{{ url('GSetrika/getData') }}',
                data: {
                    'jenisBaju' : jenisBaju,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    console.log(data);
                    var purchaseId ="<option value=''>Pilih Nomor PO</option>";
                    for(var i = 0;i < data.length;i++){
                        purchaseId += "<option value="+data[i]['purchaseId']+">"+data[i]['kode']+"</option>";
                    }
                    $('#purchaseId').html(purchaseId);
                }
            })
        });

        $(document).on("change", ".purchaseId", function(){
            var purchaseId = $('#purchaseId').val();
            var jenisBaju = $('#jenisBaju').val();
            var _token = $('#_token').val();

            
            $.ajax({
                type: "get",
                url: '{{ url('GSetrika/operator/getDetailMaterial') }}/'+purchaseId+'/'+jenisBaju+'/'+null+'/'+null,
                
                success: function(response){
                    var data = JSON.parse(response)
                    var ukuranBaju ="<option value=''>Pilih Ukuran Baju</option>";
                    for(var i = 0;i < data.length;i++){
                        ukuranBaju += "<option value="+data+">"+data+"</option>";
                    }
                    $('#ukuranBaju').html(ukuranBaju);
                    console.log(data);
                }
            })
        });

        $(document).on("change", ".ukuranBaju", function(){
            var purchaseId = $('#purchaseId').val();
            var jenisBaju = $('#jenisBaju').val();
            var ukuranBaju = $('#ukuranBaju').val();
            var _token = $('#_token').val();
            $('#requestOperatorId').html('');
            
            $.ajax({
                type: "get",
                url: '{{ url('GSetrika/operator/getDetailMaterial') }}/'+purchaseId+'/'+jenisBaju+'/'+ukuranBaju+'/'+null,
                
                success: function(response){
                    var data = JSON.parse(response)
                    console.log(data);
                    $('#jumlah').val(data['jumlah']);
                    console.log(data.operatorReqId.length)
                    for(var i = 0;i < data.operatorReqId.length; i++){
                        var dt ="<input type='hidden' name='requestOperatorId[]' value='"+data['operatorReqId'][i]+"' id='requestOperatorId_"+i+"'>";
                        $('#requestOperatorId').append(dt);  
                    }
                }
            })
        });

        $(document).on("change", ".jumlah", function(){
            var purchaseId = $('#purchaseId').val();
            var jenisBaju = $('#jenisBaju').val();
            var ukuranBaju = $('#ukuranBaju').val();
            var jumlah = $('#jumlah').val();
            var _token = $('#_token').val();
            $('#requestOperatorId').html('');
            
            $.ajax({
                type: "get",
                url: '{{ url('GSetrika/operator/getDetailMaterial') }}/'+purchaseId+'/'+jenisBaju+'/'+ukuranBaju+'/'+jumlah,
                
                success: function(response){
                    var data = JSON.parse(response)
                    console.log(data);
                    $('#jumlah').val(data['jumlah']);
                    for(var i = 0;i < data.operatorReqId.length; i++){
                        var dt ="<input type='hidden' name='requestOperatorId[]' value='"+data['operatorReqId'][i]+"' id='requestOperatorId_"+i+"'>";
                        $('#requestOperatorId').append(dt);  
                    }
                }
            })
        });
        
        $(document).ready( function () {             
            $(document).on("click", "button.TData", function(e){
                e.preventDefault(); 

                var jenisBaju       = $('#jenisBaju').val();
                var jenisBajuName   = $('#jenisBaju').find('option:selected').text();
                var purchaseId      = $('#purchaseId').val();
                var purchaseKode    = $('#purchaseId').find('option:selected').text();
                var ukuranBaju      = $('#ukuranBaju').val();
                var jumlah          = $('#jumlah').val();
                var operatorReqId   = [];
                for(i=0; i<jumlah; i++){
                    operatorReqId[i]   = $('#requestOperatorId_'+i+'').val();
                }
                
                var jumlah_data     = $('#jumlah_data').val();

                if(jenisBaju != "" && purchaseId != "" && ukuranBaju != "" && jumlah != 0){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<td>"+jumlah_data+"<input type='hidden' name='operatorReqId[]' value='"+operatorReqId+"' id='operatorReqId_"+jumlah_data+"'></td>";
                            table += "<td>"+jenisBajuName+"<input type='hidden' name='jenisBaju[]' value='"+jenisBaju+"' id='jenisBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+purchaseKode+"<input type='hidden' name='purchaseId[]' value='"+purchaseId+"' id='purchaseId_"+jumlah_data+"'></td>";
                            table += "<td>"+ukuranBaju+"<input type='hidden' name='ukuranBaju[]' value='"+ukuranBaju+"' id='ukuranBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+jumlah+"<input type='hidden' name='jumlah[]' value='"+jumlah+"' id='jumlah_"+jumlah_data+"'></td>";
                            
                            table += "<td align='center'>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                        table += "</tr>";

                        $('#jenisBaju').val('');
                        $('#jenisBaju option[value=""]').attr('selected','selected');

                        $('#purchaseId').val('');
                        $('#purchaseId option[value=""]').attr('selected','selected');

                        $('#ukuranBaju').val('');
                        $('#ukuranBaju option[value=""]').attr('selected','selected');

                        $('#jumlah').val('');
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