 
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
                    <h1>Supply Bahan Baku</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Supply Bahan Baku</li>
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
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data" action="{{ route('bahan_baku.supply.update',['id'=>$data->id]) }}">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">   
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <div class="row">                                                    
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Kode Purchase</label>
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
                                                            <input type="text" class="form-control total" required id="total" name="total"placeholder="Total" value="{{$data->total}}"readonly /> 
                                                        </div>
                                                    </div>

                                                    @foreach($barangDatang as $value)
                                                        <p>Waktu Kedatangan : {{date('j F Y H:i:s', strtotime($value->created_at))}}</p>
                                                        <div class="col-12 right">
                                                            <table id="materialPO" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="textAlign" style="width: 7%;">No</th>
                                                                        <th class="textAlign">Nama Barang</th>
                                                                        <th class="textAlign">Jumlah Permintaan</th>
                                                                        <th class="textAlign">Jumlah Datang</th>
                                                                        <th class="textAlign">Satuan </th>
                                                                        <th class="textAlign">Diameter </th>
                                                                        <th class="textAlign">Gramasi </th>
                                                                        <th class="textAlign">Bruto </th>
                                                                        <th class="textAlign">Netto </th>
                                                                        <th class="textAlign">Tarra</th>
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
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->material->nama}}
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->qtyPermintaan}}
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->jumlah_datang}}
                                                                            </td>
                                                                            <td>
                                                                                {{$detail->material->satuan}}
                                                                            </td>
                                                                            @if($detail->materialId == 1)
                                                                                 @foreach($detail->detailMaterial as $key2=>$detailMaterial)
                                                                                    <td>
                                                                                        {{$detailMaterial->diameter}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->gramasi}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->brutto}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->netto}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->tarra}}
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
                                                                                @foreach($detail->detailMaterial as $detailMaterial)
                                                                                <tr>
                                                                                    <td colspan="5"></td>
                                                                                    <td>
                                                                                        {{$detailMaterial->diameter}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->gramasi}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->brutto}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->netto}}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$detailMaterial->tarra}}
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

    </script>
@endpush