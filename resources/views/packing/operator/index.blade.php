 
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
                    <h1>Packing Rekap</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Packing</li>
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
                                <li class="nav-item"><a class="nav-link active" href="#BarcodeLink" data-toggle="tab">Data Generate Barcode</a></li>
                                <li class="nav-item"><a class="nav-link " href="#OperatorLink" data-toggle="tab">Rekap Packing</a></li>
                                <li class="nav-item"><a class="nav-link" href="#PindahLink" data-toggle="tab">Pindah Ke G. Barang Jadi</a></li>
                            </ul>                            
                        </div>                   
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="BarcodeLink">
                                    @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 36)
                                        <h3 class="card-title mb-4" style="width: 100%">
                                            <a href="{{ route('GPacking.operator.cetakBarcode') }}" target="blank" class='btn btn-success btn-flat-right'>Cetak Barcode</a>
                                        </h3>
                                    @endif
                                    <table id="barcode" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">No </th>
                                                <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                                <th class="textAlign" style="vertical-align: middle;">Kode Barcode</th>
                                                <th class="textAlign" style="vertical-align: middle;">No PO</th>
                                                <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                                <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">
                                            <?php $no = 1; ?>
                                        @foreach ($dataBarcode as $barcode)
                                            @if (!isset($barcode->barangJadi))
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ date('d F Y', strtotime($barcode->tanggal)) }}</td>
                                                    <td>
                                                        <span style="font-weight: bold;">{{ $barcode->keterangan }}</span> <br>
                                                        <small>{{ $barcode->kodeBarcode}}</small>
                                                    </td>
                                                    <td>{{ $barcode->purchase->kode}}</td>
                                                    <td>{{ $barcode->jenisBaju}}</td>
                                                    <td>{{ $barcode->ukuranBaju }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="OperatorLink">
                                    @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 36)
                                        <h3 class="card-title mb-4" style="width: 100%">
                                            <a href="{{ route('GPacking.rekap.create') }}" class='btn btn-info btn-flat-right'>Rekap Barang</a>
                                        </h3>
                                    @endif
                                    <table id="operator" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">No </th>
                                                <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                                <th class="textAlign" style="vertical-align: middle;">Packing Pegawai </th>
                                                <th class="textAlign" style="vertical-align: middle;">Operator</th>
                                                <th class="textAlign" style="vertical-align: middle;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">
                                            <?php $no = 1; ?>
                                        @foreach ($packingRekap as $rekap)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ date('d F Y', strtotime($rekap->tanggal)) }}</td>
                                                    <td>{{ $rekap->pegawaiName}}</td>
                                                    <td>{{ $rekap->user->nama }}</td>
                                                    
                                                    <td>
                                                        <a href="{{ route('GPacking.rekap.detail', [$rekap->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>

                                                        @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8)
                                                            @if ($rekap->tanggal == date("Y-m-d"))
                                                                <a href="{{ route('GPacking.rekap.update', $rekap->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                            @else
                                                                <button type="button" class="btn btn-success disabled" style="width:40px;"><span class="fas fa-pencil-alt"></span></button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="PindahLink">
                                    @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 36)
                                        <h3 class="card-title mb-4" style="width: 100%">
                                            <a href="{{ route('GPacking.bahanBaku.create') }}" class='btn btn-info btn-flat-right'>Pindahkan Barang</a>
                                        </h3>
                                    @endif
                                    <table id="barangJadi" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th class="textAlign" style="vertical-align: middle;">No </th>
                                                <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                                <th class="textAlign" style="vertical-align: middle;">Kode Product</th>
                                                <th class="textAlign" style="vertical-align: middle;">Nomor PO </th>
                                                <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                                <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                            </tr>
                                        </thead>
                                        <tbody class="textAlign">
                                            <?php $no = 1; ?>
                                            @foreach ($barangJadi as $detail)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ date('d F Y', strtotime($detail->tanggal)) }}</td>
                                                    <td>
                                                        <span style="font-weight: bold;">{{ $detail->keterangan }}</span> <br>
                                                        <small>{{ $detail->kodeProduct}}</small>
                                                    </td>
                                                    <td>{{ $detail->purchase->kode}}</td>
                                                    <td>{{ $detail->jenisBaju}}</td>
                                                    <td>{{ $detail->ukuranBaju }}</td>
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
    <div id="DeleteModal" class="modal fade">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form id="deleteForm" method="post" >
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">DELETE CONFIRMATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <p>Anda yakin ingin menghapus data ini ?</p>
                        <input type="hidden" name="rejectId" id="rejectId">
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-success" data-dismiss="modal">Batal</button>
                            <button type="submit" name="" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Ya, Hapus</button>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        function deleteData(id)
        {
            var id = id;
            var url = '{{ route('GBatil.reject.delete') }}';
            // url = url.replace(':id', id);
            console.log(id);
            $('#rejectId').val(id);
            $("#deleteForm").attr('action', url);            
        }

        function formSubmit()
        {
            $("#deleteForm").submit();
        }

        $(document).ready( function () {
            $('#barcode').DataTable({
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
            $('#operator').DataTable({
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
            $('#barangJadi').DataTable({
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush