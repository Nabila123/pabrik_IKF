 
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
                    <h1> Proses Inspeksi Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Proses Inspeksi Barang</li>
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
                                Pengerjaan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                  
                        <div class="card-body">
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" id="operator" name="operator" value="{{ \Auth::user()->id }}" class="form-control operator">
                                <input type="hidden" name="gudangStokId" id="gudangStokId" value="{{ $gudangKeluar['gudangStokId'] }}">        
                                <input type="hidden" name="materialId" id="materialId" value="{{ $gudangKeluar['materialId'] }}">        
                                <input type="hidden" name="jenisId" id="jenisId" value="{{ $gudangKeluar['jenisId'] }}">        
                                
                                <div class="row mb-5">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Kode Purchase </label>
                                            <select class="form-control col-md-7 col-xs-12 purchaseId" id="purchaseId" name="purchaseId" style="width: 100%; height: 38px;" required>
                                                <option> Pilih Satu </option>
                                                @for ($i = 0; $i < count($purchaseId); $i++)
                                                    @if ($purchaseId[$i]['terima'] != 0)
                                                        <option value="{{ $purchaseId[$i]['id'] }}">{{ $purchaseId[$i]['kode'] }}</option>
                                                    @endif
                                                @endfor    
                                            </select>                                                                                  
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Operator</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="namaOperator" name="namaOperator" value="{{ \Auth::user()->nama }}" class="form-control namaOperator disabled" readonly>
                                        </div>
                                    </div> 

                                    <div class="col-4">
                                        <label>Nama Suplier</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="namaSuplier" name="namaSuplier" class="form-control namaSuplier disabled" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label>Jenis Kain</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="jenisKain" name="jenisKain" class="form-control jenisKain disabled" readonly>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label>Diameter</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="diameter" name="diameter" class="form-control diameter" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered textAlign">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="vertical-align: middle;">Roll</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Berat</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Pnj. Kain</th>
                                                    <th colspan="6">Kerusakan</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Keterangan</th>
                                                </tr>
                                                <tr>
                                                    <th>Lubang</th>
                                                    <th>Plex</th>
                                                    <th>Belang</th>
                                                    <th>Tanah</th>
                                                    <th>B. Sambung</th>
                                                    <th>Jarum</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> <input class="form-control" type="text" name="rollInput" id="rollInput"> </td>
                                                    <td> <input class="form-control" type="text" name="beratInput" id="beratInput"> </td>
                                                    <td> <input class="form-control" type="text" name="panjangInput" id="panjangInput"> </td>
                                                    <td> 
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" name="lubangInput" id="lubangInput">
                                                                <label for="lubangInput"> </label>
                                                              </div>    
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" name="plexInput" id="plexInput">
                                                                <label for="plexInput"> </label>
                                                            </div>    
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" name="belangInput" id="belangInput">
                                                                <label for="belangInput"> </label>
                                                            </div>    
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" name="tanahInput" id="tanahInput">
                                                                <label for="tanahInput"> </label>
                                                            </div>    
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" name="sambungInput" id="sambungInput">
                                                                <label for="sambungInput"> </label>
                                                            </div>    
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-primary d-inline">
                                                                <input type="checkbox" name="jarumInput" id="jarumInput">
                                                                <label for="jarumInput"> </label>
                                                            </div>    
                                                        </div>    
                                                    </td>
                                                    <td> <input class="form-control" type="text" name="ketInput" id="ketInput"> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 mb-5">
                                        <div class="form-group">
                                            <button type="button" id="TData" class='btn btn-success btn-flat-right TData' style="float: right">Tambah Data</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-12">
                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                        <table id="InspeksiData" class="table table-bordered textAlign InspeksiData">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="vertical-align: middle;">No</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Roll</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Berat</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Pnj. Kain</th>
                                                    <th colspan="6">Kerusakan</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Keterangan</th>
                                                    <th rowspan="2" style="vertical-align: middle;">Action</th>
                                                </tr>
                                                <tr>
                                                    <th>Lubang</th>
                                                    <th>Plex</th>
                                                    <th>Belang</th>
                                                    <th>Tanah</th>
                                                    <th>B. Sambung</th>
                                                    <th>Jarum</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data"> </tbody>
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
        $('#purchaseId').select2({
            theme: 'bootstrap4'
        });

        $(document).on("change", ".purchaseId", function(){
            var purchaseId = $('#purchaseId').val();
            var materialId = $('#materialId').val();
            var jenisId = $('#jenisId').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('purchase/getData') }}',
                data: {
                    'purchaseId' : purchaseId,
                    'materialId' : materialId,
                    'jenisId' : jenisId,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    $('.namaSuplier').val(data.suplierName);
                    $('.jenisKain').val(data.nama);
                    console.log(data);
                }
            })
        });
        
        $(document).ready( function () {             
            $(document).on("click", "button.TData", function(e){
                e.preventDefault(); 

                var rollInput       = $('#rollInput').val();
                var beratInput      = $('#beratInput').val();
                var panjangInput    = $('#panjangInput').val();
                var lubangInput     = $('#lubangInput').is(":checked") ? 1 : 0 ;
                var plexInput       = $('#plexInput').is(":checked") ? 1 : 0 ;
                var belangInput     = $('#belangInput').is(":checked") ? 1 : 0 ;
                var tanahInput      = $('#tanahInput').is(":checked") ? 1 : 0 ;
                var sambungInput    = $('#sambungInput').is(":checked") ? 1 : 0 ;
                var jarumInput      = $('#jarumInput').is(":checked") ? 1 : 0 ;
                var ketInput        = $('#ketInput').val();

                var jumlah_data = $('#jumlah_data').val();

                if(rollInput != "" && beratInput != "" && panjangInput != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<td>"+jumlah_data+"</td>";
                            table += "<td>"+rollInput+"<input type='hidden' name='roll[]' value='"+rollInput+"' id='roll_"+jumlah_data+"'></td>";
                            table += "<td>"+beratInput+"<input type='hidden' name='berat[]' value='"+beratInput+"' id='berat_"+jumlah_data+"'></td>";
                            table += "<td>"+panjangInput+"<input type='hidden' name='panjang[]' value='"+panjangInput+"' id='panjang_"+jumlah_data+"'></td>";
                            table += "<td>"+lubangInput+"<input type='hidden' name='lubang[]' value='"+lubangInput+"' id='lubang_"+jumlah_data+"'></td>";
                            table += "<td>"+plexInput+"<input type='hidden' name='plex[]' value='"+plexInput+"' id='plex_"+jumlah_data+"'></td>";
                            table += "<td>"+belangInput+"<input type='hidden' name='belang[]' value='"+belangInput+"' id='belang_"+jumlah_data+"'></td>";
                            table += "<td>"+tanahInput+"<input type='hidden' name='tanah[]' value='"+tanahInput+"' id='tanah_"+jumlah_data+"'></td>";
                            table += "<td>"+sambungInput+"<input type='hidden' name='sambung[]' value='"+sambungInput+"' id='sambung_"+jumlah_data+"'></td>";
                            table += "<td>"+jarumInput+"<input type='hidden' name='jarum[]' value='"+jarumInput+"' id='jarum_"+jumlah_data+"'></td>";
                            table += "<td>"+ketInput+"<input type='hidden' name='ket[]' value='"+ketInput+"' id='ket_"+jumlah_data+"'></td>";

                            table += "<td>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                        table += "</tr>";

                        $('#rollInput').val('');
                        $('#beratInput').val('');
                        $('#panjangInput').val('');
                        $('#lubangInput').prop('checked', false);
                        $('#plexInput').prop('checked', false);
                        $('#belangInput').prop('checked', false);
                        $('#tanahInput').prop('checked', false);
                        $('#sambungInput').prop('checked', false);
                        $('#jarumInput').prop('checked', false);
                        $('#ketInput').val('');
                }else{
                    alert("Data Roll, Berat & Panjang Tidak Boleh Kosong");
                }

                $('#InspeksiData tbody.data').append(table);
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