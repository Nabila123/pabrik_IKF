 
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
                    <h1>Purchase Order Detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Purchase Order</li>
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
                            <span class="tglPurchase">Tanggal Pengajuan</span>
                            <table class="table" style="margin-bottom: 35px">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Kode Purchase</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>                               
                            </table>

                            <table id="example2" class="table table-bordered dataTables_scrollBody text-center" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 30%;">Deskripsi</th>
                                        <th style="width: 5%;">Qty</th>
                                        <th style="width: 5%;">Satuan</th>
                                        <th style="width: 5%;">Harga</th>
                                        <th style="width: 5%;">Total</th>
                                        <th style="width: 25%;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="text-left">Internet Explorer 95+</td>
                                        <td>4</td>
                                        <td>Pcs</td>
                                        <td>40000</td>
                                        <td>160.000</td>
                                        <td class="text-left">Barang Digunakan Untuk Lain Sebagainya</td>
                                    </tr>                                    
                                </tbody>                                
                            </table>

                            <table class="table text-center" style="margin-top: 35px">
                                <thead>
                                    <tr>
                                        <th colspan="2" style="width: 60%">Approved By</th>
                                        <th rowspan="2" style="vertical-align: middle;">PPIC</th>
                                    </tr>
                                    <tr>
                                        <th>K. Dept Prod</th>
                                        <th>K. Dept PO</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <tr>
                                        <td>Nama Kepala Departemen Produksi <br> <span style="font-size: 12px; color:green"> Approve At 16 Jan 2022 </span></td>    
                                        <td>Nama Kepala Departemen Purchase Order <br> <span style="font-size: 12px; color:green"> Approve At 16 Jan 2022 </span></td>    
                                        <td>Nama Production Planning and Inventory Control</td>    
                                    </tr>    
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