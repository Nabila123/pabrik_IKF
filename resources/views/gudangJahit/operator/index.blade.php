 
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
                    <h1>Gudang Jahit Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Jahit Request</li>
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
                                <li class="nav-item"><a class="nav-link active" href="#userTweet" data-toggle="tab">Operator</a></li>
                                <li class="nav-item"><a class="nav-link retweetByClick" href="#retweetBy" data-toggle="tab">Basis</a></li>
                            </ul>                            
                        </div>                   
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="userTweet">
                                    <h3 class="card-title mb-4" style="width: 100%">
                                        <a href="{{ route('GJahit.operator.create') }}" class='btn btn-info btn-flat-right'>Ambil Barang</a>
                                    </h3>
                                    <table id="operator" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">Tanggal Request </th>
                                                <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                                <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                                <th class="textAlign" style="vertical-align: middle;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">
                                            @foreach ($operatorRequest as $detail)
                                                <tr>
                                                    <td>{{ date('d F Y', strtotime($detail->created_at)) }}</td>
                                                    <td>{{ strtoupper($detail->jenisBaju) }}</td>
                                                    <td>{{ $detail->ukuranBaju }}</td>
                                                    
                                                    <td>
                                                        <a href="{{ route('GJahit.operator.detail', [$detail->jenisBaju, $detail->ukuranBaju]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="retweetBy">
                                    <h3 class="card-title mb-4" style="width: 100%">
                                        <a href="{{ route('GJahit.basis.create') }}" class='btn btn-info btn-flat-right'>Tambah Basis</a>
                                    </h3>
                                    <table id="basis" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                                <th class="textAlign" style="vertical-align: middle;">Posisi</th>
                                                <th class="textAlign" style="vertical-align: middle;">Target Posisi</th>
                                                <th class="textAlign" style="vertical-align: middle;">Jumlah Saat Ini</th>
                                                <th class="textAlign" style="vertical-align: middle;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">
                                            @foreach ($jahitBasis as $basis)
                                                <tr>
                                                    <td>{{ date('d F Y', strtotime($basis->created_at)) }}</td>
                                                    <td>{{ strtoupper($basis->posisi) }}</td>
                                                    <td>{{ $basis->qtyTarget }}</td>
                                                    <td>{{ $basis->total }}</td>
                                                    
                                                    <td>
                                                        <a href="{{ route('GJahit.basis.detail', [$basis->posisi]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
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
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        $(document).ready( function () {
            $('#operator').DataTable( {
                "responsive": true,
            });
            $('#basis').DataTable( {
                "responsive": true,
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush