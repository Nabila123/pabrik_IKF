 
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
                    <h1> Proses Potong Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Proses Potong Barang</li>
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
                                <input type="hidden" name="materialId" id="materialId" value="{{ $material['materialId'] }}">        
                                <input type="hidden" name="jenisId" id="jenisId" value="{{ $material['jenisId'] }}">        
                                
                                <div class="row mb-5">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Nomor PO </label>
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
                                            {{--  <input type="text" id="namaOperator" name="namaOperator" value="{{ \Auth::user()->nama }}" class="form-control namaOperator disabled" readonly>  --}}
                                            <select class="form-control col-md-7 col-xs-12 pegawaiId" id="pegawaiId" name="pegawaiId" style="width: 100%; height: 38px;" required>
                                                <option> Pilih Satu </option>
                                                    @foreach ($pegawai as $val)
                                                        <option value="{{ $val->id }}">{{ $val->nama }}</option>
                                                    @endforeach   
                                            </select>
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
                                            <input type="text" id="jumlah" name="jumlah" class="form-control jumlah" required>
                                        </div>
                                    </div>                                 
                                    
                                </div>
                                <input type="hidden" name="gdPotongKId" class="gdPotongKId" id="gdPotongKId">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-responsive dataTables_scrollBody textAlign">
                                            <thead>
                                                <tr>
                                                    <th colspan="2" style="vertical-align: middle;">Kepala Kain</th>
                                                    <th colspan="3" style="vertical-align: middle;">Hasil Potong</th>
                                                    <th colspan="3" style="vertical-align: middle;">Total Potong</th>
                                                    <th colspan="10" style="vertical-align: middle;">Kain Aval (Kg)</th>
                                                    <th rowspan="2" style="vertical-align: middle;">SKB (Kg)</th>
                                                    <th rowspan="2" style="vertical-align: middle;">BS (Pcs)</th>
                                                </tr>
                                                <tr>
                                                    <th style="vertical-align: middle;">Jml Potong</th>
                                                    <th style="vertical-align: middle;">Berat</th>
                                                    <th style="vertical-align: middle;">Berat Roll</th>
                                                    <th style="vertical-align: middle;">Jns Baju</th>
                                                    <th style="vertical-align: middle;">Size</th>
                                                    <th style="vertical-align: middle;">Dz</th>
                                                    <th style="vertical-align: middle;">Sisa (Pcs)</th>
                                                    <th style="vertical-align: middle;">Kg</th>
                                                    
                                                    <th style="vertical-align: middle;">Kecil</th>
                                                    <th style="vertical-align: middle;">Ketek</th>
                                                    <th style="vertical-align: middle;">Ketek Pot.</th>
                                                    <th style="vertical-align: middle;">Sumbu</th>
                                                    <th style="vertical-align: middle;">Bunder</th>
                                                    <th style="vertical-align: middle;">T. Kecil</th>
                                                    <th style="vertical-align: middle;">T. Besar</th>
                                                    <th style="vertical-align: middle;">Tangan</th>
                                                    <th style="vertical-align: middle;">KK. Putih</th>
                                                    <th style="vertical-align: middle;">KK. Belang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> <input class="form-control"  style='width:90px;' type="text" name="jmlPotong" id="jmlPotong"> </td>
                                                    <td> <input class="form-control"  style='width:70px;' type="text" name="beratPotong" id="beratPotong"> </td>
                                                    <td> <input class="form-control"  style='width:70px;' type="text" name="beratRoll" id="beratRoll"> </td>
                                                    <td> 
                                                        <select class="form-control" name="jnsBaju" id="jnsBaju" style="width: 150px">
                                                            <option> Pilih Jenis Baju</option>   
                                                            <optgroup label="Jupiter"></optgroup> 
                                                            <option value="Blong-Jupiter"> Blong Jupiter (BY) </option>    
                                                            <option value="Singlet-Jupiter"> Singlet Jupiter (SY) </option>    
                                                            <option value="Blong-Tempat-Kancing"> Blong Tempat Kancing Jupiter (BTK) </option> 
                                                            <option value="Blong-Tanpa-Lengan"> Blong Tanpa Lengan Jupiter (BTL) </option> 
                                                            
                                                            <optgroup label="Daun Jati"></optgroup>
                                                            <option value="Singlet-DJ"> Singlet DJ </option>    
                                                            <option value="Blong-TK-DJ"> Blong TK DJ </option>    
                                                            <option value="Singlet-Haji-DJ"> Singlet Haji DJ </option>   
                                                            <option value="Blong-Haji-DJ"> Blong Haji DJ </option>   
                                                        </select>    
                                                    </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="size" id="size"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="totalDZ" id="totalDZ"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="totalPcs" id="totalPcs"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="totalKG" id="totalKG"> </td>
                                                    
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="kecil" id="kecil"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="ketek" id="ketek"> </td>
                                                    <td> <input style='width:90px;' class="form-control" type="number" name="ketekPot" id="ketekPot"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="sumbu" id="sumbu"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="bunder" id="bunder"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="tKecil" id="tKecil"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="tBesar" id="tBesar"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="tangan" id="tangan"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="kPutih" id="kPutih"> </td>
                                                    <td> <input style='width:90px;' class="form-control" type="number" name="kBelang" id="kBelang"> </td>

                                                    <td> <input style='width:70px;' class="form-control" type="number" name="skb" id="skb"> </td>
                                                    <td> <input style='width:70px;' class="form-control" type="number" name="bs" id="bs"> </td>
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
                                        <div class="table-responsive dataTables_scrollBody">
                                            <table id="PotongData" class="table table-bordered textAlign PotongData">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" style="vertical-align: middle;">Kepala Kain</th>
                                                        <th colspan="3" style="vertical-align: middle;">Hasil Potong</th>
                                                        <th colspan="3" style="vertical-align: middle;">Total Potong</th>
                                                        <th colspan="10" style="vertical-align: middle;">Kain Aval</th>
                                                        <th rowspan="2" style="vertical-align: middle;">SKB</th>
                                                        <th rowspan="2" style="vertical-align: middle;">BS</th>
                                                        <th rowspan="2" style="vertical-align: middle;">Action</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Jml Potong</th>
                                                        <th>Berat Potong</th>
                                                        <th>Berat Roll</th>
                                                        <th>Jns Baju</th>
                                                        <th>Size</th>
                                                        <th>Dz</th>
                                                        <th>Sisa (Pcs)</th>
                                                        <th>Kg</th>
                                                        
                                                        <th>Kecil</th>
                                                        <th>Ketek</th>
                                                        <th>Ketek Pot.</th>
                                                        <th>Sumbu</th>
                                                        <th>Bunder</th>
                                                        <th>T. Kecil</th>
                                                        <th>T. Besar</th>
                                                        <th>Tangan</th>
                                                        <th>KK. Putih</th>
                                                        <th>KK. Belang</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="data"> </tbody>
                                            </table>
                                        </div>
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
        
        $('#pegawaiId').select2({
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
                url: '{{ url('GPotong/getData') }}',
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
                url: '{{ url('GPotong/proses/getDetailMaterial') }}/'+purchaseId+'/'+materialId+'/'+diameter+'/'+null,
                
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
                url: '{{ url('GPotong/proses/getDetailMaterial') }}/'+purchaseId+'/'+materialId+'/'+diameter+'/'+gramasi,
                
                success: function(response){
                    var data = JSON.parse(response)
                    $('#gdPotongKId').val(data.gdPotongKId);
                    $('#jumlah').val(data.jumlah);
                }
            })
        });
        
        $(document).ready( function () {             
            $(document).on("click", "button.TData", function(e){
                e.preventDefault(); 

                var gdPotongKId     = $('#gdPotongKId').val();
                var jmlPotong       = $('#jmlPotong').val();
                var beratPotong     = $('#beratPotong').val();
                var beratRoll       = $('#beratRoll').val();
                var jnsBaju         = $('#jnsBaju').val();
                var jnsBajuName     = $('#jnsBaju').find('option:selected').text();
                var size            = $('#size').val() != ""?$('#size').val():0;
                var totalDZ         = $('#totalDZ').val() != ""?$('#totalDZ').val():0;
                var totalPcs        = $('#totalPcs').val() != ""?$('#totalPcs').val():0;
                var totalKG         = $('#totalKG').val() != ""?$('#totalKG').val():0;

                var kecil           = $('#kecil').val() != ""?$('#kecil').val():0;
                var ketek           = $('#ketek').val() != ""?$('#ketek').val():0;
                var ketekPot        = $('#ketekPot').val() != ""?$('#ketekPot').val():0;
                var sumbu           = $('#sumbu').val() != ""?$('#sumbu').val():0;
                var bunder          = $('#bunder').val() != ""?$('#bunder').val():0;
                var tKecil          = $('#tKecil').val() != ""?$('#tKecil').val():0;
                var tBesar          = $('#tBesar').val() != ""?$('#tBesar').val():0;
                var tangan          = $('#tangan').val() != ""?$('#tangan').val():0;
                var kPutih          = $('#kPutih').val() != ""?$('#kPutih').val():0;
                var kBelang         = $('#kBelang').val() != ""?$('#kBelang').val():0;

                var skb             = $('#skb').val() != ""?$('#skb').val():0;
                var bs              = $('#bs').val() != ""?$('#bs').val():0;

                var jumlah_data     = $('#jumlah_data').val();

                if(gdPotongKId != "" && jmlPotong != "" && beratPotong != "" && jnsBaju != "" && size != "" && totalDZ != "" && totalKG != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                            table += "<td>"+jmlPotong+"<input type='hidden' name='jmlPotong[]' value='"+jmlPotong+"' id='jmlPotong_"+jumlah_data+"'></td>";
                            table += "<td>"+beratPotong+"<input type='hidden' name='beratPotong[]' value='"+beratPotong+"' id='beratPotong_"+jumlah_data+"'></td>";
                            table += "<td>"+beratRoll+"<input type='hidden' name='beratRoll[]' value='"+beratRoll+"' id='beratRoll_"+jumlah_data+"'></td>";
                            table += "<td>"+jnsBajuName+"<input type='hidden' name='jnsBaju[]' value='"+jnsBaju+"' id='jnsBaju_"+jumlah_data+"'></td>";
                            table += "<td>"+size+"<input type='hidden' name='size[]' value='"+size+"' id='size_"+jumlah_data+"'></td>";
                            table += "<td>"+totalDZ+"<input type='hidden' name='totalDZ[]' value='"+totalDZ+"' id='totalDZ_"+jumlah_data+"'></td>";
                            table += "<td>"+totalPcs+"<input type='hidden' name='totalPcs[]' value='"+totalPcs+"' id='totalPcs_"+jumlah_data+"'></td>";
                            table += "<td>"+totalKG+"<input type='hidden' name='totalKG[]' value='"+totalKG+"' id='totalKG_"+jumlah_data+"'></td>";
                            
                            table += "<td>"+kecil+"<input type='hidden' name='kecil[]' value='"+kecil+"' id='kecil_"+jumlah_data+"'></td>";
                            table += "<td>"+ketek+"<input type='hidden' name='ketek[]' value='"+ketek+"' id='ketek_"+jumlah_data+"'></td>";
                            table += "<td>"+ketekPot+"<input type='hidden' name='ketekPot[]' value='"+ketekPot+"' id='ketekPot_"+jumlah_data+"'></td>";
                            table += "<td>"+sumbu+"<input type='hidden' name='sumbu[]' value='"+sumbu+"' id='sumbu_"+jumlah_data+"'></td>";
                            table += "<td>"+bunder+"<input type='hidden' name='bunder[]' value='"+bunder+"' id='bunder_"+jumlah_data+"'></td>";
                            table += "<td>"+tKecil+"<input type='hidden' name='tKecil[]' value='"+tKecil+"' id='tKecil_"+jumlah_data+"'></td>";
                            table += "<td>"+tBesar+"<input type='hidden' name='tBesar[]' value='"+tBesar+"' id='tBesar_"+jumlah_data+"'></td>";
                            table += "<td>"+tangan+"<input type='hidden' name='tangan[]' value='"+tangan+"' id='tangan_"+jumlah_data+"'></td>";
                            table += "<td>"+kPutih+"<input type='hidden' name='kPutih[]' value='"+kPutih+"' id='kPutih_"+jumlah_data+"'></td>";
                            table += "<td>"+kBelang+"<input type='hidden' name='kBelang[]' value='"+kBelang+"' id='kBelang_"+jumlah_data+"'></td>";
                            
                            table += "<td>"+skb+"<input type='hidden' name='skb[]' value='"+skb+"' id='skb_"+jumlah_data+"'></td>";
                            table += "<td>"+bs+"<input type='hidden' name='bs[]' value='"+bs+"' id='bs_"+jumlah_data+"'></td>";

                            table += "<td>";
                            table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                            table += "</td>";
                        table += "</tr>";

                        $('#jmlPotong').val('');
                        $('#beratPotong').val('');
                        $('#jnsBaju').val('');
                        $('#size').val('');
                        $('#totalDZ').val('');
                        $('#totalPcs').val('');
                        $('#totalKG').val('');

                        $('#kecil').val('');
                        $('#ketek').val('');
                        $('#ketekPot').val('');
                        $('#sumbu').val('');
                        $('#bunder').val('');
                        $('#tKecil').val('');
                        $('#tBesar').val('');
                        $('#tangan').val('');
                        $('#kPutih').val('');
                        $('#kBelang').val('');

                        $('#skb').val('');
                        $('#bs').val('');
                }else{
                    alert("Data Purchase, Kepala Kain, Hasil Potong & Total Potong Tidak Boleh Kosong");
                }

                $('#PotongData tbody.data').append(table);
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