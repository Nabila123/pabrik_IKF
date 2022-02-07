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
                        <div class="card-body">
                            <div style="margin:10px; text-align: right;">
                                <a href="{{ route('bahan_baku.keluar.create') }}" class='btn btn-success btn-flat-right'><i class="fas fa-plus" style="font-size: 15px"></i> Request Keluar</a>
                            </div>
                            <table id="example2" class="table table-bordered table-responsive dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle; width: 20%;">Gudang Request</th>
                                        <th style="vertical-align: middle; width: 30%;">Material</th>
                                        <th style="vertical-align: middle; width:10%;">Tanggal </th>
                                        <th style="vertical-align: middle; width:20%;">Status </th>
                                        <th style="vertical-align: middle; width:10%;">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key=>$value)
                                        <tr>
                                            <td>{{$value->gudangRequest}}</td>
                                            <td>{{$value->material->nama}}</td>
                                            <td>{{$value->tanggal}}</td>
                                            <td>
                                                @if ($value->statusDiterima == 0)
                                                <span style="color: rgb(221, 3, 3); font-size: 13px"> Dalam Proses Penyerahan</span>
                                               
                                                @elseif ($value->statusDiterima == 1)
                                                    <span style="color: green; font-size: 13px">Barang Sudah Diterima Oleh {{$value->gudangRequest}}</span>
                                                @elseif ($value->statusDiterima == 2)
                                                    <span style="color: green; font-size: 13px">Barang Sedang Dipindahkan ke Gudang Compact</span>
                                                @elseif ($value->statusDiterima == 3)
                                                    <span style="color: green; font-size: 13px">Barang Sudah Diterima Oleh Gudang Compact</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('bahan_baku.keluar.detail',['id'=>$value->id])}}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                <button type="button" data-toggle="modal" dataId='{{ $value->id }}' data-target="#DeleteModal" id="modalDelete" class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></button> 
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
        $(document).ready( function () {
            $('#example2').DataTable();

            $('.delete').click(function () {
               var id = $(this).attr('dataId');
               $('#gudangId').val(id);
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
                $("#deleteForm").attr('action', url);
            }

            function formSubmit()
            {
                $("#deleteForm").submit();
            }
        });
    </script>
@endpush