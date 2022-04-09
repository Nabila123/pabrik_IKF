 
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
                    <h1> Tambah Data Purchase Invoice</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Purchase Invoice</li>
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
                                <input type="hidden" name="purchaseId" id="purchaseId">        
                                
                                <div class="row"> 
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Nomor PO</label>
                                            <select class="form-control col-md-7 col-xs-12 barangDatangId" id="barangDatangId" name="barangDatangId" style="width: 100%; height: 38px;" required>
                                                <option value="">Pilih Nomor PO</option>
                                                @foreach($purchaseId as $purchase)
                                                    <option value="{{$purchase->id}}">{{$purchase->purchase->kode}} ( {{ $purchase->kedatangan }} )</option>
                                                @endforeach
                                            </select>                                           
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Nama Suplier</label>
                                            <input class="form-control suplierName disabled" type="text" id="suplierName" name="suplierName" placeholder="Nama Suplier" readonly>                                            
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Total Harga</label>
                                            <input class="form-control harga" id="harga" name="harga" type="text" placeholder="harga Harga" required>                                            
                                            <input id="total" name="total" type="hidden">                                            
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Tanggal Jatuh Tempo <sup>(Optional)</sup></label>
                                        <div class="input-group date" id="DateJatuhTempo">
                                            <input type="date" id="paymentDue" name="paymentDue" class="form-control datetimepicker-input paymentDue" data-target="#DateJatuhTempo"/>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mt-3">
                                            <label>Catatan / Pesan</label>
                                            <textarea id="notePesan" name="notePesan" class="form-control notePesan disabled" rows="3" placeholder="Catatan /Pesan" readonly></textarea>
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
                                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                    <div class="col-12 right">
                                                        <table id="invoice" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 15%;" class="textAlign">Nama Barang</th>
                                                                    <th style="width: 5%;" class="textAlign">Diameter</th>
                                                                    <th style="width: 5%;" class="textAlign">Gramasi</th>
                                                                    <th style="width: 5%;" class="textAlign">Brutto</th>
                                                                    <th style="width: 5%;" class="textAlign">Netto</th>
                                                                    <th style="width: 5%;" class="textAlign">Selisih </th>
                                                                    <th style="width: 5%;" class="textAlign">Satuan</th>                                                                    
                                                                    <th style="width: 5%;" class="textAlign">Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">   
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
        $('#barangDatangId').select2({
            theme: 'bootstrap4'
        });

        $(document).on("keyup", ".harga", function(){
            var rupiah      = $('#harga').val();
            rupiah = formatRupiah(rupiah, 'Rp. ');

            $('#harga').val(rupiah);
            $('#total').val(rupiah.replace(/[^0-9]/g, ''));
        });

        $(document).on("change", ".barangDatangId", function(){
            var purchaseId = $('#barangDatangId').val();
            var _token = $('#_token').val();
            $('#invoice tbody.data').empty();
            $.ajax({
                type: "get",
                url: '{{ url('purchase/getDataInvoice') }}',
                data: {
                    'purchaseId' : purchaseId,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    console.log(data)
                    $('#purchaseId').val(data['purchase']['purchaseId']);
                    $('#suplierName').val(data['purchase']['suplierName']);
                    $('#notePesan').val(data['purchase']['catatan']);                    
                    for(var i =0;i < data['material'].length;i++){
                        var dt = "<tr  class='data_"+jumlah_data+"'>";
                            dt += "<td>"+data['material'][i].nama+"</td>";
                            dt += "<td>"+data['material'][i].diameter+"</td>";
                            dt += "<td>"+data['material'][i].gramasi+"</td>";
                            dt += "<td>"+data['material'][i].brutto+"</td>";
                            dt += "<td>"+data['material'][i].netto+"</td>";
                            dt += "<td>"+data['material'][i].tarra+"</td>";
                            dt += "<td>"+data['material'][i].satuan+"</td>";
                            dt += "<td>"+data['material'][i].harga+"</td>";
                            dt += '</tr>';
                            
                        $('#invoice tbody.data').append(dt);
                    }
                }
            })
        });

    </script>
@endpush