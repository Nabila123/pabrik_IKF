 
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
                    <h1>Pemindahan Baju</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Jahit Pemindahan</li>
                        <li class="breadcrumb-item active">Create</li>
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
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data"> 
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                   
                                <div class="row">
                                    <div class="col-12">
                                        <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th class="textAlign" style="vertical-align: middle;">Tanggal Request </th>
                                                    <th class="textAlign" style="vertical-align: middle;">Kode Purchase</th>
                                                    <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                                    <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                                </tr>
                                            </thead>
                                            <tbody class="textAlign">
                                                @for ($i = 0; $i < count($pemindahan); $i++)                                                    
                                                    <tr>
                                                        <td>{{ $pemindahan[$i]['tanggal'] }} <input type="hidden" name="gdBajuStokOpnameId[]" id="gdBajuStokOpnameId" value="{{ $pemindahan[$i]['gdBajuStokOpnameId'] }}"></td>
                                                        <td>{{ $pemindahan[$i]['purchase'] }} <input type="hidden" name="purchaseId[]" id="purchaseId" value="{{ $pemindahan[$i]['purchaseId'] }}"> </td>
                                                        <td>{{ strtoupper($pemindahan[$i]['jenisBaju']) }} <input type="hidden" name="jenisBaju[]" id="jenisBaju" value="{{ $pemindahan[$i]['jenisBaju'] }}"> </td>
                                                        <td>{{ $pemindahan[$i]['ukuranBaju'] }} <input type="hidden" name="ukuranBaju[]" id="ukuranBaju" value="{{ $pemindahan[$i]['ukuranBaju'] }}"> </td>                                                        
                                                    </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 mt-5">
                                        <div class="form-group">
                                            <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Pindahkan Baju</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush