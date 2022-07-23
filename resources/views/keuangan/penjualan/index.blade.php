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
                    <h1>Penjulan Baju</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Penjulan Baju</li>
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
                            @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8)
                                <div style="margin:10px;">
                                    <a href="{{ route('Keuangan.penjualan.create') }}" class='btn btn-success mb-3'><i class="fas fa-plus" style="font-size: 15px"></i> Tambah Pembayaran</a>
                                </div>
                            @endif
                            <table id="example2" class="table table-bordered dataTables_scrollBody textAlign" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle;">Tanggal </th>
                                        <th style="vertical-align: middle;">Kode Transaksi</th>
                                        <th style="vertical-align: middle;">Customer (Pelanggan) </th>
                                        <th style="vertical-align: middle;">Total Harga </th>
                                        <th style="vertical-align: middle;">Total Bayar </th>
                                        <th style="vertical-align: middle;">action</th>
                                    </tr>
                                </thead>
                                <tbody>        
                                    @foreach ($pembayaran as $bayar)
                                        <tr>
                                            <td>{{ date('d F Y', strtotime($bayar->tanggal)) }}</td>
                                            <td>{{ $bayar->kodeTransaksi }}</td>
                                            <td>{{ $bayar->customer }}</td>
                                            <td>{{ rupiah($bayar->totalHarga) }}</td>
                                            <td>{{ rupiah($bayar->totalBayar) }}</td>
                                            <td>
                                                <a href="{{ route('Keuangan.penjualan.detail', [$bayar->kodeTransaksi]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
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
    <div id="DeleteModal" class="modal fade">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <form action="{{ route('bahan_baku.keluar.delete') }}" id="deleteForm" method="post" >
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">DELETE CONFIRMATION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <p>Anda yakin ingin menghapus data ini ?</p>
                        <input type="hidden" name="gudangRequestId" id="gudangRequestId">
                        <input type="hidden" name="gudangRequestName" id="gudangRequestName">
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button type="button" class="btn btn-success" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger submitDelete">Ya, Hapus</button>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        $(document).ready( function () {
            $('#example2').DataTable();

            $('.delete').click(function () {
               var id = $(this).attr('dataId');
               var gudangRequest = $(this).attr('dataRequest');
               $('#gudangRequestId').val(id);
               $('#gudangRequestName').val(gudangRequest);
            });

            $('.submitDelete').click(function(){
                $("#deleteForm").submit();
            })

            function hapusData(id)
            {
                var id = id;
                var url = '{{ route('bahan_baku.keluar.delete') }}';
                // url = url.replace(':id', id);
                console.log(id);
                $('#gudangId').val(id);
                $('#gudangId').val(id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit()
            {
                $("#deleteForm").submit();
            }
        });
    </script>
@endpush