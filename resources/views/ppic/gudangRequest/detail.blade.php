 
@extends('layouts.app')

@push('page_css') 
    <style>
        .textAlign {
            vertical-align: middle; 
            text-align: center;
            font-size: 15px;
        }

        .tglGudang{
            float: right;
            margin-bottom: 10px;
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
                    <h1>Gudang Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Gudang Request</li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <span class="tglGudang">Tanggal Permintaan : {{ $ppicRequest->tanggal }}</span>
                            <table class="table" style="margin-bottom: 35px">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Gudang Request : {{ $ppicRequest->gudangRequest }}</th>
                                    </tr>
                                </thead>                               
                            </table>

                            <table id="example2" class="table table-bordered dataTables_scrollBody text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th >No</th>
                                        <th >Bahan Baku</th>
                                        <th >Gramasi</th>
                                        <th >Diameter</th>
                                        <th >Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>
                                    @foreach ($ppicRequestDetail as $detail)
                                    <?php $no++; ?>
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $detail->material->nama }}</td>
                                            <td>{{ $detail->gramasi }}</td>
                                            <td>{{ $detail->diameter }}</td>
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
    </section>
@endsection


@push('page_scripts') 
    <script type="text/javascript">
        $(document).ready( function () {
            $('#example2').DataTable();
        });
    </script>
@endpush