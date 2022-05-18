 
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
                    <h1> Penjualan Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">Penjualan Barang</li>
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
                                Tanggal Penjualan : <label style="font-size: 15px;">  {{ date('d F y') }} </label>
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
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Kode Product </label>
                                            <input type="text" id="kodeProduct" name="kodeProduct" placeholder="Kode Product" class="form-control kodeProduct">
                                                                                  
                                        </div>
                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card card-info">
                                            <div class="card-header">
                                              <h3 class="card-title">Data Barang</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">  
                                                    <div class="col-12">
                                                        <input type="hidden" name="jumlah_data" class="jumlah_data" id="jumlah_data" value="0">
                                                        <table id="JahitData" class="table table-bordered dataTables_scrollBody textAlign JahitData">
                                                            <thead>
                                                                <tr>
                                                                    <th style="vertical-align: middle;">No</th>
                                                                    <th style="vertical-align: middle;">Kode Barang</th>
                                                                    <th style="vertical-align: middle;">Jenis Baju</th>
                                                                    <th style="vertical-align: middle;">Ukuran Baju</th>
                                                                    <th style="vertical-align: middle;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="data textAlign">
                
                                                            </tbody>
                                                        </table>
                                                    </div>                                          
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-5">                                    
                                    <div class="col-12 mt-3">
                                        <div class="form-group">
                                            <button type="submit" id="Simpan" style="float: right" class='btn btn-info btn-flat-right Simpan'>Simpan Data</button>
                                        </div>
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
        $(document).ready( function () {           
            $('#kodeProduct').val("").focus();
            
            $(document).on("keyup", ".kodeProduct", function(){
                var kodeProduct   = $('#kodeProduct').val().substring(0, 12);
                var _token = $('#_token').val();

                if(kodeProduct != "" && kodeProduct.length == 12){
                    $.ajax({
                        type: "post",
                        url: '{{ url('GPacking/getKode') }}',
                        data: {
                            'kodeProduct' : kodeProduct,
                            '_token': _token
                        },
                        success: function(response){
                            var data = JSON.parse(response)
                            console.log(data.length)
                            $('#JahitData tbody.data').html('');
                            for(var i = 0;i < data.length;i++){
                                var dataHasil = "<tr>";
                                        dataHasil += "<td>"+ (i+1) +"</td>";
                                        dataHasil += "<td>";
                                            dataHasil += "<span style='font-weight: bold;'>"+ data[i]['keterangan'] +"</span> <br>";
                                            dataHasil += "<small>"+ data[i]['kodeProduct'] +"</small>";
                                        dataHasil += "</td>";
                                        dataHasil += "<td>"+ data[i]['jenisBaju'] +"</td>";
                                        dataHasil += "<td>"+ data[i]['ukuranBaju'] +"</td>";
                                        dataHasil += "<td align='center'>";
                                            dataHasil += "<button type='button' id='delete' kodeProduct='"+data[i]['kodeProduct']+"' class='btn btn-sm btn-block btn-danger delete' style='width:40px;'><span class='fa fa-trash'></span></button>";
                                        dataHasil += "</td>";
                                    dataHasil += "</tr>";

                                $('#JahitData tbody.data').append(dataHasil);
                            }                            
                        }
                    })
                    $('#kodeProduct').val("").focus();
                }                
            });
    
            $(document).on("click", "button.delete", function(e){
                e.preventDefault();
                var kodeProduct = $(this).attr('kodeProduct');
                var _token = $('#_token').val();

                $.ajax({
                    type: "delete",
                    url: '{{ url('GPacking/bahanBaku/delete') }}',
                    data: {
                        'kodeProduct' : kodeProduct,
                        '_token': _token
                    },
                    success: function(response){
                        var data = JSON.parse(response)
                        console.log(data.length)
                        $('#JahitData tbody.data').html('');
                        for(var i = 0;i < data.length;i++){
                            var dataHasil = "<tr>";
                                    dataHasil += "<td>"+ (i+1) +"</td>";
                                    dataHasil += "<td>"+ data[i]['kodeProduct'] +"</td>";
                                    dataHasil += "<td>"+ data[i]['jenisBaju'] +"</td>";
                                    dataHasil += "<td>"+ data[i]['ukuranBaju'] +"</td>";
                                    dataHasil += "<td align='center'>";
                                        dataHasil += "<button type='button' id='delete' kodeProduct='"+data[i]['kodeProduct']+"' class='btn btn-sm btn-block btn-danger delete' style='width:40px;'><span class='fa fa-trash'></span></button>";
                                    dataHasil += "</td>";
                                dataHasil += "</tr>";

                            $('#JahitData tbody.data').append(dataHasil);
                        }                            
                    }
                })
            });
        });
      
       
    </script>
@endpush