@extends('layouts.app')

@push('page_css')
    <style>
        .textAlign {
            vertical-align: middle;
            text-align: center;
            font-size: 15px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
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
                    <h1>Request Masuk Gudang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Request Masuk Gudang</li>
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

                        @if (session()->has('success'))
                            <div class="card-title">
                                <div class="alert alert-success alert-dismissible fade show text-white">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <strong> Berhasil! :</strong> {{ session()->get('success') }}
                                    <button type="button" class="close alert-close text-white" data-bs-dismiss="alert"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="card-title">
                                <div class="alert alert-danger alert-dismissible fade show text-white">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <strong> Gagal! :</strong> {{ session()->get('error') }}
                                    <button type="button" class="close alert-close text-white" data-bs-dismiss="alert"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div class="card-body">
                            <table id="example2"
                                class="table table-bordered table-responsive dataTables_scrollBody textAlign"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; width:10%;">Relasi G. Keluar</th>
                                        <th style="vertical-align: middle; width:20%;">Gudang Request</th>
                                        <th style="vertical-align: middle; width:10%;">Tanggal </th>
                                        <th style="vertical-align: middle; width:20%;">Status </th>
                                        <th style="vertical-align: middle; width:10%;">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($data); $i++)
                                        @for ($j = 0; $j < count($data[$i]); $j++)
                                            <tr>
                                                <td>{{ $data[$i][$j]->foreign }}</td>
                                                <td>{{ $data[$i][$j]->gudangRequest }}</td>
                                                <td>{{ date('d F Y', strtotime($data[$i][$j]->tanggal)) }}</td>
                                                <td>
                                                    @if ($data[$i][$j]->statusDiterima == 0)
                                                        @if (\Auth::user()->roleId == 1 ||
                                                            \Auth::user()->roleId == 4 ||
                                                            \Auth::user()->roleId == 7 ||
                                                            \Auth::user()->roleId == 10)
                                                            <a href="{{ route('bahan_baku.masuk.terima', [$data[$i][$j]->id, $data[$i][$j]->gudangRequest]) }}"
                                                                class="btn btn-success"> Terima Barang </a>
                                                        @else
                                                            <span style="color: rgb(194, 94, 0); font-size: 13px">Dalam
                                                                Proses Pengembalian </span>
                                                        @endif
                                                    @elseif ($data[$i][$j]->statusDiterima == 1)
                                                        <span style="color: green; font-size: 13px">Barang Sudah Diterima
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('bahan_baku.masuk.detail', ['id' => $data[$i][$j]->id, 'gudangRequest' => $data[$i][$j]->gudangRequest]) }}"
                                                        class='btn btn-warning'><i class="fas fa-list-ul"
                                                            style="font-size: 14px"></i></a>
                                                    {{--  <a href="{{ route('bahan_baku.keluar.update',['id'=>$data[$i][$j]->id, 'gudangRequest'=>$data[$i][$j]->gudangRequest])}}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>  --}}
                                                    @if (\Auth::user()->roleId == 1 ||
                                                        \Auth::user()->roleId == 4 ||
                                                        \Auth::user()->roleId == 7 ||
                                                        \Auth::user()->roleId == 10)
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#DeleteModal" id="modalDelete"
                                                            onclick='deleteData("{{ $data[$i][$j]->id }}", "{{ $data[$i][$j]->gudangRequest }}")'
                                                            class='btn btn-danger delete'><i class="fas fa-trash"
                                                                style="font-size: 14px"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor
                                    @endfor
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
            <form id="deleteForm" method="post">
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
                        <input type="hidden" name="gudangId" id="gudangId">
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
        $(document).ready(function() {
            $('#example2').DataTable();
        });

        function deleteData(id, gudangReq) {
            switch (gudangReq) {
                case "Gudang Rajut Masuk":
                    var url = '{{ route('GRajut.kembali.delete') }}';
                    document.getElementById("gudangId").name = "gdRajutMId";
                    break;
                case "Gudang Compact Masuk":
                    var url = '{{ route('GCompact.kembali.delete') }}';
                    document.getElementById("gudangId").name = "gdCompactMId";
                    break;
                case "Gudang Inspeksi Masuk":
                    var url = '{{ route('GInspeksi.kembali.delete') }}';
                    document.getElementById("gudangId").name = "gdInspeksiMId";
                    break;
            }

            var id = id;
            console.log(id);
            $('#gudangId').val(id);
            $("#deleteForm").attr('action', url);
        }

        function formSubmit() {
            $("#deleteForm").submit();
        }
    </script>
@endpush
