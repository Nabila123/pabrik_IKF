 
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
                    <h1>Detail Gudang Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Gudang Potong Request</li>
                        <li class="breadcrumb-item active">Detail</li>
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
                            <table class="table">
                                <tr>
                                    <td> <b>Status Pemrosesan :</b> <span style="color:{{ $gdPotongRequest->color }};">{{ $gdPotongRequest->status }}</span></td>
                                </tr>
                                <tr>
                                    <td> <b>Tanggal Pengambilan :</b> {{ date('d F Y', strtotime($gdPotongRequest->tanggal)) }}</td>
                                </tr>
                            </table>
                        </div>                       
                        <div class="card-body">
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                        <th colspan="2" class="textAlign">Jumlah</th>
                                    </tr>
                                    <tr>
                                        <th class="textAlign">Pcs</th>
                                        <th class="textAlign">Dz</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gdPotongRequestDetail as $detail)
                                        <tr>
                                            <td>{{ $detail->jenisBaju }}</td>
                                            <td>{{ $detail->ukuranBaju }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ $detail->dz }}</td>
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
            $('#example2').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush