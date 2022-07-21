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

         tr th {
             max-width: 100%;
             white-space: nowrap;
         }

         tr td {
             max-width: 100%;
             white-space: nowrap;
         }
     </style>
 @endpush

 @section('content')
     <section class="content-header">
         <div class="container-fluid">
             <div class="row mb-2">
                 <div class="col-sm-6">
                     <h1>Gudang Jahit Operator</h1>
                 </div>
                 <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                         <li class="breadcrumb-item"><a href="#">Home</a></li>
                         <li class="breadcrumb-item active">Gudang Jahit Operator</li>
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
                             <ul class="nav nav-pills">
                                 <li class="nav-item"><a class="nav-link active" href="#OperatorLink"
                                         data-toggle="tab">Operator</a></li>
                                 {{-- <li class="nav-item"><a class="nav-link" href="#BasisLink" data-toggle="tab">Basis</a></li> --}}
                                 <li class="nav-item"><a class="nav-link" href="#RekapanLink" data-toggle="tab">Rekapan</a>
                                 </li>
                                 <li class="nav-item"><a class="nav-link" href="#PindahLink" data-toggle="tab">Pindah Ke
                                         Batil</a></li>
                                 <li class="nav-item"><a class="nav-link" href="#RekapanAllLink" data-toggle="tab">Rekapan
                                         Semua Data</a></li>
                             </ul>
                         </div>
                         <div class="card-body">
                             <div class="tab-content">
                                 <div class="active tab-pane" id="OperatorLink">
                                     @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13 || \Auth::user()->roleId == 32)
                                         <h3 class="card-title mb-4" style="width: 100%">
                                             <a href="{{ route('GJahit.operator.create') }}"
                                                 class='btn btn-info btn-flat-right'>Ambil Barang</a>
                                         </h3>
                                     @endif
                                     <table id="operator" class="table table-bordered dataTables_scrollBody"
                                         style="width: 100%">
                                         <thead>
                                             <tr>
                                                 <th class="textAlign" style="vertical-align: middle;">Tanggal Request </th>
                                                 <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Jumlah (Dz)</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Action</th>
                                             </tr>
                                         </thead>
                                         <tbody class="textAlign">
                                             @foreach ($operatorRequest as $detail)
                                                 <tr>
                                                     <td>{{ date('d F Y', strtotime($detail->created_at)) }}</td>
                                                     <td>{{ strtoupper($detail->jenisBaju) }}</td>
                                                     <td>{{ $detail->ukuranBaju }}</td>
                                                     <td>
                                                         {{ $detail->totalDz }}
                                                         @if (isset($detail->sisa))
                                                             / {{ $detail->sisa }}
                                                         @endif
                                                     </td>

                                                     <td>
                                                         <a href="{{ route('GJahit.operator.detail', [$detail->jenisBaju, $detail->ukuranBaju]) }}"
                                                             class='btn btn-warning'><i class="fas fa-list-ul"
                                                                 style="font-size: 14px"></i></a>

                                                         @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13)
                                                             <a href="{{ route('GJahit.operator.update', date('Y-m-d', strtotime($detail->created_at))) }}"
                                                                 class='btn btn-success'><i class="fas fa-pencil-alt"
                                                                     style="font-size: 14px"></i></a>
                                                         @endif
                                                         @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8)
                                                             @if (count($jahitBasis) == 0)
                                                                 <button type="button" data-toggle="modal"
                                                                     requestId='{{ $detail->id }}'
                                                                     data-target="#DeleteModal" id="modalDelete"
                                                                     onclick='deleteData("{{ $detail->jenisBaju }}", "{{ $detail->ukuranBaju }}", "operator")'
                                                                     class='btn btn-danger delete'><i class="fas fa-trash"
                                                                         style="font-size: 14px"></i></a>
                                                                 @else
                                                                     <button type="button" class="btn btn-danger disabled"
                                                                         style="width:40px;"><span
                                                                             class="fa fa-trash"></span></button>
                                                             @endif
                                                         @endif
                                                     </td>
                                                 </tr>
                                             @endforeach
                                         </tbody>
                                     </table>
                                 </div>
                                 <div class="tab-pane" id="BasisLink">
                                     @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13 || \Auth::user()->roleId == 32)
                                         <h3 class="card-title mb-4" style="width: 100%">
                                             <a href="{{ route('GJahit.basis.create') }}"
                                                 class='btn btn-info btn-flat-right'>Tambah Basis</a>
                                         </h3>
                                     @endif
                                     <table id="basis" class="table table-bordered dataTables_scrollBody"
                                         style="width: 100%">
                                         <thead>
                                             <tr>
                                                 <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                                 <th class="textAlign" style="vertical-align: middle;">Posisi</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Target Posisi</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Jumlah Saat Ini</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Action</th>
                                             </tr>
                                         </thead>
                                         <tbody class="textAlign">
                                             @foreach ($jahitBasis as $basis)
                                                 <tr>
                                                     <td>{{ date('d F Y', strtotime($basis->created_at)) }}</td>
                                                     <td>{{ strtoupper($basis->posisi) }}</td>
                                                     <td>{{ $basis->qtyTarget }}</td>
                                                     <td>{{ $basis->total }}</td>

                                                     <td>
                                                         <a href="{{ route('GJahit.basis.detail', [$basis->posisi]) }}"
                                                             class='btn btn-warning'><i class="fas fa-list-ul"
                                                                 style="font-size: 14px"></i></a>

                                                         @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13)
                                                             <a href="{{ route('GJahit.basis.update', $basis->posisi) }}"
                                                                 class='btn btn-success'><i class="fas fa-pencil-alt"
                                                                     style="font-size: 14px"></i></a>
                                                         @endif
                                                         @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8)
                                                             <button type="button" data-toggle="modal"
                                                                 requestId='{{ $basis->id }}'
                                                                 data-target="#DeleteModal" id="modalDelete"
                                                                 onclick='deleteData("{{ $basis->posisi }}", "basis", "basis")'
                                                                 class='btn btn-danger delete'><i class="fas fa-trash"
                                                                     style="font-size: 14px"></i></a>
                                                         @endif
                                                     </td>
                                                 </tr>
                                             @endforeach
                                         </tbody>
                                     </table>
                                 </div>
                                 <div class="tab-pane" id="RekapanLink">
                                     @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13 || \Auth::user()->roleId == 32)
                                         <h3 class="card-title mb-4" style="width: 100%">
                                             <a href="{{ route('GJahit.rekap.create') }}"
                                                 class='btn btn-info btn-flat-right'>Tambah Rekapan Jahit</a>
                                         </h3>
                                     @endif

                                     <table id="rekap" class="table table-bordered dataTables_scrollBody"
                                         style="width: 100%">
                                         <thead>
                                             <tr>
                                                 <th class="textAlign" style="vertical-align: middle;">Tanggal </th>
                                                 <th class="textAlign" style="vertical-align: middle;">Posisi</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Action</th>
                                             </tr>
                                         </thead>
                                         <tbody class="textAlign">
                                             @foreach ($jahitRekap as $rekap)
                                                 <tr>
                                                     <td>{{ date('d F Y', strtotime($rekap->tanggal)) }}</td>
                                                     <td>{{ strtoupper($rekap->posisi) }}</td>

                                                     <td>
                                                         <a href="{{ route('GJahit.rekap.detail', [$rekap->id]) }}"
                                                             class='btn btn-warning'><i class="fas fa-list-ul"
                                                                 style="font-size: 14px"></i></a>

                                                         @if ($rekap->tanggal == date('Y-m-d'))
                                                             @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13)
                                                                 <a href="{{ route('GJahit.rekap.update', $rekap->id) }}"
                                                                     class='btn btn-success'><i class="fas fa-pencil-alt"
                                                                         style="font-size: 14px"></i></a>
                                                             @endif
                                                         @else
                                                             @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8)
                                                                 <button type="button" class="btn btn-success disabled"
                                                                     style="width:40px;"><span
                                                                         class="fas fa-pencil-alt"></span></button>
                                                             @endif
                                                         @endif
                                                     </td>
                                                 </tr>
                                             @endforeach
                                         </tbody>
                                     </table>
                                 </div>
                                 <div class="tab-pane" id="PindahLink">
                                     <ul class="nav nav-pills">
                                         <li class="nav-item"><a class="nav-link active" href="#pindahkanData"
                                                 data-toggle="tab">Pindahkan Data</a>
                                             <hr>
                                         </li>
                                         <li class="nav-item"><a class="nav-link" href="#DataDipindahkan"
                                                 data-toggle="tab">Data Sudah Dipindahkan</a>
                                             <hr>
                                         </li>
                                     </ul>

                                     <div class="tab-content">
                                         <div class="active tab-pane mt-5" id="pindahkanData">
                                             <table id="pemindahan" class="table table-bordered dataTables_scrollBody"
                                                 style="width: 100%">
                                                 <thead>
                                                     <tr>
                                                         <th class="textAlign" style="vertical-align: middle;">Tanggal
                                                             Request </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Jenis Baju
                                                         </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Ukuran Baju
                                                         </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Jumlah Baju
                                                             (Dz)</th>
                                                         <th class="textAlign" style="vertical-align: middle;">Action</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody class="textAlign">
                                                     @for ($i = 0; $i < count($dataPemindahan); $i++)
                                                         <tr>
                                                             <td>{{ $dataPemindahan[$i]['tanggal'] }}</td>
                                                             <td>{{ strtoupper($dataPemindahan[$i]['jenisBaju']) }}</td>
                                                             <td>{{ $dataPemindahan[$i]['ukuranBaju'] }}</td>
                                                             <td>{{ $dataPemindahan[$i]['jumlahBaju'] / 12 }}</td>

                                                             <td>
                                                                 @if (\Auth::user()->roleId == 1 || \Auth::user()->roleId == 5 || \Auth::user()->roleId == 8 || \Auth::user()->roleId == 13 || \Auth::user()->roleId == 32)
                                                                     <a href="{{ route('GJahit.keluar.create', [$dataPemindahan[$i]['jenisBaju'], $dataPemindahan[$i]['ukuranBaju'], date('Y-m-d', strtotime($dataPemindahan[$i]['tanggal']))]) }}"
                                                                         title="Pindahkan Barang"
                                                                         class='btn btn-info btn-flat-right'><i
                                                                             class="fa-solid fa-arrow-right-arrow-left"
                                                                             alt="Pindahkan Barang"></i> <small> Pindahkan
                                                                             Barang</small></a>
                                                                 @endif
                                                                 <a href="{{ route('GJahit.keluar.detail', [$dataPemindahan[$i]['jenisBaju'], $dataPemindahan[$i]['ukuranBaju'], date('Y-m-d', strtotime($dataPemindahan[$i]['tanggal']))]) }}"
                                                                     class='btn btn-warning'><i class="fas fa-list-ul"
                                                                         style="font-size: 14px"></i></a>
                                                             </td>
                                                         </tr>
                                                     @endfor
                                                 </tbody>
                                             </table>
                                         </div>
                                         <div class="tab-pane mt-5" id="DataDipindahkan">
                                             <table id="pemindahan" class="table table-bordered dataTables_scrollBody"
                                                 style="width: 100%">
                                                 <thead>
                                                     <tr>
                                                         <th class="textAlign" style="vertical-align: middle;">Tanggal
                                                             Request </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Nomor PO
                                                         </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Jenis Baju
                                                         </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Ukuran Baju
                                                         </th>
                                                         <th class="textAlign" style="vertical-align: middle;">Jumlah Baju
                                                             (Dz)</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody class="textAlign">
                                                     @foreach ($gdBatilMasuk as $detail)
                                                         <tr>
                                                             <td>{{ date('d F Y', strtotime($detail->created_at)) }}</td>
                                                             <td>{{ $detail->purchase->kode }}</td>
                                                             <td>{{ strtoupper($detail->jenisBaju) }}</td>
                                                             <td>{{ $detail->ukuranBaju }}</td>
                                                             <td>{{ $detail->jumlah / 12 }}</td>
                                                         </tr>
                                                     @endforeach
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="tab-pane" id="RekapanAllLink">
                                     <div class="card-title mb-4" style="width: 100%">
                                         <form id="FormRekapanAll" method="POST">
                                             <input type="hidden" name="_token" id="_token"
                                                 value="{{ csrf_token() }}">

                                             <div class="row">
                                                 <div class="col-3">
                                                     <div class="form-group">
                                                         <label>Nomor PO </label>
                                                         <select class="form-control purchaseId" id="purchaseId"
                                                             name="purchaseId" style="width: 100%; height: 38px;">
                                                             <option> Pilih Satu </option>
                                                             @foreach ($purchase as $kode)
                                                                 <option value="{{ $kode->purchaseId }} ">
                                                                     {{ strtoupper($kode->purchase->kode) }} </option>
                                                             @endforeach
                                                         </select>
                                                     </div>
                                                 </div>
                                                 <div class="col-3">
                                                     <div class="form-group">
                                                         <label>Tanggal Mulai </label>
                                                         <input type="date" name="tglMulai" id="tglMulai"
                                                             class="form-control">
                                                     </div>
                                                 </div>
                                                 <div class="col-3">
                                                     <div class="form-group">
                                                         <label>Tanggal Selesai </label>
                                                         <input type="date" name="tglSelesai" id="tglSelesai"
                                                             class="form-control">
                                                     </div>
                                                 </div>
                                                 <div class="col-12">
                                                     <div class="form-group">
                                                         <button type="submit" id="telusuri"
                                                             class='btn btn-success btn-flat-left telusuri'
                                                             style="float: left">Telusuri</button>
                                                     </div>
                                                 </div>
                                                 <div class="col-12">
                                                     <h6 class="mt-4 text-danger text-sm"> Nb : Data Yang Ditampilkan
                                                         Merupakan Data Yang Sudah Melewati Proses
                                                         Soom, Jahit & Bawahan
                                                     </h6>
                                                 </div>
                                             </div>
                                         </form>
                                     </div>
                                     <table id="rekapanAll" class="table table-bordered dataTables_scrollBody"
                                         style="width: 100%">
                                         <thead>
                                             <tr>
                                                 <th class="textAlign" style="vertical-align: middle;">No </th>
                                                 <th class="textAlign" style="vertical-align: middle;">Nomor PO</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Jenis Baju</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Ukuran Baju</th>
                                                 <th class="textAlign" style="vertical-align: middle;">Total (Dz)</th>
                                             </tr>
                                         </thead>
                                         <tbody class="textAlign allRekapan" id="allRekapan">
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </section>
     <div id="DeleteModal" class="modal fade">
         <div class="modal-dialog ">
             <!-- Modal content-->
             <form id="deleteForm" method="post">
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
                         <input type="hidden" name="jenisBaju" id="jenisBaju">
                         <input type="hidden" name="ukuranBaju" id="ukuranBaju">
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
         function deleteData(jenisBaju, ukuranBaju, opsi) {
             if (opsi == "basis") {
                 var id = id;
                 var url = '{{ route('GJahit.basis.delete') }}';
                 // url = url.replace(':id', id);
                 console.log(id);
                 $('#jenisBaju').val(jenisBaju);
                 $('#ukuranBaju').val(ukuranBaju);
                 $("#deleteForm").attr('action', url);

             } else {
                 var jenisBaju = jenisBaju;
                 var ukuranBaju = ukuranBaju;
                 var url = '{{ route('GJahit.operator.delete') }}';
                 // url = url.replace(':id', id);
                 $('#jenisBaju').val(jenisBaju);
                 $('#ukuranBaju').val(ukuranBaju);
                 $("#deleteForm").attr('action', url);
             }
         }

         function formSubmit() {
             $("#deleteForm").submit();
         }

         $(document).ready(function() {
             $('#operator').DataTable({});
             $('#basis').DataTable({
                 "lengthMenu": [
                     [50, 100, 500, -1],
                     [50, 100, 500, "All"]
                 ]
             });
             $('#rekap').DataTable({
                 "lengthMenu": [
                     [50, 100, 500, -1],
                     [50, 100, 500, "All"]
                 ]
             });
             $('#pemindahan').DataTable({});
             $('#rekapanAll').DataTable({
                 "lengthMenu": [
                     [50, 100, 500, -1],
                     [50, 100, 500, "All"]
                 ]
             });

             $('#FormRekapanAll').on('submit', function(e) {
                 e.preventDefault();
                 var $this = $(this);
                 $.ajax({
                     url: '{{ url('GJahit/searchRekapan') }}',
                     type: 'post',
                     data: $this.serialize(),
                     success: function(html) {
                        $('#rekapanAll tbody.allRekapan').html('');
                        
                        $('#rekapanAll tbody.allRekapan').append(html);
                     }
                 });
             });
         });
     </script>
 @endpush
