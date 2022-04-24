 
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
                    <h1>Purchase Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Purchase Request</li>
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
                            @if (\Auth::user()->roleId == 38 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 10 || \Auth::user()->roleId == 24)
                                <h3 class="card-title">
                                    <a href="{{ route('adminPO.poRequest.create') }}" class='btn btn-info btn-flat-right'>Tambah Data</a>
                                </h3>
                            @endif
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered  dataTables_scrollBody" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Nomor PO</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Tanggal Pengajuan</th>
                                        <th colspan="3" class="textAlign" style="vertical-align: middle;">Agreement </th>
                                        <th colspan="2" class="textAlign" style="vertical-align: middle;">Status</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle; width:11%;">action</th>
                                    </tr>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">PPIC</th>
                                        <th class="textAlign" style="vertical-align: middle;">Manager Prod</th>
                                        <th class="textAlign" style="vertical-align: middle;">Manager PO</th>
                                        <th class="textAlign" style="vertical-align: middle;">Proses Order</th>
                                        <th class="textAlign" style="vertical-align: middle;">Kedatangan</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($poRequest as $request)
                                        <tr>
                                            <td>
                                                @if (\Auth::user()->roleId == 7 && $request->isKaDeptPO != 0 && $request->prosesOrder != true)
                                                    <a href="{{ route('adminPO.poRequest.requestKode', $request->id) }}" class="btn btn-info requestKode"> Buat Pruchase Order </a> <br>
                                                    <span style="color: green; font-size: 10px">Lanjut Purchase Order</span>
                                                @else
                                                    {{ $request->kode }}
                                                @endif
                                            </td>
                                            <td>{{ date('d F Y', strtotime($request->tanggal)) }}</td>
                                            <td>
                                                {{ $request->user->nama }} <br>
                                                <span style="color: green; font-size: 10px">Submitted At {{ date('d F Y', strtotime($request->tanggal)) }}</span>

                                            </td>
                                            <td>
                                                @if ($request->isKaDeptProd != 0)
                                                    {{ $request->roleKaDeptProdUser->nama }} <br>
                                                    <span style="color: green; font-size: 10px"> Aproved At {{ date('d F Y', strtotime($request->isKaDeptProdAt)) }} </span>
                                                @else
                                                    @if (\Auth::user()->roleId == 8)
                                                    <button type="button" id="approveKaDeptProd" purchaseid="{{ $request->id }}" class='btn btn-success approveKaDeptProd'><i class="fas fa-check-square" style="font-size: 14px"> </i> Approve</a>
                                                    @else
                                                        <br>
                                                        <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                    @endif
                                                @endif                                                
                                            </td>
                                            <td>
                                                @if ($request->isKaDeptPO != 0)
                                                    {{ $request->roleKaDeptPOUser->nama }} <br>
                                                    <span style="color: green; font-size: 10px"> Aproved At {{ date('d F Y', strtotime($request->isKaDeptPOAt)) }} </span>
                                                @else
                                                    @if (\Auth::user()->roleId == 7 && $request->isKaDeptProd != 0)
                                                        <button type="button" id="approveKaDeptPO" purchaseId="{{ $request->id }}" class='btn btn-success approveKaDeptPO'><i class="fas fa-check-square" style="font-size: 14px"> </i> Approve</a>
                                                    @else
                                                        <br>
                                                        <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($request->prosesOrder == true)
                                                    <span style="color: green; font-size: 10px"> {{ date('d F Y', strtotime($request->prosesOrderAt)) }} </span>
                                                @else
                                                    <span style="color: rgb(253, 5, 5); font-size: 12px">Menunggu Proses Order</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($request->barangDatang == true)
                                                    Barang Datang <br>
                                                    <span style="color: green; font-size: 10px"> At {{ date('d F Y', strtotime($request->barangDatangAt)) }} </span>
                                                @else
                                                    <span style="color: rgb(253, 5, 5); font-size: 12px">Barang Belum Datang</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('adminPO.poRequest.detail', $request->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                
                                                @if (\Auth::user()->roleId == 38 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 10)
                                                    @if ($request->prosesOrder != true)
                                                        <a href="{{ route('adminPO.poRequest.update', $request->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                    @else
                                                        <button type="button" class='btn btn-success disabled mt-1'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></button>
                                                    @endif
                                                @endif

                                                @if ($request->isKaDeptPO != 0)
                                                    <a href="{{ route('adminPO.poRequest.unduh', $request->id) }}" target="_blank" class='btn btn-info mt-1'><i class="fas fa-download" style="font-size: 14px"></i></a>
                                                @else
                                                    <button type="button" class='btn btn-info disabled mt-1'><i class="fas fa-download" style="font-size: 14px"></i></button>
                                                @endif
                                                
                                                @if (\Auth::user()->roleId == 38 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7)
                                                    <button type="button" data-toggle="modal" purchaseId='{{ $request->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $request->id }}")' class='btn btn-danger delete mt-1'><i class="fas fa-trash" style="font-size: 14px"></i></a>
                                                @endif
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
            <form action="{{ route('adminPO.poRequest.delete') }}" id="deleteForm" method="post" >
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
            var url = '{{ route('adminPO.poRequest.delete') }}';
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
            $('#example2').DataTable({
                "responsive": true,
            });
            
            $(document).on("click", "button.approveKaDeptProd", function(e){
                e.preventDefault();
                var purchaseId = $(this).attr('purchaseId');
               
                $.ajax({
                    type: "get",
                    url: '{{ url('adminPO/Request/approve') }}',
                    data: {
                        'purchaseId' : purchaseId,
                        'approve' : 'isKaDeptProd',
                        'approveAt' : 'isKaDeptProdAt',
                    },
                    success: function(response){                        
                        if(response == 1){
                            location.reload();
                        }
                    }
                })
            });

            $(document).on("click", "button.approveKaDeptPO", function(e){
                e.preventDefault();
                var purchaseId = $(this).attr('purchaseId');
               
                $.ajax({
                    type: "get",
                    url: '{{ url('adminPO/Request/approve') }}',
                    data: {
                        'purchaseId' : purchaseId,
                        'approve' : 'isKaDeptPO',
                        'approveAt' : 'isKaDeptPOAt',
                    },
                    success: function(response){                        
                        if(response == 1){
                            location.reload();
                        }
                    }
                })
            });
        });
    </script>
@endpush