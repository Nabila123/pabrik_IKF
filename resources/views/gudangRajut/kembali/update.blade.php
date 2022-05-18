 
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
                    <h1> Pengambalian Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Pengembalian Barang</li>
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
                                Pengembalian <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                 
                        <div class="card-body">
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" name="gdRajutMId" id="gdRajutMId" value="{{ $gRajutKeluar->id }}">           
                                
                                <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                <div class="row mb-5">
                                    <div class="col-12">
                                        <table class="table table-bordered textAlign">
                                            <thead>
                                                <tr>
                                                    <th>Bahan Baku</th>
                                                    <th>Hasil Bahan Baku</th>
                                                    <th>Nomor PO</th>
                                                    <th>Jumlah</th>
                                                    <th>Gramasi</th>
                                                    <th>Diameter</th>
                                                    <th>Berat</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="data">
                                                <?php $i = 0; ?>
                                                @foreach ($gRajutRequestDetail as $detail)
                                                <?php $i++; ?>
                                                <input type="hidden" name="totalData" class="totalData" id="totalData" value="{{ $i }}">
                                                <input type="hidden" name="namaBahan" class="namaBahan" id="namaBahan" value="{{ $detail->material->nama }}">
                                                <input type="hidden" name="jumlah_roll_{{ $i }}" id="jumlah_roll_{{ $i }}" value="0">
                                                
                                                <input type="hidden" name="gudangId_{{ $i }}" id="gudangId_{{ $i }}" value="{{ $detail->gudangId }}">
                                                <input type="hidden" name="purchaseId_{{ $i }}" id="purchaseId_{{ $i }}" value="{{ $detail->purchaseId }}">
                                                <input type="hidden" name="purchaseKode_{{ $i }}" id="purchaseKode_{{ $i }}" value="{{ $detail->purchase->kode }}">
                
                                                    <tr class="data_{{ $i }}">
                                                        <td style="font-weight: bold">{{ $detail->material->nama }}</td>
                                                        <td><button type="button" id="THasil" materialKode={{ $i }} class="btn btn-info THasil">Tambah Hasil</button></td>
                                                        <td>{{ $detail->purchase->kode }}</td>
                                                        <td>{{ $detail->qty }} <sub>Kg</sub></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <?php $no = 1; ?>
                                                    @foreach ($detail->rajutKeluar as $keluar)
                                                        <tr>
                                                            <td> Roll {{ $no++ }} </td>
                                                            <td>{{ $keluar->material->nama }}</td>
                                                            <td>{{ $keluar->purchase->kode }}</td>
                                                            <td>{{ $keluar->qty }} <sub>Roll</sub></td>
                                                            <td>{{ $keluar->gramasi }}</td>
                                                            <td>{{ $keluar->diameter }}</td>
                                                            <td>{{ $keluar->berat }}</td>
                                                            <td align="center">
                                                                <a href="{{ route('GRajut.kembali.update.delete', [$gRajutKeluar->id, $keluar->id]) }}" class="btn btn-sm btn-block btn-danger" style="width:40px;"><span class="fa fa-trash"></span></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Ubah Data</button>
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
        $('#material').select2({
            theme: 'bootstrap4'
        });
        $(document).ready( function () {
            $(document).on("click", "button.THasil", function(){
                var bahan = $('#namaBahan').val();
                var data = $('#jumlah_data').val();
                var materialId = $(this).attr('materialKode');
                var jumlah_roll = $('#jumlah_roll_'+materialId).val();
                jumlah_roll++;
                data ++;

                var gudangId = $('#gudangId_'+materialId).val();
                var purchaseId = $('#purchaseId_'+materialId).val();
                var purchaseKode = $('#purchaseKode_'+materialId).val();


                console.log("jumlah Data "+ data)
                console.log("material Id "+ materialId)
                console.log("jumlah Roll "+ jumlah_roll)

                dt = '<tr id="'+data+'">';
                dt += "<td> <b>New Roll</b> "+jumlah_roll+" <input type='hidden' name='gudangId_"+materialId+"' value='"+gudangId+"' > </td>";
                dt += "<td>  Grey / Kain Grey <input type='hidden' name='materialId' value='2' > <input type='hidden' name='jenisId' value='2' > </td>";
                dt += "<td align='center'>"+purchaseKode+"<input type='hidden' name='purchaseId_"+materialId+"' value='"+purchaseId+"' > </td>";
                dt += "<td align='center'><input type='text' required style='width:60px;' class='form-control gramasi' name='qty_"+materialId+"[]' value=''> </td>";
                dt += "<td align='center'><input type='text' required style='width:60px;' class='form-control gramasi' name='gramasi_"+materialId+"[]' value=''> </td>";
                dt += "<td align='center'><input type='text' required style='width:60px;' class='form-control diameter' name='diameter_"+materialId+"[]' value=''> </td>";
                dt += "<td align='center'><input type='text' required style='width:80px;' class='form-control berat' name='berat_"+materialId+"[]' value='' placeholder='KG'> </td>";
                dt += "<td align='center'><a class='btn btn-sm btn-block btn-danger del' materialId='"+materialId+"' idsub='"+data+"' style='width:40px;'><span class='fa fa-trash'></span></a> </td>";
                dt += '</tr>';

                $('tr.data_'+materialId).after(dt);
                $('#jumlah_roll_'+materialId).val(jumlah_roll);
                $('#jumlah_data').val(data);
            });

            $(document).on("click", "a.del", function(e){
                e.preventDefault();
                var sub = $(this).attr('idsub');
                var jumlah_data = $('#jumlah_data').val();

                console.log("Sub "+ sub)
                console.log("jumlah_data "+ jumlah_data)
                
                jumlah_data--;
                $('#jumlah_data').val(jumlah_data);
                $('#'+sub+'').remove();
            });
        });
    
    </script>
@endpush