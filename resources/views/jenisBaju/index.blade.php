 
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
                    <h1>Jenis Baju</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Jenis Baju</li>
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
                            <div style="margin:10px; text-align: right;">
                                <a href="{{ route('JenisBaju.create') }}" class='btn btn-success btn-flat-right'><i class="fas fa-plus" style="font-size: 15px"></i> Tambah Data</a>
                            </div>
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">Jenis </th>
                                        <th class="textAlign" style="vertical-align: middle;">Ukuran</th>
                                        <th class="textAlign" style="vertical-align: middle; width:15%;">action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($jenisBajus as $jenisBaju)
                                        <tr>
                                            <td>{{ $jenisBaju->jenis}} </td>
                                            <td>{{ $jenisBaju->ukuran}} </td>
                                            <td>
                                                <a href="{{ route('JenisBaju.detail', $jenisBaju->id) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                <a href="{{ route('JenisBaju.edit', $jenisBaju->id) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                <button type="button" data-toggle="modal" purchaseId='{{ $jenisBaju->id }}' data-target="#DeleteModal" id="modalDelete" onclick='deleteData("{{ $jenisBaju->id }}")' class='btn btn-danger delete mt-1'><i class="fas fa-trash" style="font-size: 14px"></i></a>
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
            <form action="{{ route('JenisBaju.delete') }}" id="deleteForm" method="post" >
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
                        <input type="hidden" name="id" id="id">
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
            var url = '{{ route("JenisBaju.delete") }}';
            // url = url.replace(':id', id);
            console.log(id);
            $('#id').val(id);
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