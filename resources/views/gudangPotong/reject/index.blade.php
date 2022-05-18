 
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
                    <h1>Gudang Potong Reject</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Potong Reject</li>
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
                            <table id="operator" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">No </th>
                                        <th class="textAlign" style="vertical-align: middle;">Reject From </th>
                                        <th class="textAlign" style="vertical-align: middle;">Tanggal</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jumlah Baju</th>
                                        <th class="textAlign" style="vertical-align: middle;">Status Proses</th>
                                        <th class="textAlign" style="vertical-align: middle;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    <?php $no = 1; ?>
                                   @foreach ($jahitReject as $reject)
                                       <tr>
                                           <td>{{ $no++ }}</td>
                                           <td>{{ $reject->gudangRequest }}</td>
                                           <td>{{ date('d F Y', strtotime($reject->tanggal)) }}</td>
                                           <td>{{ $reject->totalBaju }}</td>
                                           <td>
                                               @if ($reject->statusProses == 0)
                                                    @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 31)
                                                        <a href="{{ route('GJahit.reject.terima', [$reject->id]) }}" class="btn btn-success"> Proses Reject </a>
                                                    @else
                                                        <span style="color: rgb(209, 34, 10); font-size: 15px">Belum Di Proses</span>
                                                    @endif

                                                @else
                                                    @if ($reject->statusProses == 1)
                                                        @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 31)
                                                            <a href="{{ route('GJahit.reject.kembali', [$reject->id]) }}" class="btn btn-info"> Kembalikan Barang </a>
                                                        @else
                                                            <span style="color: rgb(230, 140, 5); font-size: 15px">Sedang Dalam Proses</span>
                                                        @endif
                                                    @else
                                                        @if ($reject->statusProses == 2)
                                                            <span style="color: rgb(230, 140, 5); font-size: 13px">Dalam Proses Dikembalikan</span>                                                            
                                                        @else
                                                            <span style="color: green; font-size: 13px">Sudah Dikembalikan</span>
                                                        @endif
                                                    @endif
                                               @endif
                                           </td>
                                           <td>
                                                <a href="{{ route('GJahit.reject.detail', [$reject->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
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
                        <input type="hidden" name="jenisBaju" id="jenisBaju">
                        <input type="hidden" name="ukuranBaju" id="ukuranBaju">
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
        function deleteData(jenisBaju, ukuranBaju, opsi)
        {
            if(opsi == "basis"){
                var id = id;
                var url = '{{ route('GJahit.basis.delete') }}';
                // url = url.replace(':id', id);
                console.log(id);
                $('#jenisBaju').val(jenisBaju);
                $('#ukuranBaju').val(ukuranBaju);
                $("#deleteForm").attr('action', url);

            }else{
                var jenisBaju = jenisBaju;
                var ukuranBaju = ukuranBaju;
                var url = '{{ route('GJahit.operator.delete') }}';
                // url = url.replace(':id', id);
                $('#jenisBaju').val(jenisBaju);
                $('#ukuranBaju').val(ukuranBaju);
                $("#deleteForm").attr('action', url);
            }
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