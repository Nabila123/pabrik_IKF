 
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
                    <h1>Pegawai</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pegawai</li>
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
                        @if (\Auth::user()->roleId == 1)        
                            <div class="card-header">
                                <div style="margin:10px; text-align: left;">
                                    <a href="{{ route('pegawai.create') }}" class='btn btn-success btn-flat-left'><i class="fas fa-plus" style="font-size: 15px"></i> Tambah Data</a>
                                </div>    
                            </div>
                        @endif               
                        <div class="card-body">                            
                            <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="textAlign" style="vertical-align: middle;">Nomor</th>
                                        <th class="textAlign" style="vertical-align: middle;">Nama Pegawai</th>
                                        <th class="textAlign" style="vertical-align: middle;">Bagian</th>
                                        <th class="textAlign" style="vertical-align: middle;">Jumlah Pegawai</th>
                                        <th class="textAlign" style="vertical-align: middle;">action</th>
                                    </tr>
                                </thead>
                                <tbody class="textAlign">
                                    @php $no = 1; @endphp
                                    @foreach ($pegawais as $pegawai)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $pegawai->nama }}</td>
                                            <td>{{ strtoupper($pegawai->kodeBagian) }}</td>
                                            <td>{{ $pegawai->jumlah }} Orang </td>
                                            <td>
                                                <a href="{{ route('pegawai.detail', $pegawai->kodeBagian) }}" class='btn btn-warning'><i class="fas fa-list-ul" style="font-size: 14px"></i></a>
                                                {{--  <a href="{{ route('pegawai.edit', $pegawai->kodeBagian) }}" class='btn btn-success'><i class="fas fa-pencil-alt" style="font-size: 14px"></i></a>  --}}
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
                "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]]
            });
        });
    </script>
@endpush