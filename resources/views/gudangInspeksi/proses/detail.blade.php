 
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
                    <h1> Detail Inspeksi Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Inspeksi Barang</li>
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
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title" style="float: right">
                                Pengerjaan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div> 
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Kode Purchase </label>
                                        <input type="text" value="{{ $gudangInspeksi->purchase->kode }}" class="form-control namaOperator disabled" readonly>                                                                                 
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Operator</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gudangInspeksi->user->nama }}" class="form-control namaOperator disabled" readonly>
                                    </div>
                                </div> 
    
                                <div class="col-3">
                                    <label>Nama Suplier</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gudangInspeksi->purchase->suplierName }}" class="form-control namaSuplier disabled" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label>Jenis Kain</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gudangInspeksi->material->nama }}" class="form-control jenisKain disabled" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label>Gramasi</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gudangInspeksi->gramasi }}" class="form-control gramasi disabled" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label>Diameter</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gudangInspeksi->diameter }}" class="form-control diameter disabled" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered textAlign">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" style="vertical-align: middle;">Roll</th>
                                                <th rowspan="2" style="vertical-align: middle;">Berat</th>
                                                <th rowspan="2" style="vertical-align: middle;">Pnj. Kain</th>
                                                <th colspan="6">Kerusakan</th>
                                                <th rowspan="2" style="vertical-align: middle;">Keterangan</th>
                                            </tr>
                                            <tr>
                                                <th>Lubang</th>
                                                <th>Plex</th>
                                                <th>Belang</th>
                                                <th>Tanah</th>
                                                <th>B. Sambung</th>
                                                <th>Jarum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($gudangInspeksiDetail as $detail)
                                                <tr>
                                                    <td>{{ $detail->roll }}</td>
                                                    <td>{{ $detail->berat }}</td>
                                                    <td>{{ $detail->yard }}</td>
                                                    <td>{{ $detail->lubang }}</td>
                                                    <td>{{ $detail->plek }}</td>
                                                    <td>{{ $detail->belang }}</td>
                                                    <td>{{ $detail->tanah }}</td>
                                                    <td>{{ $detail->bs }}</td>
                                                    <td>{{ $detail->jarum }}</td>
                                                    <td>{{ $detail->keterangan }}</td>
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
            $('#example2').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush