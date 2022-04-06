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
                     <h1>Purchase Invoice</h1>
                 </div>
                 <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                         <li class="breadcrumb-item"><a href="#">Home</a></li>
                         <li class="breadcrumb-item active">Purchase Invoice</li>
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
                         <div class="card-header">
                             <h3 class="card-title">
                                 <a href="{{ route('adminPO.poInvoice.create') }}"
                                     class='btn btn-info btn-flat-right'>Tambah Data</a>
                             </h3>
                         </div>
                         <div class="card-body">
                             <table id="example2" class="table table-bordered dataTables_scrollBody" style="width: 100%">
                                 <thead>
                                     <tr>
                                         <th class="textAlign" style="vertical-align: middle;">Kode Purchase</th>
                                         <th class="textAlign" style="vertical-align: middle;">Total </th>
                                         <th class="textAlign" style="vertical-align: middle;">Tanggal Jatuh Tempo</th>
                                         <th class="textAlign" style="vertical-align: middle; width:11%;">action</th>
                                     </tr>
                                 </thead>
                                 <tbody class="textAlign">
                                     @foreach ($invoice as $val)
                                         <tr>
                                             <td>{{ $val->purchase->kode }}</td>
                                             <td>{{ rupiah($val->total) }}</td>
                                             <td>{{ $val->paymentDue }}</td>
                                             <td>
                                                 <a href="{{ route('adminPO.poInvoice.detail', $val->id) }}"
                                                     class='btn btn-warning'><i class="fas fa-list-ul"
                                                         style="font-size: 14px"></i></a>
                                                 <a href="{{ route('adminPO.poInvoice.update', $val->id) }}"
                                                     class='btn btn-success'><i class="fas fa-pencil-alt"
                                                         style="font-size: 14px"></i></a>
                                                 <button type="button" data-toggle="modal" invoiceId='{{ $val->id }}'
                                                     data-target="#DeleteModal" id="modalDelete"
                                                     onclick='deleteData("{{ $val->id }}")'
                                                     class='btn btn-danger delete mt-1'><i class="fas fa-trash"
                                                         style="font-size: 14px"></i></a>
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

     <div id="DeleteModal" class="modal fade">
         <div class="modal-dialog ">
             <!-- Modal content-->
             <form action="{{ route('adminPO.poInvoice.delete') }}" id="deleteForm" method="post">
                 <div class="modal-content">
                     <div class="modal-header bg-danger">
                         <h4 class="modal-title">DELETE CONFIRMATION</h4>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                     </div>
                     <div class="modal-body">
                         {{ csrf_field() }}
                         {{ method_field('DELETE') }}
                         <p>Anda yakin ingin menghapus data ini ?</p>
                         <input type="hidden" name="invoiceId" id="invoiceId">
                     </div>
                     <div class="modal-footer">
                         <center>
                             <button type="button" class="btn btn-success" data-dismiss="modal">Batal</button>
                             <button type="submit" name="" class="btn btn-danger" data-dismiss="modal"
                                 onclick="formSubmit()">Ya, Hapus</button>
                         </center>
                     </div>
                 </div>
             </form>
         </div>
     </div>
 @endsection


 @push('page_scripts')
     <script type="text/javascript">
         function deleteData(id) {
             var id = id;
             var url = '{{ route('adminPO.poInvoice.delete') }}';
             // url = url.replace(':id', id);
             console.log(id);
             $('#invoiceId').val(id);
             $("#deleteForm").attr('action', url);
         }

         function formSubmit() {
             $("#deleteForm").submit();
         }

         $(document).ready(function() {
             $('#example2').DataTable({
                 "responsive": true,
             });

             $(document).on("click", "button.approveKaKaDivPO", function(e) {
                 e.preventDefault();
                 var purchaseId = $(this).attr('purchaseId');

                 $.ajax({
                     type: "get",
                     url: '{{ url('adminPO/Request/approve') }}',
                     data: {
                         'purchaseId': purchaseId,
                         'approve': 'isKaDivPO',
                         'approveAt': 'isKaDivPOAt',
                     },
                     success: function(response) {
                         if (response == 1) {
                             location.reload();
                         }
                     }
                 })
             });

             $(document).on("click", "button.approveKaDivFin", function(e) {
                 e.preventDefault();
                 var purchaseId = $(this).attr('purchaseId');

                 $.ajax({
                     type: "get",
                     url: '{{ url('adminPO/Request/approve') }}',
                     data: {
                         'purchaseId': purchaseId,
                         'approve': 'isKaDivFin',
                         'approveAt': 'isKaDivFinAt',
                     },
                     success: function(response) {
                         if (response == 1) {
                             location.reload();
                         }
                     }
                 })
             });
         });
     </script>
 @endpush
