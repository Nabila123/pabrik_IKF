 
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

        tr th{
            max-width:100%;
            white-space:nowrap;
        }

        tr td{
            max-width:100%;
            white-space:nowrap;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Detail Potong Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Potong Barang</li>
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
                                        <label>Nomor PO </label>
                                        <input type="text" value="{{ $gdPotong->purchase->kode }}" class="form-control namaOperator disabled" readonly>                                                                                 
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Operator</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gdPotong->user->nama }}" class="form-control namaOperator disabled" readonly>
                                    </div>
                                </div> 
    
                                <div class="col-4">
                                    <label>Nama Suplier</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gdPotong->purchase->suplierName }}" class="form-control namaSuplier disabled" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label>Jenis Kain</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gdPotong->material->nama }}" class="form-control jenisKain disabled" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label>Jumlah</label>
                                    <div class="input-group">                                            
                                        <input type="text" value="{{ $gdPotong->qty }}" class="form-control gramasi disabled" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-responsive dataTables_scrollBody textAlign">
                                        <thead>
                                            <tr>
                                                <th colspan="2" style="vertical-align: middle;">Kepala Kain</th>
                                                <th colspan="3" style="vertical-align: middle;">Kain Roll</th>
                                                <th colspan="2" style="vertical-align: middle;">Hasil Potong</th>
                                                <th colspan="2" style="vertical-align: middle;">Total Potong</th>
                                                <th colspan="10" style="vertical-align: middle;">Kain Aval</th>
                                                <th rowspan="2" style="vertical-align: middle;">SKB</th>
                                                <th rowspan="2" style="vertical-align: middle;">BS</th>
                                            </tr>
                                            <tr>
                                                <th>Jml Potong</th>
                                                <th>Berat Potong</th>
                                                <th>Berat Roll</th>
                                                <th>Diameter</th>
                                                <th>Gramasi</th>
                                                <th>Jns Baju</th>
                                                <th>Size</th>
                                                <th>Dz</th>
                                                <th>Kg</th>
                                                
                                                <th>Kecil</th>
                                                <th>Ketek</th>
                                                <th>Ketek Pot.</th>
                                                <th>Sumbu</th>
                                                <th>Bunder</th>
                                                <th>T. Kecil</th>
                                                <th>T. Besar</th>
                                                <th>Tangan</th>
                                                <th>KK. Putih</th>
                                                <th>KK. Belang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($gdPotongDetail as $detail)
                                                <tr>
                                                    <td>{{ $detail->jumlahRoll }}</td>
                                                    <td>{{ $detail->beratPotong }}</td>
                                                    <td>{{ $detail->diameter }}</td>
                                                    <td>{{ $detail->gramasi }}</td>
                                                    <td>{{ $detail->beratRoll }}</td>
                                                    <td>{{ $detail->jenisBaju }}</td>
                                                    <td>{{ $detail->ukuranBaju }}</td>
                                                    <td>{{ $detail->hasilDz }}</td>
                                                    <td>{{ $detail->hasilKg }}</td>
                                                    <td>{{ $detail->skb }}</td>
                                                    <td>{{ $detail->bs }}</td>
                                                    <td>{{ $detail->aKecil }}</td>
                                                    <td>{{ $detail->aKetek }}</td>
                                                    <td>{{ $detail->aKetekPotong }}</td>
                                                    <td>{{ $detail->aSumbu }}</td>
                                                    <td>{{ $detail->aBunder }}</td>
                                                    <td>{{ $detail->aTanggungKecil }}</td>
                                                    <td>{{ $detail->aTanggungBesar }}</td>
                                                    <td>{{ $detail->aTangan }}</td>
                                                    <td>{{ $detail->aKepalaPutih }}</td>
                                                    <td>{{ $detail->aKepalaBelang }}</td>
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