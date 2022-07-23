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
                             <span class="tglPurchase">Tanggal Pengajuan :
                                 {{ date('d F Y', strtotime($purchase->tanggal)) }}</span>
                             <table class="table" style="margin-bottom: 35px">
                                 <thead>
                                     <tr>
                                         <th style="width: 40%">Nomor PO : {{ $purchase->kode }}</th>
                                     </tr>
                                     <tr>
                                         <th style="width: 40%">Suplier : {{ $purchase->suplierName }}</th>
                                     </tr>
                                     <tr>
                                         <th>Note : {{ $purchase->note }}</th>
                                     </tr>
                                 </thead>
                             </table>

                             <table id="example2"
                                 class="table table-bordered table-responsive dataTables_scrollBody text-center"
                                 style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th style="width: 5%;">No</th>
                                         <th style="width: 30%;">Deskripsi</th>
                                         <th style="width: 5%;">Qty</th>
                                         <th style="width: 5%;">Satuan</th>
                                         @if (isset($datang['datang']))
                                             <th style="width: 5%;">Diameter</th>
                                             <th style="width: 5%;">Gramasi</th>
                                             <th style="width: 5%;">Brutto</th>
                                             <th style="width: 5%;">Netto</th>
                                             <th style="width: 5%;">Selisih </th>
                                         @endif
                                         <th style="width: 15%;">Harga</th>
                                         <th style="width: 15%;">Total</th>
                                         <th style="width: 25%;">Keterangan</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <?php $no = 0; ?>
                                     @foreach ($purchaseDetails as $detail)
                                         <?php $no++; ?>
                                         <tr>
                                             <td style="vertical-align: middle;">{{ $no }}</td>
                                             <td style="vertical-align: middle;" class="text-left">
                                                 {{ $detail->material->nama }}</td>
                                             <td style="vertical-align: middle;">{{ $detail->qty }}</td>
                                             <td style="vertical-align: middle;">{{ $detail->unit }}</td>
                                             @if (isset($datang['datang']))
                                                 <td colspan="5" style="vertical-align: middle;">
                                                     @for ($i = 0; $i < count($datang) - 1; $i++)
                                                         <table class="table table-bordered text-center">
                                                             <tr>
                                                                 @if ($datang[$i]['materialId'] == $detail->materialId)
                                                                     <td>{{ $datang[$i]['diameter'] }}</td>
                                                                     <td>{{ $datang[$i]['gramasi'] }}</td>
                                                                     <td>{{ $datang[$i]['brutto'] }}</td>
                                                                     <td>{{ $datang[$i]['netto'] }}</td>
                                                                     <td>{{ $datang[$i]['tarra'] }}</td>
                                                                 @endif
                                                             </tr>
                                                         </table>
                                                     @endfor
                                                 </td>
                                             @endif
                                             <td style="vertical-align: middle;">{{ rupiah($detail->unitPrice) }}</td>
                                             <td style="vertical-align: middle;">{{ rupiah($detail->amount) }}</td>
                                             <td style="vertical-align: middle;" class="text-left">{{ $detail->remark }}
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>

                             <table class="table text-center" style="margin-top: 35px">
                                 <thead>
                                     <tr>
                                         <th colspan="2" style="width: 60%">Approved By</th>
                                         <th rowspan="2" style="vertical-align: middle;">Manager PO</th>
                                     </tr>
                                     <tr>
                                         <th>Manager PO</th>
                                         <th>Kepala Div Fin</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <tr>
                                         <td>
                                             @if ($purchase->isKaDivPO != 0)
                                                 <span style="font-size: 12px; color:green">
                                                     Approve At {{ $purchase->isKaDivPOAt }}
                                                 </span>
                                             @else
                                                 <br>
                                                 <span style="font-size: 12px; color:rgb(250, 1, 1)">
                                                     Dalam Proses Approve
                                                 </span>
                                             @endif

                                         </td>
                                         <td>
                                             @if ($purchase->isKaDivFin != 0)
                                                 <span style="font-size: 12px; color:green">
                                                     Approve At {{ $purchase->isKaDivFinAt }}
                                                 </span>
                                             @else
                                                 <br>
                                                 <span style="font-size: 12px; color:rgb(250, 1, 1)">
                                                     Dalam Proses Approve
                                                 </span>
                                             @endif

                                         </td>
                                         <td>
                                             {{ $purchase->user->nama }}
                                         </td>
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
         $(document).ready(function() {
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
