 @extends('layouts.app')

 @push('page_css')
     <style>
         .textAlign {
             vertical-align: middle;
             text-align: center;
             font-size: 15px;
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
                     <h4>Stok Barang</h4>
                 </div>
                 <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                         <li class="breadcrumb-item active"><a href="#">Home</a></li>
                     </ol>
                 </div>
             </div>
         </div>
     </section>

     <section class="content">
         <div class="container-fluid">
             <div class="row">
                 @foreach ($dataStok as $stok)
                     <div class="col-12 col-sm-12 col-md-4">
                         <div class="info-box mb-4">
                             <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list-alt"></i></span>
                             <div class="info-box-content">
                                 <span class="info-box-text">{{ $stok['nama'] }}</span>
                                 <span class="info-box-number"> {{ $stok['qty'] }} <span
                                         style="float:right; font-weight:bold;">{{ $stok['satuan'] }}</span> </span>
                             </div>
                         </div>
                     </div>
                 @endforeach
             </div>
             <div class="row">
                 <div class="col-12 col-sm-12 col-md-4">
                     <h4>Request Keluar</h4>
                     <div class="info-box mb-4">
                         <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>

                         <div class="info-box-content">
                             <span class="info-box-number">{{ $dataKeluar }}</span>
                         </div>
                     </div>
                 </div>

                 <div class="clearfix hidden-md-up"></div>

                 <div class="col-12 col-sm-12 col-md-4">
                     <h4>Request Masuk</h4>
                     <div class="info-box mb-4">
                         <span class="info-box-icon bg-success elevation-1"><i class="fas fa-people-carry"></i></span>

                         <div class="info-box-content">
                             <span class="info-box-number">{{ $dataMasuk }}</span>
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
