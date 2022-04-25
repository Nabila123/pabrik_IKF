 
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
                    <h1>Detail Pembayaran</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Pembayaran</li>
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
                                    <td>{{ date('d F Y', strtotime($penjualan->tanggal)) }}</td>
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
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualanDetail as $detail)
                                        <tr>
                                            <td>{{ $detail->kodeProduct }}</td>
                                            <td>{{ $detail->jenisBaju }}</td>
                                            <td>{{ $detail->ukuranBaju }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ rupiah($detail->harga) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" style="text-align: right;">Total Harga</th>
                                        <th>{{ rupiah($penjualan->totalHarga) }}</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card card-info">
                                        <div class="card-header">
                                          <h3 class="card-title">Detail Pembayaran</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">  
                                                <div class="col-12">
                                                    <table class="table table-bordered textAlign" style="width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Tanggal</th>
                                                                <th>Keuangan</th>
                                                                <th>Jumlah</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($pembayaran as $detail)
                                                                <tr>
                                                                    <td>{{ date('d F Y', strtotime($detail->tanggal)) }}</td>
                                                                    <td>{{ $detail->user->nama }}</td>
                                                                    <td>{{ rupiah($detail->totalHarga) }}</td>
                                                                    <td>
                                                                        @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 6 || \Auth::user()->roleId == 9)
                                                                            <button type="button" data-toggle="modal" data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $detail->id }}", "{{ $detail->kodeTransaksi }}")' class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></a>
                                                                        @else
                                                                            -
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
                        <input type="hidden" name="pembayaranId" id="pembayaranId">
                        <input type="hidden" name="kodeTransaksi" id="kodeTransaksi">
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
        function deleteData(id, kodeTransaksi)
        {
            var id = id;
            var kodeTransaksi = kodeTransaksi;
            var url = '{{ route('Keuangan.penjualan.delete') }}';
            // url = url.replace(':id', id);
            console.log(id);
            $('#pembayaranId').val(id);
            $('#kodeTransaksi').val(kodeTransaksi);
            $("#deleteForm").attr('action', url);            
        }

        function formSubmit()
        {
            $("#deleteForm").submit();
        }

        $(document).ready( function () {
            $('#example2').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush