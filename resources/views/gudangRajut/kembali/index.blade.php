 
@extends('layouts.app')

@push('page_css') 
    <style>
        .textAlign {
            vertical-align: middle; 
            text-align: center;
            font-size: 15px;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gudang Rajut Kembali</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Rajut Kembali</li>
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
                        {{--  <div class="card-header">
                            <h3 class="card-title">
                                <a href="#" class='btn btn-info btn-flat-right'>Kembalikan Barang</a>
                            </h3>
                        </div>                 --}}
                        <div class="card-body">
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign">Tanggal </th>
                                        <th class="textAlign">Operator Rajut</th>
                                        <th class="textAlign">Status Keterangan</th>
                                        <th class="textAlign">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gRajutKembali as $kembali)
                                        <tr>
                                            <td> {{ date('d F Y', strtotime($kembali->tanggal)) }}  </td>
                                            <td> {{ $kembali->user->nama }}  </td>
                                            <td> 
                                                @if ($kembali->statusDiterima == 0)
                                                <span style="color: rgb(221, 3, 3); font-size: 13px"> Dalam Proses Pengembalian</span>
                                               
                                                @else
                                                    <span style="color: green; font-size: 13px">Barang Sudah Dikembalikan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('GRajut.kembali.detail', [$kembali->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 13)
                                                    <a href="{{ route('GRajut.kembali.update', [$kembali->id]) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                @endif
                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 13)
                                                    <button type="button" data-toggle="modal" data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $kembali->id }}")' class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></a>        
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
                        <input type="hidden" name="gdRajutMId" id="gdRajutMId">
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
            var url = '{{ route('GRajut.kembali.delete') }}';
            // url = url.replace(':id', id);
            console.log(id);
            $('#gdRajutMId').val(id);
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