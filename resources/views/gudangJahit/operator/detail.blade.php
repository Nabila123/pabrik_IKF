 
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
                    <h1>Detail Jahit Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Jahit Request</li>
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
                        <div class="card-body">
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">No </th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Nomor PO</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                        <th rowspan="2" class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                        <th colspan="3" class="textAlign" style="vertical-align: middle;">Keterangan</th>
                                    </tr>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">Soom</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jahit</th>
                                        <th class="textAlign" style="vertical-align: middle;">Bawahan</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    <?php $no = 1; ?>
                                    @foreach ($operatorRequest as $detail)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $detail->purchase->kode }}</td>
                                            <td>{{ strtoupper($detail->jenisBaju) }}</td>
                                            <td>{{ $detail->ukuranBaju }}</td>
                                            <td>
                                                @if ($detail->soom == 1)
                                                    <i class="fa-solid fa-check"></i>
                                                @else
                                                    <i class="fas fa-xmark"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($detail->jahit == 1)
                                                    <i class="fa-solid fa-check"></i>
                                                @else
                                                    <i class="fas fa-xmark"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($detail->bawahan == 1)
                                                    <i class="fa-solid fa-check"></i>
                                                @else
                                                    <i class="fas fa-xmark"></i>
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
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        $(document).ready( function () {
            $('#example2').DataTable( {
                "responsive": true,
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush