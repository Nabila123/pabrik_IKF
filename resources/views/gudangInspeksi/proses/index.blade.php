 
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
                    <h1>Proses Inspeksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Proses Inspeksi</li>
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
                        @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 12 || \Auth::user()->roleId == 30)   
                            <div class="card-header">
                                <h3 class="card-title">
                                    <a href="{{ route('GInspeksi.proses.create') }}" class='btn btn-info btn-flat-right'>Tambah Data</a>
                                </h3>
                            </div>   
                        @endif               
                        <div class="card-body">
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign">Nomor PO</th>
                                        <th class="textAlign">Nama Suplier</th>
                                        <th class="textAlign">Jenis Kain</th>
                                        <th class="textAlign">Tanggal Request</th>
                                        <th class="textAlign">Operator Inspeksi</th>
                                        <th class="textAlign">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gudangInspeksi as $inspeksi)
                                        <tr>
                                            <td>{{ $inspeksi->purchase->kode }}</td>
                                            <td>{{ $inspeksi->purchase->suplierName }}</td>
                                            <td>{{ $inspeksi->material->nama }}</td>
                                            <td>{{ date('d F Y', strtotime($inspeksi->inspeksiKeluar->tanggal)) }}</td>
                                            <td>{{ $inspeksi->user->nama }}</td>
                                            <td>
                                                <a href="{{ route('GInspeksi.proses.detail', $inspeksi->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                
                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 12)   
                                                    <a href="{{ route('GInspeksi.proses.update', $inspeksi->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                @endif
                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8)   
                                                    <button type="button" data-toggle="modal" inspeksiId='{{ $inspeksi->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $inspeksi->id }}")' class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></a>
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
            <form action="{{ route('GInspeksi.proses.delete') }}" id="deleteForm" method="post" >
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
                        <input type="hidden" name="inspeksiId" id="inspeksiId">
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
            var url = '{{ route('GInspeksi.proses.delete') }}';
            // url = url.replace(':id', id);
            console.log(id);
            $('#inspeksiId').val(id);
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