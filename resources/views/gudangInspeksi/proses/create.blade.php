 
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
                                <input type="hidden" name="materialId" id="materialId" value="{{ $gudangKeluar['materialId'] }}">        
                                <input type="hidden" name="jenisId" id="jenisId" value="{{ $gudangKeluar['jenisId'] }}">        
                                
                                <div class="row mb-5">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Kode Purchase </label>
                                            <select class="form-control col-md-7 col-xs-12 purchaseId" id="purchaseId" name="purchaseId" style="width: 100%; height: 38px;" required>
                                                <option> Pilih Satu </option>
                                                @for ($i = 0; $i < count($purchaseId); $i++)
                                                    @if ($purchaseId[$i]['terima'] != 0)
                                                        <option value="{{ $purchaseId[$i]['id'] }} - {{ $purchaseId[$i]['gdKeluarId'] }}">{{ $purchaseId[$i]['kode'] }} - {{ date('d F Y', strtotime($purchaseId[$i]['tanggal'])) }}</option>
                                                    @endif
                                                @endfor    
                                            </select>                                                                                  
                                        </div>
                                    </div>
                                    <div class="col-4">
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
                                    <div class="col-3">
                                        <label>Jenis Kain</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="jenisKain" name="jenisKain" class="form-control jenisKain disabled" readonly>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>diameter</label>
                                            <select class="form-control diameter" id="diameter" name="diameter" style="width: 100%; height: 38px;" required>
                                                
                                            </select>                                                        
                                        </div>
                                    </div>                                    

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Gramasi</label>
                                            <select class="form-control gramasi" id="gramasi" name="gramasi" style="width: 100%; height: 38px;" required>
                                                
                                            </select>                                                        
                                        </div>
                                    </div>    

                                    <div class="col-3">
                                        <label>Jumlah Roll</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="jumlah" name="jumlah" class="form-control jumlah disabled" readonly>
                                        </div>
                                    </div>                                 
                                    
                                </div>
                                <input type="hidden" name="gdDetailMaterialId" class="gdDetailMaterialId" id="gdDetailMaterialId">
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
                                                        <div class="form-group">
                                                                <input style='width:70px;' class="form-control" type="number" name="lubangInput" id="lubangInput">
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group">
                                                                <input style='width:70px;' class="form-control" type="number" name="plexInput" id="plexInput">
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group">
                                                                <input style='width:70px;' class="form-control" type="number" name="belangInput" id="belangInput">
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group">
                                                                <input style='width:70px;' class="form-control" type="number" name="tanahInput" id="tanahInput">
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group">
                                                                <input style='width:70px;' class="form-control" type="number" name="sambungInput" id="sambungInput">
                                                        </div>    
                                                    </td>
                                                    <td> 
                                                        <div class="form-group">
                                                                <input style='width:70px;' class="form-control" type="number" name="jarumInput" id="jarumInput">
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

            console.log(purchaseId)
            
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
                    var diameter ="<option value=''>Pilih diameter</option>";
                    for(var i = 0;i < data.diameter.length;i++){
                        diameter += "<option value="+data.diameter[i]+">"+data.diameter[i]+"</option>";
                    }
                    $('#diameter').html(diameter);
                    console.log(data);
                }
            })
        });

        $(document).on("change", ".diameter", function(){
            var purchaseId = $('#purchaseId').val();
            var materialId = $('#materialId').val();
            var jenisId = $('#jenisId').val();
            var diameter = $('#diameter').val();
            var _token = $('#_token').val();

            
            $.ajax({
                type: "get",
                url: '{{ url('gudangInspeksi/proses/getDetailMaterial') }}/'+purchaseId+'/'+materialId+'/'+diameter+'/'+null,
                
                success: function(response){
                    var data = JSON.parse(response)
                    var gramasi ="<option value=''>Pilih gramasi</option>";
                    for(var i = 0;i < data.length;i++){
                        gramasi += "<option value="+data[i]+">"+data[i]+"</option>";
                    }
                    $('#gramasi').html(gramasi);
                    console.log(data);
                }
            })
        });

        $(document).on("change", ".gramasi", function(){
            var purchaseId = $('#purchaseId').val();
            var materialId = $('#materialId').val();
            var jenisId = $('#jenisId').val();
            var diameter = $('#diameter').val();
            var gramasi = $('#gramasi').val();
            var _token = $('#_token').val();

            
            $.ajax({
                type: "get",
                url: '{{ url('gudangInspeksi/proses/getDetailMaterial') }}/'+purchaseId+'/'+materialId+'/'+diameter+'/'+gramasi,
                
                success: function(response){
                    var data = JSON.parse(response)
                    $('#gdDetailMaterialId').val(data.gdDetailMaterialId);
                    $('#jumlah').val(data.jumlah);
                }
            })
        });
        
        $(document).ready( function () {             
            $(document).on("click", "button.TData", function(e){
                e.preventDefault(); 

                var gdDetailMaterialId  = $('#gdDetailMaterialId').val();
                var rollInput           = $('#rollInput').val();
                var beratInput          = $('#beratInput').val();
                var panjangInput        = $('#panjangInput').val();
                var lubangInput         = $('#lubangInput').val() != ""?$('#lubangInput').val():0;
                var plexInput           = $('#plexInput').val() != ""?$('#plexInput').val():0;
                var belangInput         = $('#belangInput').val() != ""?$('#belangInput').val():0;
                var tanahInput          = $('#tanahInput').val() != ""?$('#tanahInput').val():0;
                var sambungInput        = $('#sambungInput').val() != ""?$('#sambungInput').val():0;
                var jarumInput          = $('#jarumInput').val() != ""?$('#jarumInput').val():0;
                var ketInput            = $('#ketInput').val();

                var jumlah_data         = $('#jumlah_data').val();

                console.log(jarumInput)

                if(gdDetailMaterialId != "" && rollInput != "" && beratInput != "" && panjangInput != ""){
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
                        $('#lubangInput').val('');
                        $('#plexInput').val('');
                        $('#belangInput').val('');
                        $('#tanahInput').val('');
                        $('#sambungInput').val('');
                        $('#jarumInput').val('');
                        $('#ketInput').val('');
                }else{
                    alert("Data Purchase, Roll, Berat & Panjang Tidak Boleh Kosong");
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