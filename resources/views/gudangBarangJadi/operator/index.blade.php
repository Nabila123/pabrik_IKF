 
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
                    <h1>Gudang Barang Jadi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Barang Jadi</li>
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
                            @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 11)
                                <h3 class="card-title" style="width: 100%">
                                    <a href="{{ route('GBarangJadi.operator.create') }}" class='btn btn-info btn-flat-right'>Tambah Pengeluaran Barang</a>
                                </h3>                           
                            @endif
                        </div>                   
                        <div class="card-body">
                            <table id="operator" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">No </th>
                                        <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                        <th class="textAlign" style="vertical-align: middle;">Kode Transaksi </th>
                                        <th class="textAlign" style="vertical-align: middle;">Kategori </th>
                                        <th class="textAlign" style="vertical-align: middle;">Customer (Pelanggan)</th>
                                        <th class="textAlign" style="vertical-align: middle;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    <?php $no = 1; ?>
                                   @foreach ($penjualans as $penjualan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ date('d F Y', strtotime($penjualan->tanggal)) }}</td>
                                            <td>{{ $penjualan->kodeTransaksi}}</td>
                                            <td>{{ $penjualan->kategori}}</td>
                                            <td>{{ $penjualan->customer }}</td>
                                            
                                            <td>
                                                <a href="{{ route('GBarangJadi.operator.detail', [$penjualan->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>

                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 11)
                                                    <button type="button" data-toggle="modal" requestId='{{ $penjualan->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $penjualan->id }}")' class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></a>
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
            var url = '{{ route('GBarangJadi.operator.delete') }}';
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
            $('#reject').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush