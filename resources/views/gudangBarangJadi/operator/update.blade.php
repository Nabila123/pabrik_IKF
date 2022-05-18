 
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
                    <h1> Penjualan Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Penjualan Barang</li>
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
                                Tanggal Penjualan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
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
                                <input type="hidden" id="penjualanId" name="penjualanId" value="{{ $penjualan->id }}" class="form-control penjualanId">        
                                
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Kode Transaksi </label>
                                            <input type="text" id="kodeTransaksi" name="kodeTransaksi" value="{{$penjualan->kodeTransaksi }}" class="form-control kodeTransaksi disable" readonly>
                                                                                  
                                        </div>
                                    </div>                                    

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Nama Customer (Pelanggan) <sup>Optional</sup></label>
                                            <input type="text" id="customer" name="customer" value="{{ $penjualan->customer }}" class="form-control customer">                                                                                 
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
                                              <h3 class="card-title">Data Barang</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">  
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Kode Barang <sup>Optional</sup> </label>
                                                            <input type="text" id="kodeProduct" name="kodeProduct" class="form-control kodeProduct">                                                                                 
                                                        </div>
                                                    </div>
                                                    <div class="col-3">
                                                        <div class="form-group">
                                                            <label>Jenis Baju</label>
                                                            <select class="form-control jenisBaju" id="jenisBaju" name="jenisBaju" style="width: 100%; height: 38px;" >
                                                                <option> Pilih Satu Jenis Baju</option>
                                                                @foreach ($jenisBaju as $baju)
                                                                    <option value="{{ $baju->jenisBaju }}"> {{ $baju->jenisBaju }}</option>
                                                                @endforeach
                                                            </select> 
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <div class="form-group">
                                                            <label>Ukuran Baju </label>
                                                            <select class="form-control ukuranBaju" id="ukuranBaju" name="ukuranBaju" style="width: 100%; height: 38px;" >
                                                
                                                            </select>
                                                        </div>
                                                    </div>       
                                                    <div class="col-2">
                                                        <div class="form-group">
                                                            <label>Jumlah Baju </label>                                                            
                                                            <input type="text" class="form-control jumlahBaju" name="jumlahBaju" id="jumlahBaju">
                                                            <input type="hidden" style="width:100px;" id="jumlahBajuOld">
                                                        </div>
                                                        <div id="requestOperatorId">

                                                        </div>
                                                    </div>  
                                                    <div class="col-2">
                                                        <div class="form-group">
                                                            <label>Harga Baju </label>
                                                            <input type="text" class="form-control hargaBaju" name="hargaBaju" id="hargaBaju">
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
                                                    <th style="vertical-align: middle;">Kode Barang</th>
                                                    <th style="vertical-align: middle;">Jenis Baju</th>
                                                    <th style="vertical-align: middle;">Ukuran Baju</th>
                                                    <th style="vertical-align: middle;">Jumlah Baju</th>
                                                    <th style="vertical-align: middle;">Harga Baju</th>
                                                    <th style="vertical-align: middle;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data textAlign">
                                                @foreach ($penjualanDetail as $detail)
                                                    <tr>
                                                        <td>{{ $detail->kodeProduct }}</td>
                                                        <td>{{ $detail->jenisBaju }}</td>
                                                        <td>{{ $detail->ukuranBaju }}</td>
                                                        <td>{{ $detail->qty }}</td>
                                                        <td>{{ $detail->harga }}</td>
                                                        <td><a href="{{ route('GBarangJadi.operator.update.delete', [$penjualan->id, $detail->id, $detail->jenisBaju, $detail->ukuranBaju, $detail->qty]) }}" class='btn btn-sm btn-block btn-danger' style='width:40px;'><span class='fa fa-trash'></span></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" style="text-align: right">Total Harga</th>
                                                    <th>
                                                        <div class="totalHarga" id="totalHarga">
                                                            Rp. {{ $penjualan->totalHarga }}
                                                        </div>
                                                        <input type="hidden" name="total" id="total" value="{{ $penjualan->totalHarga }}">
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
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
        function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $(document).on("keyup", ".hargaBaju", function(){
            var rupiah      = $('#hargaBaju').val();
            rupiah = formatRupiah(rupiah, 'Rp. ');

            $('#hargaBaju').val(rupiah);
        });


        $(document).on("change", ".jenisBaju", function(){
            var jenisBaju = $('#jenisBaju').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('GBarangJadi/getBarangJadi') }}',
                data: {
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
            var jenisBaju   = $('#jenisBaju').val();
            var ukuranBaju  = $('#ukuranBaju').val();
            var jumlah_data  = $('#jumlah_data').val();

            var operatorReqId   = [];
            for(i=1; i<=jumlah_data; i++){
                operatorReqId[i]   = $('#operatorReqId_'+i+'').val();
            }
            var _token = $('#_token').val();

            $('#requestOperatorId').html('');
            
            $.ajax({
                type: "post",
                url: '{{ url('GBarangJadi/getBarangJadi') }}',
                data: {
                    'jenisBaju' : jenisBaju,
                    'ukuranBaju' : ukuranBaju,
                    'operatorReqId' : operatorReqId,
                    'groupBy' : "kodeProduct",
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response) 
                    console.log(data['operator']);
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
            var jenisBaju   = $('#jenisBaju').val();
            var ukuranBaju  = $('#ukuranBaju').val();
            var jumlahBaju  = $('#jumlahBaju').val();
            var jumlahBajuOld  = $('#jumlahBajuOld').val();
            var jumlah_data  = $('#jumlah_data').val();

            var operatorReqId   = [];
            for(i=1; i<=jumlah_data; i++){
                operatorReqId[i]   = $('#operatorReqId_'+i+'').val();
            }
            var _token = $('#_token').val();

            $('#requestOperatorId').html('');
            $('#jumlahBaju').css({'border':'1px solid #ced4da'});

            if(parseInt(jumlahBaju) <= parseInt(jumlahBajuOld)){
                $.ajax({
                    type: "post",
                    url: '{{ url('GBarangJadi/getBarangJadi') }}',
                    data: {
                        'jenisBaju' : jenisBaju,
                        'ukuranBaju' : ukuranBaju,
                        'jumlahBaju' : jumlahBaju,
                        'operatorReqId' : operatorReqId,
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

                var kodeProduct     = $('#kodeProduct').val()==""?" - ":$('#kodeProduct').val();
                var jenisBaju       = $('#jenisBaju').val();
                var ukuranBaju      = $('#ukuranBaju').val();
                var jumlahBaju      = $('#jumlahBaju').val();
                var hargaBaju       = $('#hargaBaju').val();
                var totalHarga      = $('#total').val();
                var totalAkhir      = 0;
                var operatorReqId   = [];
                for(i=0; i<jumlahBaju; i++){
                    operatorReqId[i]   = $('#requestOperatorId_'+i+'').val();
                }

                console.log(totalAkhir)
                totalAkhir = parseInt(totalHarga) + (parseInt(hargaBaju.replace(/[^0-9]/g, '')) * parseInt(jumlahBaju));
                console.log(totalAkhir)
                

                var jumlah_data     = $('#jumlah_data').val();

                if(jenisBaju != "" && ukuranBaju != "" && jumlahBaju != "" && hargaBaju != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<input type='hidden' name='operatorReqId[]' value='"+operatorReqId+"' id='operatorReqId_"+jumlah_data+"'>";
                            table += "<td>"+kodeProduct+"<input type='hidden' name='kodeProduct[]' value='"+kodeProduct+"' id='kodeProduct_"+jumlah_data+"'></td>";
                            table += "<td>"+jenisBaju+"<input type='hidden' name='jenisBaju[]' value='"+jenisBaju+"' id='jenisBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+ukuranBaju+"<input type='hidden' name='ukuranBaju[]' value='"+ukuranBaju+"' id='ukuranBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+jumlahBaju+"<input type='hidden' name='jumlahBaju[]' value='"+jumlahBaju+"' id='jumlahBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+hargaBaju+"<input type='hidden' name='hargaBaju[]' value='"+parseInt(hargaBaju.replace(/[^0-9]/g, ''))+"' id='hargaBaju_"+jumlah_data+"'></td>";    
                            
                            table += "<td>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                        table += "</tr>";


                        $('#jenisBaju').val('');
                        $('#jenisBaju option[value=""]').attr('selected','selected');

                        $('#ukuranBaju').val('');
                        $('#ukuranBaju option[value=""]').attr('selected','selected');
                        
                        $('#jumlahBaju').val('');
                        $('#hargaBaju').val('');

                }else{
                    alert("Inputan Tidak Boleh Ada Yang Kosong");
                }

                $('#JahitData tbody.data').append(table);
                $('#totalHarga').html(formatRupiah(String(totalAkhir), 'Rp. '));
                $('#total').val(totalAkhir);
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