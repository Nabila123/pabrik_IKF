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
                    <h1>Request Keluar Gudang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Request Keluar Gudang</li>
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
                            @if (\Auth::user()->roleId == 1 ||
                                \Auth::user()->roleId == 4 ||
                                \Auth::user()->roleId == 7 ||
                                \Auth::user()->roleId == 10)
                                <div style="margin:10px; text-align: right;">
                                    <a href="{{ route('bahan_baku.keluar.create') }}"
                                        class='btn btn-success btn-flat-right'><i class="fas fa-plus"
                                            style="font-size: 15px"></i> Request Keluar</a>
                                </div>
                            @endif
                            <table id="example2" class="table table-bordered dataTables_scrollBody textAlign"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; width: 5%;">Kode Gudang</th>
                                        <th style="vertical-align: middle; width: 10%;">Gudang Request</th>
                                        <th style="vertical-align: middle; width:10%;">Tanggal </th>
                                        <th style="vertical-align: middle; width:20%;">Status </th>
                                        <th style="vertical-align: middle; width:10%;">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < count($data); $i++)
                                        @for ($j = 0; $j < count($data[$i]); $j++)
                                            <tr>
                                                <td>
                                                    @if ($i == 2)
                                                    {{ $data[$i][$j]->foreign }} <- {{ $data[$i][$j]->id }} @else
                                                            {{ $data[$i][$j]->id }} @endif
                                                </td>
                                                <td>{{ $data[$i][$j]->gudangRequest }}</td>
                                                <td>{{ date('d F Y', strtotime($data[$i][$j]->created_at)) }}</td>
                                                <td>
                                                    @if ($data[$i][$j]->statusDiterima == 0)
                                                        <span style="color: rgb(221, 3, 3); font-size: 13px"> Dalam Proses
                                                            Penyerahan</span>
                                                    @elseif ($data[$i][$j]->statusDiterima == 1)
                                                        <span style="color: green; font-size: 13px">Barang Sudah Diterima
                                                            Oleh {{ $data[$i][$j]->gudangRequest }}</span>
                                                    @elseif ($data[$i][$j]->statusDiterima == 2)
                                                        <span style="color: green; font-size: 13px">Barang Sedang
                                                            Dipindahkan ke Gudang Compact</span>
                                                    @elseif ($data[$i][$j]->statusDiterima == 3)
                                                        <span style="color: green; font-size: 13px">Barang Sudah Diterima
                                                            Oleh Gudang Compact</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('bahan_baku.keluar.detail', ['id' => $data[$i][$j]->id, 'gudangRequest' => $data[$i][$j]->gudangRequest]) }}"
                                                        class='btn btn-warning'><i class="fas fa-list-ul"
                                                            style="font-size: 14px"></i></a>

                                                    @if (\Auth::user()->roleId == 1 ||
                                                        \Auth::user()->roleId == 4 ||
                                                        \Auth::user()->roleId == 7 ||
                                                        \Auth::user()->roleId == 10)
                                                        <a href="{{ route('bahan_baku.keluar.update', ['id' => $data[$i][$j]->id, 'gudangRequest' => $data[$i][$j]->gudangRequest]) }}"
                                                            class='btn btn-success'><i class="fas fa-pencil-alt"
                                                                style="font-size: 14px"></i></a>
                                                    @endif

                                                    @if (\Auth::user()->roleId == 1 ||
                                                        \Auth::user()->roleId == 4 ||
                                                        \Auth::user()->roleId == 7 ||
                                                        \Auth::user()->roleId == 10)
                                                        @if (!isset($data[$i][$j]->cuciDelete))
                                                            <button type="button" data-toggle="modal"
                                                                dataId='{{ $data[$i][$j]->id }}'
                                                                dataRequest="{{ $data[$i][$j]->gudangRequest }}"
                                                                data-target="#DeleteModal" id="modalDelete"
                                                                class='btn btn-danger delete'><i class="fas fa-trash"
                                                                    style="font-size: 14px"></i></button>
                                                        @endif
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
            <form action="{{ route('bahan_baku.keluar.delete') }}" id="deleteForm" method="post">
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
        $(document).ready(function() {
            $('#example2').DataTable();

            $('.delete').click(function() {
                var id = $(this).attr('dataId');
                var gudangRequest = $(this).attr('dataRequest');
                $('#gudangRequestId').val(id);
                $('#gudangRequestName').val(gudangRequest);
            });

            $('.submitDelete').click(function() {
                $("#deleteForm").submit();
            })

            function hapusData(id) {
                var id = id;
                var url = '{{ route('bahan_baku.keluar.delete') }}';
                // url = url.replace(':id', id);
                console.log(id);
                $('#gudangId').val(id);
                $('#gudangId').val(id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit() {
                $("#deleteForm").submit();
            }
        });
    </script>
@endpush
