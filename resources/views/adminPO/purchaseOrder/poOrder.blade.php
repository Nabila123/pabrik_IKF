 
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
                            <table id="example2" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Kode Purchase</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Tanggal Pengajuan</th>
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
                                <tbody>
                                    <tr>
                                        <td>InternetExplorer</td>
                                        <td>InternetExplorer 95+</td>
                                        <td>InternetExplorer 4</td>
                                        <td>InternetExplorerX</td>
                                        <td>InternetExplorerX</td>
                                        <td>InternetExplorerX</td>
                                        <td>
                                            <a href="{{ route('adminPO.poOrder.detail') }}" class='btn btn-warning btn-flat-right'><i class="fas fa-list-ul" style="font-size: 15px"></i> List</a>
                                        </td>
                                    </tr>                                    
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