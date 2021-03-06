 
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
                    <h1>Gudang Bahan Baku</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Bahan Baku</li>
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
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Nomor PO</label>
                                                            <input type="text" class="form-control col-md-7 col-xs-12 kodePurchase" id="kodePurchase" name="kodePurchase" value="{{$data->kodePurchase}}" readonly />
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Nama Suplier</label>
                                                            <input class="form-control suplier" required id="suplier" name="namaSuplier" type="text" placeholder="Nama Suplier" value="{{$data->namaSuplier}}" readonly >                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Diameter</label>
                                                            <input type="text" class="form-control diameter" required id="diameter" name="diameter"placeholder="Diameter" value="{{$data->diameter}}" /> 
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Gramasi</label>
                                                            <input type="text" class="form-control gramasi" required id="gramasi" name="gramasi"placeholder="Gramasi" value="{{$data->gramasi}}" /> 
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <label>Total</label>
                                                            <input type="text" class="form-control total" required id="total" name="total"placeholder="Total" value="{{$data->total}}" /> 
                                                        </div>
                                                    </div>


                                                    <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="{{count($dataDetail)}}">
                                                    <div class="col-12 right">
                                                        <table id="materialPO" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th class="textAlign" style="width: 7%;">No</th>
                                                                    <th class="textAlign">Nama Barang</th>
                                                                    <th class="textAlign">Jumlah </th>
                                                                    <th class="textAlign">Bruto </th>
                                                                    <th class="textAlign">Netto </th>
                                                                    <th class="textAlign">Tarra</th>
                                                                    <th class="textAlign">Satuan </th>
                                                                    <th class="textAlign">Harga Satuan</th>
                                                                    <th class="textAlign">Amount</th>
                                                                    <th class="textAlign">Remark</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">
                                                                @php $i=1; @endphp
                                                                @foreach($dataDetail as $key=>$detail)
                                                                    <tr>
                                                                        <td>
                                                                            {{$i}}
                                                                            <input type="hidden" name="detailId[]" value="{{$detail->id}}">
                                                                        </td>
                                                                        <td>
                                                                            {{$detail->materialNama}}
                                                                            <input type="hidden" name="materialId[]" value="{{$detail->materialId}}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="qty[]" value="{{$detail->qty}}"  style="width: 70px;">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="brutto[]" value="{{$detail->brutto}}" style="width: 70px;">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="netto[]" value="{{$detail->netto}}" style="width: 70px;" >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="tarra[]" value="{{$detail->tarra}}" style="width: 70px;" >
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="unit[]" value="{{$detail->unit}}" style="width: 70px;">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="unitPrice[]" value="{{$detail->unitPrice}}" style="width: 90px;">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="amount[]" value="{{$detail->amount}}" style="width: 90px;">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" name="remark[]" value="{{$detail->remark}}" style="width: 70px;">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
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
        $(document).ready( function () {
            $('#example2').DataTable();
        });

    </script>
@endpush