 @extends('layouts.app')

 @push('page_css')
     <style>
         .tglPurchase {
             float: right;
             margin-bottom: 10px;
         }

         .dataTables_scrollBody::-webkit-scrollbar-track {
             -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
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
                     <h1>Rekapan Purchase</h1>
                 </div>
                 <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                         <li class="breadcrumb-item"><a href="#">Home</a></li>
                         <li class="breadcrumb-item active">Rekapan Purchase</li>
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
                             <table id="example2" class="table table-bordered dataTables_scrollBody text-center"
                                 style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="vertical-align: middle; width: 5%;">No</th>
                                         <th style="vertical-align: middle; width: 10%;">Nomor PO</th>
                                         <th style="vertical-align: middle; width: 30%;">Deskripsi</th>
                                         <th style="vertical-align: middle; width: 15%;">Qty</th>
                                         <th style="vertical-align: middle; width: 10%;">Satuan</th>
                                         <th style="vertical-align: middle; width: 15%;">Proses Order</th>
                                         <th style="vertical-align: middle; width: 15%;">Keterangan</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @php $no = 1; @endphp
                                     @foreach ($datas as $data)
                                         <tr>
                                             <td style="vertical-align: middle; border-top-width: 2px; border-bottom-width: 2px">{{ $no++ }}</td>
                                             <td style="vertical-align: middle; border-top-width: 2px; border-bottom-width: 2px">{{ $data->kode }}</td>
                                             <td colspan="3" style="vertical-align: middle; border-top-width: 2px; border-bottom-width: 2px">
                                                 @foreach ($data->purchaseDetail as $detail)
                                                     <table class="table table-bordered text-center">
                                                         <tr>
                                                             <td style="width: 30%;">{{ $detail->material->nama }}</td>
                                                             <td style="width: 15%;">{{ $detail->qty }}</td>
                                                             <td style="width: 10%;">{{ $detail->unit }}</td>
                                                         </tr>
                                                     </table>
                                                 @endforeach
                                             </td>

                                             <td style="vertical-align: middle; border-top-width: 2px; border-bottom-width: 2px">
                                                 @if ($data->prosesOrder == true)
                                                     Sudah DiProses <br>
                                                     <span style="color: green; font-size: 10px">
                                                         {{ date('d F Y', strtotime($data->prosesOrderAt)) }} </span>
                                                 @else
                                                     <span style="color: rgb(253, 5, 5); font-size: 12px">Menunggu Proses
                                                         Order</span>
                                                 @endif
                                             </td>
                                             <td style="vertical-align: middle; border-top-width: 2px; border-bottom-width: 2px">
                                                 @if ($data->barangDatang == true)
                                                     Barang Datang <br>
                                                     <span style="color: green; font-size: 10px"> At
                                                         {{ date('d F Y', strtotime($data->barangDatangAt)) }} </span>
                                                 @else
                                                     <span style="color: rgb(253, 5, 5); font-size: 12px">Barang Belum
                                                         Datang</span>
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
 @endsection


 @push('page_scripts')
     <script type="text/javascript">
         $(document).ready(function() {
             $('#example2').DataTable();
         });
     </script>
 @endpush
