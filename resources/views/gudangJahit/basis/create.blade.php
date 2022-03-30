 
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

        tr th{
            max-width:100%;
            white-space:nowrap;
        }

        tr td{
            max-width:100%;
            white-space:nowrap;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> Jahit Basis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Jahit Basis</li>
                        <li class="breadcrumb-item active">Tambah Data</li>
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
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title" style="float: left">
                                Operator : <label style="font-size: 15px;">  {{ \Auth::user()->nama }} </label>
                                <input type="hidden" name="namaOperator" value="{{ \Auth::user()->nama }}">
                            </h3>
                            <h3 class="card-title" style="float: right">
                                Pengerjaan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
                            </h3> 
                        </div>                  
                        <div class="card-body">
                            @if(isset($message))
                                <div class="alert alert-danger not-found">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <strong>Perhatian!</strong> {{$message}}
                                </div>
                            @endif
                            <form id="demo-form2" data-parsley-validate  method="POST" enctype="multipart/form-data">                    
                                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">        
                                <input type="hidden" id="operator" name="operator" value="{{ \Auth::user()->id }}" class="form-control operator">        
                                
                                <div class="row mb-5">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Posisi Jahit </label>
                                            <select class="form-control posisi" id="posisi" name="posisi" style="width: 100%; height: 38px;" required>
                                                <option> Pilih Satu </option>
                                                <option value="soom">Soom</option>
                                                <option value="jahit">Jahit</option>
                                                <option value="bawahan">Bawahan</option>
                                            </select>                                                                                  
                                        </div>
                                    </div>                                    

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Jumlah Target</label>
                                                <input type="number" id="qtyTarget" name="qtyTarget" class="form-control qtyTarget" placeholder="Jumlah Lusin" required>                                                
                                            </select>                                            
                                        </div>
                                    </div> 

                                    <div class="col-4">
                                        <label>Jumlah Saat Ini</label>
                                        <div class="input-group">                                            
                                            <input type="number" id="total" name="total" class="form-control total disable" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                        <table id="JahitData" class="table table-bordered table-responsive dataTables_scrollBody textAlign JahitData">
                                            <thead class="dataTHead">

                                            </thead>
                                            <tbody class="dataTBody textAlign">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="form-group">
                                            <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Simpan Data</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">
                                    <div class="col-12">
                                        <table id="HasilJahitData" class="table table-bordered table-responsive dataTables_scrollBody textAlign HasilJahitData">
                                            <thead class="dataTHead">

                                            </thead>
                                            <tbody class="dataTBody textAlign">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('page_scripts') 
    <script type="text/javascript"> 
        
        $('#posisi').select2({
            theme: 'bootstrap4'
        });

        $(document).on("change", ".posisi", function(){
            var posisi = $('#posisi').val();
            var _token = $('#_token').val();
            $('#HasilJahitData thead.dataTHead').html('');
            $('#HasilJahitData tbody.dataTBody').html('');
            $('.qtyTarget').val('');
            
            $.ajax({
                type: "post",
                url: '{{ url('GJahit/getBasis') }}',
                data: {
                    'posisi' : posisi,
                    '_token': _token
                },
                success: function(response){
                    var data = JSON.parse(response)
                    $('#JahitData thead.dataTHead').html(''); 
                    $('#JahitData tbody.dataTBody').html('');                     
                    $('.total').val(data.jumlah.total);
                    if(data.jumlah.target > 0){
                        $('.qtyTarget').val(data.jumlah.target);
                    }

                    var dataBasis = "<tr>";
                    var dataHasil = "<tr>";

                    for(var i = 0;i < data.pegawai.length;i++){
                            dataBasis += "<th>"+ data['pegawai'][i]['nip'] + " - "+ data['pegawai'][i]['nama'] +"</th>";
                            dataHasil += "<td align='center'>";
                            dataHasil += "<input style='width:70px;' class='form-control' type='number' name='jumlah[]' id='jumlah_"+i+"'>"; 
                            dataHasil += "<input type='hidden' name='pegawaiId[]' id='pegawaiId_"+i+"' value='"+data['pegawai'][i]['id']+"'>"; 
                            dataHasil += "</td>";
                        }
                    dataBasis += "</tr>";
                    dataHasil += "</tr>";
                    $('#JahitData thead.dataTHead').append(dataBasis);
                    $('#JahitData tbody.dataTBody').append(dataHasil);

                    $('#JahitData').DataTable();

                    if(data.basis != "undefined" && data.basis != null && data.basis != ''){
                        var hasilDataBasis = "<tr>";
                        var hasilDataHasil = "<tr>";
    
                        for(var i = 0;i < data.basis.pegawai.length;i++){
                                hasilDataBasis += "<th>"+ data['basis']['pegawai'][i] +"</th>";
                                hasilDataHasil += "<td>"+ data['basis']['jumlah'][i] +"</td>";
                            }
                        hasilDataBasis += "</tr>";
                        hasilDataHasil += "</tr>";

                        $('#HasilJahitData thead.dataTHead').append(hasilDataBasis);
                        $('#HasilJahitData tbody.dataTBody').append(hasilDataHasil);
    
                        $('#HasilJahitData').DataTable();
                    }
                   
                }
            })
        });
      
       
    </script>
@endpush