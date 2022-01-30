 
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
                    <h1>Proses Inspeksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Proses Inspeksi</li>
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
                                <a href="{{ route('GInspeksi.proses.create') }}" class='btn btn-info btn-flat-right'>Tambah Data</a>
                            </h3>
                        </div>                  
                        <div class="card-body">
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign">Kode Purchase</th>
                                        <th class="textAlign">Nama Suplier</th>
                                        <th class="textAlign">Jenis Kain</th>
                                        <th class="textAlign">Tanggal</th>
                                        <th class="textAlign">Operator Inspeksi</th>
                                        <th class="textAlign">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gudangInspeksi as $inspeksi)
                                        <td>{{ $inspeksi->purchase->kode }}</td>
                                        <td>{{ $inspeksi->purchase->suplierName }}</td>
                                        <td>{{ $inspeksi->material->nama }}</td>
                                        <td>{{ date('d F Y', strtotime($inspeksi->tanggal)) }}</td>
                                        <td>{{ $inspeksi->user->nama }}</td>
                                        <td>
                                            <a href="{{ route('GInspeksi.proses.detail', $inspeksi->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                        </td>
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
            $('#example2').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush