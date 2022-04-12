 
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
                    <h1>Supply Bahan Pembantu</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Supply Bahan Pembantu</li>
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
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data" action="{{ route('GBahanPembantu.supply.update',['id'=>$data->id]) }}">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">   
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <div class="row">                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Nomor PO</label>
                                                            <input type="text" class="form-control kodePurchase" id="kodePurchase" name="kodePurchase" value="{{$data->purchase->kode}}" readonly />
                                                            <input type="hidden" class="form-control purchaseId" id="purchaseId" name="purchaseId" value="{{$data->purchaseId}}" readonly />                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Nama Suplier</label>
                                                            <input class="form-control suplier" required id="suplier" name="namaSuplier" type="text" placeholder="Nama Suplier" value="{{$data->namaSuplier}}" readonly >                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Total</label>
                                                            <input type="text" class="form-control total" required id="total" name="total"placeholder="Total" value="{{$data->total}}" /> 
                                                        </div>
                                                    </div>

                                                    @foreach($barangDatang as $value)
                                                        <p>Waktu Kedatangan : {{date('j F Y H:i:s', strtotime($value->created_at))}}</p>
                                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="{{count($value->detail)}}">
                                                        <div class="col-12 right">
                                                            <table id="materialPO" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="textAlign" style="width: 7%;">No</th>
                                                                        <th class="textAlign">Nama Barang</th>
                                                                        <th class="textAlign">Jumlah Permintaan</th>
                                                                        <th class="textAlign">Jumlah Saat Ini</th>
                                                                        <th class="textAlign">Satuan </th>
                                                                        <th class="textAlign">Diameter </th>
                                                                        <th class="textAlign">Gramasi </th>
                                                                        <th class="textAlign">Bruto </th>
                                                                        <th class="textAlign">Netto </th>
                                                                        <th class="textAlign">Tarra</th>
                                                                        <th class="textAlign">Action</th>
                                                                        <!-- <th class="textAlign">Harga Satuan</th>
                                                                        <th class="textAlign">Amount</th>
                                                                        <th class="textAlign">Remark</th> -->
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="data textAlign">
                                                                    @php $i=1; @endphp
                                                                    @foreach($value->detail as $key=>$detail)
                                                                        <tr>
                                                                            <td>
                                                                                {{$i}}
                                                                                <input type="hidden" name="detailId[]" value="{{$detail->id}}">
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->material->nama}}
                                                                                <input type="hidden" name="materialId[{{$detail->id}}]" value="{{$detail->materialId}}">
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->qtyPermintaan}}
                                                                                <input type="hidden" name="qtyPermintaan[{{$detail->id}}]" value="{{$detail->qtyPermintaan}}"  style="width: 70px;">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" name="qtySaatIni[{{$detail->id}}]" value="{{$detail->jumlah_datang}}"  style="width: 70px;">
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->material->satuan}}
                                                                                <input type="hidden" name="unit[{{$detail->id}}]" value="{{$detail->material->satuan}}" style="width: 70px;">
                                                                            </td>
                                                                            @if($detail->materialId == 1)
                                                                                 @foreach($detail->detailMaterial as $key2=>$detailMaterial)
                                                                                    <input type="hidden" name="detailMaterialId[{{$detail->id}}][]" value="{{$detailMaterial->id}}" >
                                                                                    <td>
                                                                                        <input type="text" name="diameter[{{$detailMaterial->id}}]" value="{{$detailMaterial->diameter}}" style="width: 70px;">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="gramasi[{{$detailMaterial->id}}]" value="{{$detailMaterial->gramasi}}" style="width: 70px;">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="brutto[{{$detailMaterial->id}}]" value="{{$detailMaterial->brutto}}" style="width: 70px;">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="netto[{{$detailMaterial->id}}]" value="{{$detailMaterial->netto}}" style="width: 70px;" >
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="tarra[{{$detailMaterial->id}}]" value="{{$detailMaterial->tarra}}" style="width: 70px;" >
                                                                                    </td>
                                                                                @endforeach
                                                                                <!-- <td>
                                                                                    <input type="text" name="unitPrice[]" value="{{$detail->unitPrice}}" style="width: 90px;">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="amount[]" value="{{$detail->amount}}" style="width: 90px;">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" name="remark[]" value="{{$detail->remark}}" style="width: 70px;">
                                                                                </td> -->
                                                                            @else
                                                                                <td colspan="5">
                                                                                    Data Per Roll
                                                                                </td>
                                                                                @foreach($detail->detailMaterial as $key3=>$detailMaterial)
                                                                                <tr id="roll_{{$key3}}">
                                                                                    <input type="hidden" name="detailMaterialId[{{$detail->id}}][]" value="{{$detailMaterial->id}}" >
                                                                                    <td colspan="5"></td>
                                                                                    <td>
                                                                                        <input type="text" name="diameter[{{$detailMaterial->id}}]" value="{{$detailMaterial->diameter}}" style="width: 70px;">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="gramasi[{{$detailMaterial->id}}]" value="{{$detailMaterial->gramasi}}" style="width: 70px;">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="brutto[{{$detailMaterial->id}}]" value="{{$detailMaterial->brutto}}" style="width: 70px;">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="netto[{{$detailMaterial->id}}]" value="{{$detailMaterial->netto}}" style="width: 70px;" >
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" name="tarra[{{$detailMaterial->id}}]" value="{{$detailMaterial->tarra}}" style="width: 70px;" >
                                                                                    </td>
                                                                                    <td>
                                                                                        <a idRoll="{{$key3}}" idData="{{$detailMaterial->id}}" class="btn btn-danger delRoll"><i class="fa fa-trash"></i></a>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            @endif
                                                                        </tr>
                                                                        @php $i++; @endphp
                                                                    @endforeach
                                                                </tbody>                                                                                       
                                                            </table>
                                                        </div>
                                                    @endforeach
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
        $(document).ready( function () {
            $('#example2').DataTable();
        });
        $(document).on("click", ".delRoll", function(e){
            e.preventDefault();
            var idRoll = $(this).attr('idRoll');
            $('#roll_'+idRoll).remove();

            var idData = $(this).attr('idData');
            var _token = $('#_token').val();
            
            $.ajax({
                type: "post",
                url: '{{ url("GBahanPembantu/supply/delDetailMaterial") }}/'+idData,
                data: {'_token' : _token},
                success: function(response){
                    if(response == 1){
                         $('#roll_'+idRoll).remove();
                        alert('Data Berhasil Dihapus!');
                    }else{
                        alert('Data Gagal Dihapus!');
                    }
                }
            })
        });
    </script>
@endpush