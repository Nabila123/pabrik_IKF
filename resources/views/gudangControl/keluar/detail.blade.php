 
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
                    <h1>Detail Batil Pemindahan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Batil Pemindahan</li>
                        <li class="breadcrumb-item active">Detail</li>
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
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">No </th>
                                        <th class="textAlign" style="vertical-align: middle;">Nomor PO</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                        <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                        <th class="textAlign" style="vertical-align: middle;">Keterangan Batil</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    <?php $no = 1; ?>
                                    @for ($i = 0; $i < count($gdKeluarBatil); $i++)                                        
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $gdKeluarBatil[$i]['purchase'] }}</td>
                                            <td>{{ strtoupper($gdKeluarBatil[$i]['jenisBaju']) }}</td>
                                            <td>{{ $gdKeluarBatil[$i]['ukuranBaju'] }}</td>
                                            <td>
                                                @if ($gdKeluarBatil[$i]['statusControl'] == 1)
                                                    {{ $gdKeluarBatil[$i]['batilName'] }}
                                                @else
                                                    <i class="fas fa-xmark"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
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
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush