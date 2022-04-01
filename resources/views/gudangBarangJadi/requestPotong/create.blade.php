 
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
                                <div class="row"> 
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Jenis Baju</label>
                                            <select class="form-control col-md-7 col-xs-12 gudangRequest" id="gudangRequest" name="gudangRequest" style="width: 100%; height: 38px;">
                                                <option value="">Pilih Gudang Request</option>
                                                <option value="Gudang Rajut">Gudang Rajut </option>
                                                <option value="Gudang Cuci">Gudang Cuci </option>
                                                <option value="Gudang Inspeksi">Gudang Inspeksi </option>                                                
                                            </select>                                            
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Gramasi</label>
                                            <input class="form-control gramasi" type="text" id="gramasi" name="gramasi" placeholder="Gramasi">                                            
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Request Gudang</label>
                                            <select class="form-control col-md-7 col-xs-12 gudangRequest" id="gudangRequest" name="gudangRequest" style="width: 100%; height: 38px;">
                                                <option value="">Pilih Gudang Request</option>
                                                <option value="Gudang Rajut">Gudang Rajut </option>
                                                <option value="Gudang Cuci">Gudang Cuci </option>
                                                <option value="Gudang Inspeksi">Gudang Inspeksi </option>                                                
                                            </select>                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Gramasi</label>
                                            <input class="form-control gramasi" type="text" id="gramasi" name="gramasi" placeholder="Gramasi">                                            
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Diameter</label>
                                            <input class="form-control diameter" type="text" id="diameter" name="diameter" placeholder="Diameter">                                            
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Jumlah</label>
                                            <input class="form-control qty" type="number" id="qty" name="qty" placeholder="Jumlah">                                            
                                        </div>
                                    </div>

                                    <div class="col-12 right">
                                        <div class="form-group">
                                            <button type="button" id="TBarang" class='btn btn-success btn-flat-right TBarang'>Tambah Barang</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                    <div class="col-12 right">
                                        <table id="materialPO" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                            <thead>
                                                <th class="textAlign">Bahan Baku</th>
                                                <th class="textAlign">Gudang Request</th>
                                                <th class="textAlign">Gramasi</th>
                                                <th class="textAlign">Diameter</th>
                                                <th class="textAlign">Jumlah</th>
                                                <th class="textAlign">Action</th>
                                            </thead>
                                            <tbody class="data">

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
        $('#materialId').select2({
            theme: 'bootstrap4'
        });
        $('#gudangRequest').select2({
            theme: 'bootstrap4'
        });

        $(document).ready( function () {
            $(document).on("click", "button.TBarang", function(e){
                e.preventDefault();

                var materialId      = $('#materialId').val();
                var nama_material   = $('#materialId').find('option:selected').text();
                var gudangRequest   = $('#gudangRequest').val();
                var nama_gudang     = $('#gudangRequest').find('option:selected').text();
                var gramasi         = $('#gramasi').val();
                var diameter        = $('#diameter').val();
                var qty             = $('#qty').val();

                var jumlah_data = $('#jumlah_data').val();

                if((nama_material != "Pilih Bahan Baku" || materialId != "") && (nama_gudang != "Pilih Gudang Request" || gudangRequest != "") && gramasi != "" && diameter != "" && qty != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                        table += "<td>"+nama_material+"<input type='hidden' name='materialId[]' value='"+materialId+"' id='materialId_"+jumlah_data+"'></td>";
                        table += "<td>"+nama_gudang+"<input type='hidden' name='gudangRequest[]' value='"+gudangRequest+"' id='gudangRequest_"+jumlah_data+"'></td>";
                        table += "<td>"+gramasi+"<input type='hidden' name='gramasi[]' value='"+gramasi+"' id='gramasi_"+jumlah_data+"'></td>";
                        table += "<td>"+diameter+"<input type='hidden' name='diameter[]' value='"+diameter+"' id='diameter_"+jumlah_data+"'></td>";
                        table += "<td>"+qty+"<input type='hidden' name='qty[]' value='"+qty+"' id='qty_"+jumlah_data+"'></td>";
                        table += "<td>";
                        table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                        table += "</td>";
                        table += "</tr>";

                    $('#materialId option[value=""]').attr('selected','selected');
                    $('#materialId').val('');
                    $('#gudangRequest option[value=""]').attr('selected','selected');
                    $('#gudangRequest').val('');
                    $('#gramasi').val('');
                    $('#diameter').val('');
                    $('#qty').val('');
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