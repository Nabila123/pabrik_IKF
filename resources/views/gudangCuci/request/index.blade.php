 
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
                    <h1>Gudang Cuci Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Cuci Request</li>
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
                                        <th class="textAlign">Tanggal Pengambilan </th>
                                        <th class="textAlign">Operator Bahan Baku</th>
                                        <th class="textAlign">Status Keterangan</th>
                                        <th class="textAlign">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gCuciRequest as $detail)
                                        <tr>
                                            <td>{{ date('d F Y', strtotime($detail->tanggal)) }}</td>
                                            <td>{{ $detail->user->nama }}</td>
                                            <td>
                                                @if ($detail->statusDiterima == 0)
                                                    <a href="{{ route('GCuci.request.terima', [$detail->id]) }}" class="btn btn-success"> Terima Barang </a>
                                                @else
                                                    @if ($detail->statusDiterima == 1 && !isset($detail->cekPengembalian))
                                                        <input type="hidden" name="gudangKeluarId" id="gudangKeluarId" value="{{ $detail->id }}">
                                                        <a href="{{ route('GCuci.request.kembali', [$detail->id]) }}" class="btn btn-info requestKode" style="font-size: 13px;"> Ajukan Pemindahan </a> <br>
                                                        <span style="color: green; font-size: 10px">Pindahkan Barang</span>
                                                    @else
                                                        <span style="color: green; font-size: 15px">Barang Sudah DiPindahkan</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('GCuci.request.detail', [$detail->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
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
            });
        });
    </script>
@endpush