 
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
                    <h1>Tambah Gudang Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Gudang Request</li>
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
                                Permintaan Gudang <label style="font-size: 15px;">  {{ date('d F y') }} </label>
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
                                <input type="hidden" name="requestId" id="requestId" value="{{ $request->id }}">   

                                <div class="row"> 
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Jenis Baju</label>
                                            <select class="form-control jenisBaju" name="jenisBaju" id="jenisBaju" style="width: 100%; height: 38px;">
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
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Ukuran Baju</label>
                                            <input class="form-control ukuranBaju" type="text" id="ukuranBaju" name="ukuranBaju" placeholder="Ukuran Baju">                                            
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group center">
                                            <label>Jumlah Baju</label>
                                            <table width="100%">
                                                <tr>
                                                    <td>
                                                        <input class="form-control jumlahBaju" type="text" id="jumlahBaju" name="jumlahBaju" placeholder="Jumlah Baju (Pcs)">                                            
                                                    </td>
                                                    <td>
                                                        <input class="form-control jumlahDz" type="text" id="jumlahDz" name="jumlahDz" placeholder="Jumlah Baju (Dz)">                                            
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-5">
                                        <div class="form-group">
                                            <button type="button" id="TBarang" class='btn btn-success btn-flat-right TBarang' style="float: left">Tambah Data</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                    <div class="col-12 right">
                                        <table id="materialPO" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                            <thead>
                                                <th class="textAlign">Jenis Baju</th>
                                                <th class="textAlign">ukuran Baju</th>
                                                <th class="textAlign">Jumlah</th>
                                                <th class="textAlign">Action</th>
                                            </thead>
                                            <tbody class="data textAlign">
                                                @foreach ($potongRequestDetail as $detail)
                                                    <tr>
                                                        <td>{{ $detail->jenisBaju }}</td>
                                                        <td>{{ $detail->ukuranBaju }}</td>
                                                        <td>{{ $detail->qty }}</td>
                                                        <td><a href="{{ route('GBarangJadi.requestPotong.update.delete', [$request->id, $detail->id]) }}" class='btn btn-sm btn-block btn-danger' style='width:40px;'><span class='fa fa-trash'></span></a></td>
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
        $('#jenisBaju').select2({
            theme: 'bootstrap4'
        });

        $(document).on("keyup", ".jumlahBaju", function(){
            var jumlahBaju = $('#jumlahBaju').val();           
            var jumlahDz = (jumlahBaju/12);
        
            $('#jumlahDz').val(jumlahDz.toFixed(1));    
                
        });

        $(document).on("keyup", ".jumlahDz", function(){
            var jumlahDz = $('#jumlahDz').val();
            var jumlahBaju = (jumlahDz*12);
                            
            $('#jumlahBaju').val(jumlahBaju);    
                
        });

        $(document).ready( function () {
            $(document).on("click", "button.TBarang", function(e){
                e.preventDefault();

                var jenisBaju      = $('#jenisBaju').val();
                var ukuranBaju     = $('#ukuranBaju').val();
                var jumlahBaju     = $('#jumlahBaju').val();

                var jumlah_data = $('#jumlah_data').val();

                if((jenisBaju != "") && (ukuranBaju != "") && jumlahBaju != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                        table += "<td>"+jenisBaju+"<input type='hidden' name='jenisBaju[]' value='"+jenisBaju+"' id='jenisBaju_"+jumlah_data+"'></td>";
                        table += "<td>"+ukuranBaju+"<input type='hidden' name='ukuranBaju[]' value='"+ukuranBaju+"' id='ukuranBaju_"+jumlah_data+"'></td>";
                        table += "<td>"+jumlahBaju+"<input type='hidden' name='jumlahBaju[]' value='"+jumlahBaju+"' id='jumlahBaju_"+jumlah_data+"'></td>";
                        table += "<td>";
                        table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                        table += "</td>";
                        table += "</tr>";

                    $('#jenisBaju option[value=""]').attr('selected','selected');
                    $('#jenisBaju').val('');
                    $('#ukuranBaju').val('');
                    $('#jumlahBaju').val('');
                    $('#jumlahDz').val('');
                }else{
                    alert("Silahkan Isi Semua Data");
                }

                $('#materialPO tbody.data').append(table);
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