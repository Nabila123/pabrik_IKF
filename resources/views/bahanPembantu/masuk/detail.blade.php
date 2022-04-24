 
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
                    <h1>Gudang Bahan Pembantu</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Gudang Bahan Pembantu</li>
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="card card-info">
                                        <div class="card-body">
                                            <div class="row">          
                                                <div class="col-12 right">
                                                    <table id="materialPO" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="textAlign">Bahan</th>
                                                                <th class="textAlign">Nomor PO</th>
                                                                @if ($data->gudangRequest != "Gudang Rajut")
                                                                    <th class="textAlign">Gramasi</th>
                                                                    <th class="textAlign">Diamater</th>
                                                                    <th class="textAlign">Berat</th>
                                                                @endif
                                                                <th class="textAlign">Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="textAlign">
                                                            @foreach ($dataDetail as $detail)
                                                                <tr>
                                                                    <td>{{ $detail->material->nama }}</td>
                                                                    <td>{{ $detail->purchase->kode }}</td>
                                                                    @if ($data->gudangRequest != "Gudang Rajut")
                                                                        <td>{{ $detail->gramasi }}</td>
                                                                        <td>{{ $detail->diameter }}</td>
                                                                        <td>{{ $detail->berat }}</td>
                                                                    @endif
                                                                    <td>{{ $detail->qty }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>                                                                                       
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <a href="{{route('GBahanPembantu/supply')}}"" id="Simpan" style="float: right" class='btn btn-info btn-flat-right'>Kembali</a>
                                    </div>
                                </div>
                            </div>
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
            $('#example2').DataTable();
        });

        $('#kodePurchase').select2({
            theme: 'bootstrap4'
        });

    </script>
@endpush