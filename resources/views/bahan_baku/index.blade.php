 
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
                            <div style="margin:10px; text-align: right;">
                                <a href="{{ route('bahan_baku.create') }}" class='btn btn-success btn-flat-right'><i class="fas fa-plus" style="font-size: 15px"></i> Barang Datang</a>
                            </div>
                            <table id="example2" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle;">Kode Purchase</th>
                                        <th style="vertical-align: middle; width:100%;">Nama Supplier</th>
                                        <th style="vertical-align: middle; width:100%;">Diameter </th>
                                        <th style="vertical-align: middle; width:100%;">Gramasi</th>
                                        <th style="vertical-align: middle; width: 100%;">Total</th>
                                        <th style="vertical-align: middle; width: 100%;">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>InternetExplorer</td>
                                        <td>InternetExplorer 95+</td>
                                        <td>InternetExplorer 4</td>
                                        <td>InternetExplorerX</td>
                                        <td>InternetExplorerX</td>
                                        <td>
                                            <a href="{{ route('bahan_baku.detail') }}" class='btn btn-warning btn-flat-right'><i class="fas fa-list-ul" style="font-size: 15px"></i> List</a>
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