 
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
                    <h1>Purchase Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Order</li>
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
                            <h3 class="card-title">
                                <a href="{{ route('adminPO.poOrder.create') }}" class='btn btn-info btn-flat-right'>Tambah Data</a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle; width:5%;">Kode Purchase</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle; width:15%;">Tanggal Pengajuan</th>
                                        <th colspan="3" class="textAlign" style="vertical-align: middle;">Agreement </th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Status Kedatangan</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle; width:15%;">action</th>
                                    </tr>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">PPIC</th>
                                        <th class="textAlign" style="vertical-align: middle;">K. Dep Prod</th>
                                        <th class="textAlign" style="vertical-align: middle;">K. Dep PO</th>
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
                                                @if ($order->isKaDeptProd != 0)
                                                    {{ $order->isKaDeptProd }} <br>
                                                    Aproved At {{ date('d F Y', strtotime($order->isKaDeptProdAt)) }}
                                                @else
                                                    <br>
                                                    <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                @endif
                                                
                                            </td>
                                            <td>
                                                @if ($order->isKaDeptPO != 0)
                                                    {{ $order->isKaDeptPO }} <br>
                                                    Aproved At {{ date('d F Y', strtotime($order->isKaDeptPOAt)) }}
                                                @else
                                                    <br>
                                                    <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->statusDatang != 0)
                                                    Barang Datang <br>
                                                    At {{ date('d F Y', strtotime($order->updated_at)) }}
                                                @else
                                                    <span style="color: rgb(253, 5, 5); font-size: 12px">Barang Belum Datang</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('adminPO.poOrder.detail', $order->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                <a href="{{ route('adminPO.poOrder.update', $order->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                <button type="button" data-toggle="modal" purchaseId='{{ $order->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $order->id }}")' class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></a>
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
    </section>

    <div id="DeleteModal" class="modal fade">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="{{ route('adminPO.poOrder.delete') }}" id="deleteForm" method="post" >
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
                        <input type="hidden" name="purchaseId" id="purchaseId">
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
        function deleteData(id)
        {
            var id = id;
            var url = '{{ route('adminPO.poOrder.delete') }}';
            // url = url.replace(':id', id);
            console.log(id);
            $('#purchaseId').val(id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit()
        {
            $("#deleteForm").submit();
        }

        $(document).ready( function () {        
            $('#example2').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush