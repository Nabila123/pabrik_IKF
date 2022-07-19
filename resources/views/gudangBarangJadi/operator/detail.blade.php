 
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
                    <h1>Detail Penjualan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Penjualan</li>
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
                            <table class="table textAlign">
                                <tr>
                                    <td> <span style="font-size: 25px; font-weight: bold;">Kode Transaksi</span> <br> <span>No. {{ $penjualan->kodeTransaksi }}</span> </td>
                                </tr>
                            </table>
                        </div>                       
                        <div class="card-body">
                            <table cellpadding="5">
                                <tr>
                                    <td style="font-weight: bold;">Tanggal Transaksi</td>
                                    <td>:</td>
                                    <td>{{ $penjualan->tanggal }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Customer (Pelanggan)</td>
                                    <td>:</td>
                                    <td>{{ $penjualan->customer }}</td>
                                </tr>
                            </table>

                            <table class="table table-bordered mt-4 textAlign" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Kode Product</th>
                                        <th>Jenis Baju</th>
                                        <th>Ukuran Baju</th>
                                        <th>Jumlah (Dus)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualanDetail as $detail)
                                        <tr>
                                            <td>{{ $detail->kodeProduct }}</td>
                                            <td>{{ $detail->jenisBaju }}</td>
                                            <td>{{ $detail->ukuranBaju }}</td>
                                            <td>{{ $detail->qty }}</td>
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