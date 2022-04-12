 
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
                    <h4>Stok Barang</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><a href="#">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($dataStok as $stok)
                    <div class="col-12 col-sm-12 col-md-4">
                        <div class="info-box mb-4">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text" >{{strtoupper($stok['nama'])}} ( {{ $stok['ukuran'] }} )</span>
                                <span class="info-box-number"> {{ ($stok['qty']/12) }} Dz</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Proses Jahit Belum Selesai</h5>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class="textAlign" rowspan="2" style="vertical-align: middle; width:10%;">No</th>
                                        <th class="textAlign" rowspan="2" style="vertical-align: middle;">Tanggal</th>
                                        <th class="textAlign" rowspan="2" style="vertical-align: middle;">Nomor PO</th>
                                        <th class="textAlign" rowspan="2" style="vertical-align: middle;">Jenis Baju</th>
                                        <th class="textAlign" rowspan="2" style="vertical-align: middle;">Ukuran</th>
                                        <th class="textAlign" colspan="3" style="vertical-align: middle;">Keterangan</th>
                                    </tr>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">Soom</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jahit</th>
                                        <th class="textAlign" style="vertical-align: middle;">Bawahan</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    <?php $no = 1; ?>
                                   @foreach ($dataProses as $proses)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ date('d F Y', strtotime($proses->created_at)) }}</td>
                                            <td>{{ $proses->purchase->kode }}</td>
                                            <td>{{ $proses->jenisBaju }}</td>
                                            <td>{{ $proses->ukuranBaju }}</td>
                                            <td>
                                                @if ($proses->soom == 1)
                                                    <i class="fa-solid fa-check"></i>
                                                @else
                                                    <i class="fas fa-xmark"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($proses->jahit == 1)
                                                    <i class="fa-solid fa-check"></i>
                                                @else
                                                    <i class="fas fa-xmark"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($proses->bawahan == 1)
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
            $('#example2').DataTable({
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush