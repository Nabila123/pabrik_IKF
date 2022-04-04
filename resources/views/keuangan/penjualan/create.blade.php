 
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
                    <h1> Invoice Pembayaran Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Invoice Pembayaran Barang</li>
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
                                
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Kode Transaksi </label>
                                            <select class="form-control kodeTransaksi" id="kodeTransaksi" name="kodeTransaksi" style="width: 100%; height: 38px;" >
                                                <option> Pilih Satu Kode Transaksi</option>
                                                @foreach ($kodeTransaksi as $detail)
                                                    <option value="{{ $detail->kodeTransaksi }}"> {{ $detail->kodeTransaksi }}</option>
                                                @endforeach
                                            </select>     
                                        </div>
                                    </div>                                    
                                    
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Nama Customer (Pelanggan) <sup>Optional</sup></label>
                                            <input type="text" id="customer" name="customer" class="form-control customer disable" readonly>                                                                                 
                                        </div>
                                    </div> 
                                    
                                    <div class="col-4">
                                        <label>Total Bayar</label>
                                        <div class="input-group">                                            
                                            <input type="text" id="totalBayar" name="totalBayar" class="form-control totalBayar" required>
                                            <input type="hidden" name="pembayaran" id="pembayaran">                                                                             
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
                                                    <div class="col-12">
                                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                        <table id="penjualanData" class="table table-bordered dataTables_scrollBody textAlign JahitData">
                                                            <thead>
                                                                <tr>
                                                                    <th style="vertical-align: middle;">Kode Barang</th>
                                                                    <th style="vertical-align: middle;">Jenis Baju</th>
                                                                    <th style="vertical-align: middle;">Ukuran Baju</th>
                                                                    <th style="vertical-align: middle;">Jumlah Baju</th>
                                                                    <th style="vertical-align: middle;">Harga Baju</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">
                                                                
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="4" style="text-align: right">Total Harga</th>
                                                                    <th>
                                                                        <div class="totalHarga" id="totalHarga">
                                                                            
                                                                        </div>
                                                                    </th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>                                         
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
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
        $('#kodeTransaksi').select2({
            theme: 'bootstrap4'
        });

        $(document).on("keyup", ".totalBayar", function(){
            var rupiah      = $('#totalBayar').val();
            rupiah = formatRupiah(rupiah, 'Rp. ');

            $('#totalBayar').val(rupiah);
            $('#pembayaran').val(rupiah.replace(/[^0-9]/g, ''));
        });


        $(document).on("change", ".kodeTransaksi", function(){
            var kodeTransaksi = $('#kodeTransaksi').val();
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url('Keuangan/getPenjualan') }}',
                data: {
                    'kodeTransaksi' : kodeTransaksi,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response) 
                    
                    for(var i = 0;i < data.detail.length;i++){
                        var dataPenjualan = "<tr>";
                                dataPenjualan += "<td>"+data['detail'][i]['kodeProduct']+"</td>";
                                dataPenjualan += "<td>"+data['detail'][i]['jenisBaju']+"</td>";
                                dataPenjualan += "<td>"+data['detail'][i]['ukuranBaju']+"</td>";
                                dataPenjualan += "<td>"+data['detail'][i]['qty']+"</td>";
                                dataPenjualan += "<td>"+formatRupiah(data['detail'][i]['harga'], 'Rp. ')+"</td>";
                            dataPenjualan += "<tr>";

                        $('#penjualanData tbody.data').append(dataPenjualan);
                    }
                    $('#totalHarga').html(formatRupiah(data.total, 'Rp. '));
                    console.log(data)
                }
            })
        });             
    </script>
@endpush