 
@extends('layouts.app')

@push('page_css') 
    <style>
        .tglPurchase{
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
                    <h1>Purchase Invoice Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Purchase Invoice</li>
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
                            <span class="tglPurchase"><b>Tanggal Jatuh Tempo :</b> {{ date('d F Y', strtotime($invoice->paymentDue))}}</span>
                            <table class="table" style="margin-bottom: 35px">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Kode Purchase : {{ $invoice->purchase->kode }}</th>
                                    </tr>
                                    <tr>
                                        <th>Note : {{ $invoice->purchase->note }} </th>
                                    </tr>
                                    <tr>
                                        <th>Total : {{ rupiah($invoice->total) }} </th>
                                    </tr>
                                </thead>                               
                            </table>

                            <table id="example2" class="table table-bordered dataTables_scrollBody text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 30%;">Nama Bahan</th>
                                        <th style="width: 5%;">Diameter</th>
                                        <th style="width: 5%;">Gramasi</th>
                                        <th style="width: 5%;">Brutto</th>
                                        <th style="width: 5%;">Netto</th>
                                        <th style="width: 5%;">Selisih </th>
                                        <th style="width: 5%;">Satuan</th>
                                        <th style="width: 5%;">Harga</th>
                                        <th style="width: 25%;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 0; ?>   
                                    @foreach ($material['material'] as $key => $detail)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $detail['nama'] }}</td>
                                            <td>{{ $detail['diameter'] }}</td>
                                            <td>{{ $detail['gramasi'] }}</td>
                                            <td>{{ $detail['brutto'] }}</td>
                                            <td>{{ $detail['netto'] }}</td>
                                            <td>{{ $detail['tarra'] }}</td>
                                            <td>{{ $detail['satuan'] }}</td>
                                            <td>{{ rupiah($detail['harga']) }}</td>
                                            <td>{{ $detail['note'] }}</td>
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
            $('#example2').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                pageLength: 50
            });
        });
    </script>
@endpush