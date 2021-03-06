 
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
                    <h1>Gudang Batil Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Batil Request</li>
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
                            <table id="pemindahan" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">Tanggal Request </th>
                                        <th class="textAlign" style="vertical-align: middle;">Status Diterima</th>
                                        <th class="textAlign" style="vertical-align: middle;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gdBatilMasuk as $detail)
                                        <tr>
                                            <td>{{ date('d F Y', strtotime($detail->tanggal)) }}</td>
                                            <td>
                                                @if ($detail->statusDiterima == 0)
                                                    @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 19)
                                                        <a href="{{ route('GBatil.request.terima', [$detail->id]) }}" class="btn btn-success"> Terima Barang </a>
                                                    @else
                                                        <span style="color: rgb(230, 140, 5); font-size: 15px">Dalam Proses Pengambilan Barang</span>
                                                    @endif
                                                @else
                                                     <span style="color: green; font-size: 13px">Sudah Diterima</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('GBatil.request.detail', [$detail->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
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
            $('#pemindahan').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush