 
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
                    <h1>Gudang Packing Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Packing Request</li>
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
                                        <th class="textAlign" style="vertical-align: middle;">No </th>
                                        <th class="textAlign" style="vertical-align: middle;">Nomor PO</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                        <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jumlah Baju (Dz)</th>
                                        <th class="textAlign" style="vertical-align: middle;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    <?php $no = 1; ?>
                                    @foreach ($packingRequest as $detail)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $detail->purchase->kode }}</td>
                                            <td>{{ $detail->jenisBaju }}</td>
                                            <td>{{ $detail->ukuranBaju }}</td>
                                            <td>{{ $detail->jumlah/12 }}</td>
                                            <td>
                                                @if ($detail->kodeBarcode != null)
                                                    <span style="color: green; font-size: 13px">Sudah DiGenarate</span>
                                                @else
                                                    @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 22)
                                                        <a href="{{ route('GPacking.request.generate', [$detail->purchaseId, $detail->jenisBaju, $detail->ukuranBaju, $detail->tanggal]) }}" class="btn btn-success"> Generate Barcode </a>
                                                    @else
                                                        <span style="color: rgb(209, 34, 10); font-size: 15px">Belum DiGenerate</span>
                                                    @endif
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
            $('#pemindahan').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush