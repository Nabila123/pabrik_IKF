 
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
                    <h1>Request Keluar Gudang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Request Keluar Gudang</li>
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info">
                                        <div class="card-body">
                                            <div class="row">                                                    
                                                <div class="col-8">
                                                    <div class="form-group">
                                                        <label>Kode Purchase : {{$data->kodePurchase}} </label>                                        
                                                    </div>
                                                </div>

                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>Nama Suplier : {{$data->namaSuplier}}</label>                                            
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                        <p><b>Diameter :</b> {{$data->diameter}} </p>
                                                </div>
                                                <div class="col-4">
                                                    <p><b>Gramasi : </b>{{$data->gramasi}}</p>
                                                </div>
                                                <div class="col-4">
                                                    <p><b>Total : </b>{{$data->total}}</p>
                                                </div>

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
                                                            @foreach($dataDetail as $key=>$detail)
                                                                @php
                                                                    $key++;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{$key}}</td>
                                                                    <td>{{$detail->materialNama}}</td>
                                                                    <td>{{$detail->qty}}</td>
                                                                    <td>{{$detail->brutto}}</td>
                                                                    <td>{{$detail->netto}}</td>
                                                                    <td>{{$detail->tarra}}</td>
                                                                    <td>{{$detail->unit}}</td>
                                                                    <td>{{$detail->unitPrice}}</td>
                                                                    <td>{{$detail->amount}}</td>
                                                                    <td>{{$detail->remark}}</td>
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
                                        <a href="{{route('bahan_baku/supply')}}"" id="Simpan" style="float: right" class='btn btn-info btn-flat-right'>Kembali</a>
                                    </div>
                                </div>
                            </div>
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

        $('#kodePurchase').select2({
            theme: 'bootstrap4'
        });

    </script>
@endpush