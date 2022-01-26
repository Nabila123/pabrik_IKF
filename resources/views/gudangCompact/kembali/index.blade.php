 
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
                    <h1>Gudang Compact Kembali</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Compact Kembali</li>
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
                                        <th class="textAlign">Nama Barang</th>
                                        <th class="textAlign">Satuan</th>
                                        <th class="textAlign">Tanggal </th>
                                        <th class="textAlign">Admin G. Bahan Baku</th>
                                        <th class="textAlign">Status Keterangan</th>
                                        <th class="textAlign">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @foreach ($gCompactKembali as $kembali)
                                        <tr>
                                            <td> {{ $kembali->material->nama }} </td>
                                            <td> {{ $kembali->material->satuan }} </td>
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
                                                <a href="{{ route('GCompact.kembali.detail', [$kembali->id]) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
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
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        $(document).ready( function () {
            $('#example2').DataTable( {
                "responsive": true,
            });
        });
    </script>
@endpush