 
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
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;width:10%;">Kode Purchase</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle; width:15%;">Tanggal Pengajuan</th>
                                        <th colspan="3" class="textAlign" style="vertical-align: middle;">Agreement </th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Status Kedatangan</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle; width:10%;">action</th>
                                    </tr>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">PPIC</th>
                                        <th class="textAlign" style="vertical-align: middle;">K. Dep Prod</th>
                                        <th class="textAlign" style="vertical-align: middle;">K. Dep PO</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($poRequest as $request)
                                        <tr>
                                            <td>{{ $request->kode }}</td>
                                            <td>{{ date('d F Y', strtotime($request->tanggal)) }}</td>
                                            <td>
                                                {{ $request->user->nama }} <br>
                                                <span style="color: green; font-size: 10px">Submitted At {{ date('d F Y', strtotime($request->tanggal)) }}</span>

                                            </td>
                                            <td>
                                                @if ($request->isKaDeptProd != 0)
                                                    {{ $request->isKaDeptProd }} <br>
                                                    Aproved At {{ date('d F Y', strtotime($request->isKaDeptProdAt)) }}
                                                @else
                                                    <br>
                                                    <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                @endif                                                
                                            </td>
                                            <td>
                                                @if ($request->isKaDeptPO != 0)
                                                    {{ $request->isKaDeptPO }} <br>
                                                    Aproved At {{ date('d F Y', strtotime($request->isKaDeptPOAt)) }}
                                                @else
                                                    <br>
                                                    <span style="color: rgb(253, 5, 5); font-size: 10px">Dalam Proses Approve</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($request->statusDatang != 0)
                                                    Barang Datang <br>
                                                    At {{ date('d F Y', strtotime($request->updated_at)) }}
                                                @else
                                                    <span style="color: rgb(253, 5, 5); font-size: 12px">Barang Belum Datang</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('adminPO.poRequest.detail', $request->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                {{--  <a href="{{ route('adminPO.poRequest.update', $request->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                <button type="button" data-toggle="modal" purchaseId='{{ $request->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $request->id }}")' class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></a>  --}}
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
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        $(document).ready( function () {
            $('#example2').DataTable();
        });
    </script>
@endpush