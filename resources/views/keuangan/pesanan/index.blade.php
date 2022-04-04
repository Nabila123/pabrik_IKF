 
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
                    <h1>Purchasing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Purchasing</li>
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
                        <div class="card-header">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#OperatorLink" data-toggle="tab">Purchase Order</a></li>
                                <li class="nav-item"><a class="nav-link" href="#RekapanLink" data-toggle="tab">Purchase Invoice</a></li>
                            </ul>                            
                        </div>                   
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="OperatorLink">
                                    <table id="purchase" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="textAlign" style="vertical-align: middle;">Kode Purchase</th>
                                                <th rowspan="2" class="textAlign" style="vertical-align: middle;">Tanggal Pengajuan</th>
                                                <th colspan="3" class="textAlign" style="vertical-align: middle;">Agreement </th>
                                                <th rowspan="2" class="textAlign" style="vertical-align: middle;">Status Kedatangan</th>
                                                <th rowspan="2" class="textAlign" style="vertical-align: middle; width:11%;">action</th>
                                            </tr>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">K. Dep PO</th>
                                                <th class="textAlign" style="vertical-align: middle;">K. Div PO</th>
                                                <th class="textAlign" style="vertical-align: middle;">K. Div Fin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">
                                            @foreach ($poOrder as $order)
                                                <tr>
                                                    <td>{{ $order->kode }}</td>
                                                    <td>{{ date('d F Y', strtotime($order->tanggal)) }}</td>
                                                    <td>
                                                        {{ $order->user->nama }} <br>
                                                        <span style="color: green; font-size: 10px">Submitted At {{ date('d F Y', strtotime($order->tanggal)) }}</span>
        
                                                    </td>
                                                    <td>
                                                        @if ($order->isKaDivPO != 0)
                                                            {{ $order->roleKaDivPOUser->nama }} <br>
                                                            <span style="color: green; font-size: 10px"> Aproved At {{ date('d F Y', strtotime($order->isKaDivPOAt)) }} </span>
                                                        @else
                                                            @if (\Auth::user()->roleId == 4)
                                                                <button type="button" id="approveKaKaDivPO" purchaseid="{{ $order->id }}" class='btn btn-success approveKaKaDivPO'><i class="fas fa-check-square" style="font-size: 14px"> </i> Approve</a>
                                                            @else
                                                                <br>
                                                                <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                            @endif
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        @if ($order->isKaDivFin != 0)
                                                            {{ $order->roleKaDivFinUser->nama }} <br>
                                                            <span style="color: green; font-size: 10px"> Aproved At {{ date('d F Y', strtotime($order->isKaDivFinAt)) }} </span>
                                                        @else
                                                            @if (\Auth::user()->roleId == 6 && $order->isKaDivPO != 0)
                                                                <button type="button" id="approveKaDivFin" purchaseid="{{ $order->id }}" class='btn btn-success approveKaDivFin'><i class="fas fa-check-square" style="font-size: 14px"> </i> Approve</a>
                                                            @else
                                                                <br>
                                                                <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->barangDatang == true)
                                                            Barang Datang <br>
                                                            <span style="color: green; font-size: 10px"> At {{ date('d F Y', strtotime($order->barangDatangAt)) }} </span>
                                                        @else
                                                            <span style="color: rgb(253, 5, 5); font-size: 12px">Barang Belum Datang</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('adminPO.poOrder.detail', $order->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                        {{--  <a href="{{ route('adminPO.poOrder.update', $order->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>  --}}
                                                        @if ($order->isKaDivFin != 0)
                                                            <a href="{{ route('adminPO.poOrder.unduh', $order->id) }}" target="_blank" class='btn btn-info mt-1'><i class="fas fa-download" style="font-size: 14px"></i></a>
                                                        @else
                                                            <button type="button" class='btn btn-info disabled mt-1'><i class="fas fa-download" style="font-size: 14px"></i></button>
                                                        @endif
                                                        <button type="button" data-toggle="modal" purchaseId='{{ $order->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $order->id }}")' class='btn btn-danger delete mt-1'><i class="fas fa-trash" style="font-size: 14px"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach                                    
                                        </tbody>                                
                                    </table>
                                </div>
                                
                                <div class="tab-pane" id="RekapanLink">
                                    <table id="invoice" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">Kode Purchase</th>
                                                <th class="textAlign" style="vertical-align: middle;">Total </th>
                                                <th class="textAlign" style="vertical-align: middle;">Tanggal Jatuh Tempo</th>
                                                <th class="textAlign" style="vertical-align: middle; width:25%;">action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">  
                                            @foreach ($invoice as $val)
                                                <tr>
                                                    <td>{{ $val->purchase->kode }}</td>
                                                    <td>{{ $val->total }}</td>
                                                    <td>{{ $val->paymentDue }}</td>
                                                    <td>
                                                        <a href="{{ route('adminPO.poInvoice.detail', $val->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                        <a href="{{ route('adminPO.poInvoice.update', $val->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                       <button type="button" data-toggle="modal" invoiceId='{{ $val->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $val->id }}")' class='btn btn-danger delete mt-1'><i class="fas fa-trash" style="font-size: 14px"></i></a>
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
            </div>
        </div>
    </section>
    <div id="DeleteModal" class="modal fade">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form id="deleteForm" method="post" >
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">DELETE CONFIRMATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <p>Anda yakin ingin menghapus data ini ?</p>
                        <input type="hidden" name="jenisBaju" id="jenisBaju">
                        <input type="hidden" name="ukuranBaju" id="ukuranBaju">
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-success" data-dismiss="modal">Batal</button>
                            <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Ya, Hapus</button>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        function deleteData(jenisBaju, ukuranBaju, opsi)
        {
                var jenisBaju = jenisBaju;
                var ukuranBaju = ukuranBaju;
                var url = '{{ route('GBatil.operator.delete') }}';
                // url = url.replace(':id', id);
                $('#jenisBaju').val(jenisBaju);
                $('#ukuranBaju').val(ukuranBaju);
                $("#deleteForm").attr('action', url);
            
        }

        function formSubmit()
        {
            $("#deleteForm").submit();
        }

        $(document).ready( function () {
            $('#purchase').DataTable( {
                "responsive": true,
            });
            $('#invoice').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush