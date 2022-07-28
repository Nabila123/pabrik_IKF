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
                    <h1>Gudang Bahan Baku</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Bahan Baku</li>
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
                            @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 10)
                                <div style="margin:10px; text-align: right;">
                                    <a href="{{ route('bahan_baku.supply.create') }}" class='btn btn-success btn-flat-right'><i class="fas fa-plus" style="font-size: 15px"></i> Barang Datang</a>
                                </div>
                            @endif

                            <table id="example2" class="table table-bordered textAlign dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle;">Nomor PO</th>
                                        <th style="vertical-align: middle;">Nama Supplier</th>
                                        <!-- <th style="vertical-align: middle;">Total</th> -->
                                        <th style="vertical-align: middle;">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key=>$value)
                                        <tr>
                                            <td>{{$value->purchase->kode}}</td>
                                            <td>{{$value->purchase->suplierName}}</td>
                                            <!-- <td>{{$value->total}}</td> -->
                                            <td>
                                                <a href="{{ route('bahan_baku.supply.detail',['id'=>$value->purchaseId])}}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                
                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 10)
                                                    <a href="{{ route('bahan_baku.supply.update',['id'=>$value->purchaseId])}}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>
                                                @endif

                                                @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 4 || \Auth::user()->roleId == 7 || \Auth::user()->roleId == 10)
                                                    <button type="button" data-toggle="modal" dataId='{{ $value->barangDatangId }}' dataPurchaseId='{{ $value->purchaseId }}' data-target="#DeleteModal" id="modalDelete" class='btn btn-danger delete'><i class="fas fa-trash" style="font-size: 14px"></i></button>
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
            <form action="{{ route('bahan_baku.supply.delete') }}" id="deleteForm" method="post" >
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
                        <input type="hidden" name="barangDatangId" id="barangDatangId">
                        <input type="hidden" name="purchaseId" id="purchaseId">
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
               var purchaseId = $(this).attr('dataPurchaseId');
               
               $('#barangDatangId').val(id);
               $('#purchaseId').val(purchaseId);
            });

            $('.submitDelete').click(function(){
                $("#deleteForm").submit();
            })

            function hapusData(id)
            {
                var id = id;
                var url = '{{ route('bahan_baku.supply.delete') }}';
                // url = url.replace(':id', id);
                console.log(id);
                $('#barangDatangId').val(id);
                $("#deleteForm").attr('action', url);
            }

            function formSubmit()
            {
                $("#deleteForm").submit();
            }
        });
    </script>
@endpush