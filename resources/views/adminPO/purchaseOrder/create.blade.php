 
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
                    <h1> Tambah Data Purchase Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Purchase Order</li>
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
                        <div class="card-body">
                            @if(isset($message))
                                <div class="alert alert-danger not-found">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <strong>Perhatian!</strong> {{$message}}
                                </div>
                            @endif
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                @if (isset($purchase->id))
                                    <input type="hidden" name="purchaseId" id="purchaseId" value="{{ $purchase->id }}">       
                                    <input type="hidden" name="jenisPurchase" id="jenisPurchase" value="{{ $jenisPurchase }}">       
                                    <input type="hidden" name="total" id="total" value="{{ $purchase->total }}">       
                                @endif
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Nomor PO</label>
                                            <input class="form-control purchaseKode" id="purchaseKode" name="purchaseKode"  type="text" placeholder="Nomor PO" required>                                            
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Tanggal Pengajuan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" id="pengajuanDate" name="pengajuanDate" class="form-control disable pengajuanDate" value="{{ date('d F Y') }}" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask readonly>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Nama Suplier</label>
                                            <input class="form-control suplierName" id="suplierName" name="suplierName"  type="text" placeholder="Nama Suplier" required>                                            
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Tanggal Pengiriman <sup>(Optional)</sup></label> 
                                        <div class="input-group date" id="DatePengiriman">
                                            @if (isset($jenisPurchase))
                                                <?php
                                                    if($purchase->waktu == date('Y-m-d', strtotime("1970-01-01"))){
                                                        $purchase->waktu = null;
                                                    }
                                                ?>
                                                <input type="date" id="pengirimanDate" value="{{ $purchase->waktu }}" name="pengirimanDate" class="form-control datetimepicker-input pengirimanDate" data-target="#DatePengiriman"/>
                                            @else
                                            <input type="date" id="pengirimanDate" name="pengirimanDate" class="form-control datetimepicker-input pengirimanDate" data-target="#DatePengiriman"/>
                                            @endif
                                        </div>                                        
                                    </div>
                                    <div class="col-6">
                                        <label>Tanggal Jatuh Tempo <sup>(Optional)</sup></label>
                                        <div class="input-group date" id="DateJatuhTempo">
                                            @if (isset($jenisPurchase))
                                                <?php
                                                    if($purchase->waktuPayment == date('Y-m-d', strtotime("1970-01-01"))){
                                                        $purchase->waktuPayment = null;
                                                    }
                                                ?>
                                                <input type="date" value="{{ $purchase->waktuPayment }}" id="jatuhTempoDate" name="jatuhTempoDate" class="form-control datetimepicker-input jatuhTempoDate" data-target="#DateJatuhTempo"/>
                                            @else
                                                <input type="date" id="jatuhTempoDate" name="jatuhTempoDate" class="form-control datetimepicker-input jatuhTempoDate" data-target="#DateJatuhTempo"/>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mt-3">
                                            <label>Catatan / Pesan <sup>(Optional)</sup></label>
                                            <textarea id="notePesan" name="notePesan" class="form-control notePesan" rows="3" placeholder="Enter ...">@if (isset($jenisPurchase)){{ $purchase->note }}@endif</textarea>
                                          </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-header">
                                              <h3 class="card-title">Data Pesanan Material</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">                                                    
                                                    @if (!isset($jenisPurchase))
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Nama Barang</label>
                                                                <select class="form-control col-md-7 col-xs-12 material" id="material" name="material" style="width: 100%; height: 38px;">
                                                                    <option value="">Pilih Material / Bahan</option>
                                                                    @foreach($materials as $material)
                                                                        <option value="{{$material->id}}">{{$material->nama}}</option>
                                                                    @endforeach
                                                                </select>                                           
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <label>Jumlah</label>
                                                                <input class="form-control jumlah" type="number" id="jumlah" name="jumlah" placeholder="Jumlah Barang">                                            
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <label>Satuan</label>
                                                                <input class="form-control disable satuan" id="satuan" name="satuan" type="text" placeholder="Satuan" readonly>                                            
                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Harga</label>
                                                                <input class="form-control hargaBarang" id="hargaBarang" name="hargaBarang" type="text" placeholder="Harga">                                            
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label>Total Harga</label>
                                                                <input class="form-control disable total" id="total" name="total" type="text" placeholder="Total Harga" readonly>                                            
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Catatan / Pesan</label>
                                                                <textarea class="form-control note" id="note" name="note" rows="3" placeholder="Enter ..."></textarea>                                          
                                                            </div>
                                                        </div>

                                                        <div class="col-12 right">
                                                            <div class="form-group">
                                                                <button type="button" id="TBarang" class='btn btn-success btn-flat-right TBarang'>Tambah Barang</button>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                    <div class="col-12 right">
                                                        <table id="materialPO" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="textAlign">Nama Barang</th>
                                                                    <th class="textAlign">Jumlah </th>
                                                                    <th class="textAlign">Satuan</th>
                                                                    <th class="textAlign">Harga</th>
                                                                    <th class="textAlign">Total Harga</th>
                                                                    <th class="textAlign">Catatan / Pesan</th>
                                                                    @if (!isset($jenisPurchase))
                                                                        <th class="textAlign">Action</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">
                                                                @if (isset($jenisPurchase))
                                                                    @foreach ($purchaseDetails as $detail)
                                                                        <tr>
                                                                            <td>{{ $detail->material->nama }}</td>
                                                                            <td>
                                                                                {{ $detail->qty }}
                                                                                <input type="hidden" id="jumlah{{ $detail->id }}" name="jumlah{{ $detail->id }}" value="{{ $detail->qty }}">
                                                                            </td>
                                                                            <td>{{ $detail->unit }}</td>
                                                                            <td>
                                                                                <input class="form-control hargaBarang" purchaseDetail="{{ $detail->id }}" id="hargaBarang{{ $detail->id }}" name="hargaBarang[{{ $detail->id }}]" type="text" placeholder="Harga">                                            
                                                                                <input class="form-control harga" purchaseDetail="{{ $detail->id }}" id="harga{{ $detail->id }}" name="harga[{{ $detail->id }}]" type="hidden" placeholder="Harga">                                            
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control disable total" id="total{{ $detail->id }}" name="total[{{ $detail->id }}]" type="text" placeholder="Total Harga" readonly>
                                                                                <input class="form-control disable totalHarga" id="totalHarga{{ $detail->id }}" name="totalHarga[{{ $detail->id }}]" type="hidden" placeholder="Total Harga" readonly>
                                                                            </td>
                                                                            <td>{{ $detail->remark }}</td>
                                                                        </tr>                                                                    
                                                                    @endforeach 
                                                                @endif   
                                                            </tbody>                                                                                       
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
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
        $('#material').select2({
            theme: 'bootstrap4'
        });
        
        $(document).on("keyup", ".purchaseKode", function(){            
            var purchaseKode = $('#purchaseKode').val();
            var _token = $('#_token').val();
            $.ajax({
                type: "post",
                url: '{{ url('purchase/getCheckKode') }}',
                data: {
                    'purchaseKode' : purchaseKode,
                    '_token': _token
                },
                success: function(response){
                   if(response == 1){
                        $('#purchaseKode').css({'border':'2px solid #2ecc71'});
                   }else{
                        $('#purchaseKode').css({'border':'2px solid #e74c3c'});
                   }
                }
            })
        });

        $(document).on("change", ".material", function(){
            var materialId = $('#material').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('material/getSatuan') }}',
                data: {
                    'materialId' : materialId,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    $('.satuan').val(data.satuan);
                    console.log(data.satuan);
                }
            })
        });

        $(document).on("keyup", ".hargaBarang", function(){
            var id = $(this).attr('purchaseDetail');
            var harga = $('#hargaBarang'+id).val();
            var jumlah = $('#jumlah'+id).val();

            hargaTotal = (parseInt(harga.replace(/[^,\d]/g, '')) * jumlah);
            console.log(hargaTotal, harga)

            $('#total'+id).val(formatRupiah(String(hargaTotal), 'Rp. '));
            $('#hargaBarang'+id).val(formatRupiah(harga, 'Rp. '));

            $('#totalHarga'+id).val(hargaTotal);
            $('#harga'+id).val(parseInt(harga.replace(/[^,\d]/g, '')));
        });

        $(document).ready( function () {
            $(document).on("click", "button.TBarang", function(e){
                e.preventDefault();

                var material        = $('#material').val();
                var nama_material   = $('#material').find('option:selected').text();
                var jumlah          = $('#jumlah').val();
                var satuan          = $('#satuan').val();
                var harga           = $('#harga').val();
                var totalHarga      = $('#totalHarga').val();
                var note            = $('#note').val();

                var jumlah_data = $('#jumlah_data').val();

                if((nama_material != "Pilih Material / Bahan" || material != "") && satuan != "" && jumlah != "" && harga != ""){
                    jumlah_data++;
	        	    $('#jumlah_data').val(jumlah_data);

                    var table  = "<tr  class='data_"+jumlah_data+"'>";
                        table += "<td>"+jumlah_data+"</td>";
                        table += "<td>"+nama_material+"<input type='hidden' name='material[]' value='"+material+"' id='material_"+jumlah_data+"'></td>";
                        table += "<td>"+jumlah+"<input type='hidden' name='jumlah[]' value='"+jumlah+"' id='jumlah_"+jumlah_data+"'></td>";
                        table += "<td>"+satuan+"<input type='hidden' name='satuan[]' value='"+satuan+"' id='satuan_"+jumlah_data+"'></td>";
                        table += "<td>"+harga+"<input type='hidden' name='harga[]' value='"+harga+"' id='harga_"+jumlah_data+"'></td>";
                        table += "<td>"+totalHarga+"<input type='hidden' name='totalHarga[]' value='"+totalHarga+"' id='totalHarga_"+jumlah_data+"'></td>";
                        table += "<td>"+note+"<input type='hidden' name='note[]' value='"+note+"' id='note_"+jumlah_data+"'></td>";
                        table += "<td>";
                        table += "<a class='btn btn-sm btn-block btn-danger del' idsub='"+jumlah_data+"' style='width:40px;'><span class='fa fa-trash'></span></a>";
                        table += "</td>";
                        table += "</tr>";

                    $('#material option[value=""]').attr('selected','selected');
                    $('#material').val('');
                    $('#jumlah').val('');
                    $('#satuan').val('');
                    $('#harga').val('');
                    $('#totalHarga').val('');
                    $('#note').val('');
                }else{
                    alert("Material Dan Satuan Pemakaian Tidak Boleh Kosong");
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