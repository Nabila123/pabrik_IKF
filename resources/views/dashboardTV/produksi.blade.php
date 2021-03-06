 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    
    <style>
        .textAlign {
            vertical-align: middle; 
            text-align: center;
            font-size: 14px;
        }

        .dataTables_scrollBody::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
            border-radius: 10px;
        }
        
        .dataTables_scrollBody::-webkit-scrollbar {
            width: 0px;
            height: 2px;
            background-color: #F5F5F5;
        }
        
        .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background-color: #777;
            border-radius: 10px;
        }        

        tr th{
            max-width:100%;
            white-space:nowrap;
            padding: 0;
        }

        tr td{
            max-width:100%;
            white-space:nowrap;
            padding: 0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed bg-info">
<div class="wrapper">

    <section class="content-header"> </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row header" id="header" style="margin-top:-10px;">
                 
            </div>

            <div class="col-12">
                <div class="card card-info bg-light">
                    <div class="card-body">
                        <div id="contentScroll" class="dataTables_scrollBody" style="overflow-y: scroll; height:420px;">
                            <div class="table" id="table">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="{{ mix('js/app.js') }}" defer></script>

<script src = "https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src = "https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js" defer ></script>
<script src = "https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js" defer ></script>
<script src = "https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js" defer ></script>
<script src = "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" ></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript"> 
        var my_time;

        $(document).ready(function() {
            pageScroll();
            $("#contentScroll").mouseover(function() {
                clearTimeout(my_time);
            }).mouseout(function() {
                pageScroll();
            });
        });        
        
        function pageScroll() {  
            var objDiv = document.getElementById("contentScroll");
            objDiv.scrollTop = objDiv.scrollTop + 1;  
            $('p:nth-of-type(1)').html('scrollTop : '+ objDiv.scrollTop);
            $('p:nth-of-type(2)').html('scrollHeight : ' + objDiv.scrollHeight);
            if (objDiv.scrollTop == (objDiv.scrollHeight - 430)) {
                objDiv.scrollTop = 0;
            }
            my_time = setTimeout('pageScroll()', 50);
        }

        $(document).ready( function () {  
            addField()
        });

        function addField(){
            setTimeout(function() {
                getData();
                addField();
            }, 200);
        }

        function getData(){
            $.ajax({
                type: "get",
                url: '{{ url('dashboard/getProduksi') }}',
                success: function(response){
                    var data = JSON.parse(response) 
                    console.log(data);
                    $('#header').html('');
                    $('#table').html('');
                    var color = ['bg-success', 'bg-dark', 'bg-primary', 'bg-warning', 'bg-secondary', 'bg-light'];
                    for(var i = 0;i < data.header.length;i++){
                    var header = "<div class='col-12 col-sm-12 col-md-4'>";
                            header += "<div class='info-box "+color[i]+"' style='min-height:50px;'>";                    
                                header += "<div class='info-box-content'>";
                                    header += "<span class='info-box-number'> <span style='font-size:17px;'>"+data['header'][i].kategori+"</span>";
                                    header += "<span style='float:right'>"+data['header'][i].jumlah+" "+data['header'][i].satuan+"</span></span>";
                                header += "</div>";
                            header += "</div>";
                        header += "</div>";
                        $('#header').append(header);
                    }

                    for(var i = 0;i < data.table.length;i++){
                    var table = "<h6 class='card-title  mt-4 mb-4' style='width: 100%; font-weight: bold;'>";
                            if(i == 0){ table += "Gudang Potong"; }
                            else if(i == 1){ table += "Gudang Jahit"; }
                            else if(i == 2){ table += "Gudang Batil"; }
                            else if(i == 3){ table += "Gudang Control"; }
                            else if(i == 4){ table += "Gudang Setrika"; }
                            else{ table += "Gudang Packing"; }
                        table += "</h6>";
                        table += "<table class='table table-bordered table-striped textAlign ' style='width: 100%; border-collapse: collapse;'>";
                            table += "<thead>";
                                table += "<tr>";
                                    table += "<th rowspan='2' style='vertical-align: middle;'>Nomor</th>";                                    
                                    if(i != 0){
                                        table += "<th>No PO</th>";
                                    }
                                    table += "<th rowspan='2' style='vertical-align: middle;'>Jenis Baju</th>";
                                    table += "<th rowspan='2' style='vertical-align: middle;'>Ukuran</th>";
                                    table += "<th rowspan='2' style='vertical-align: middle;'>QTY</th>";
                                    if(i == 0){
                                        table += "<th colspan='10'>Aval</th>";
                                    }                                    
                                table += "</tr>";
                                if(i == 0){
                                    table += "<tr>";
                                        table += "<th>Kecil</th>";
                                        table += "<th>Ketek</th>";
                                        table += "<th>K. Potong</th>";
                                        table += "<th>Sumbu</th>";
                                        table += "<th>Bunder</th>";
                                        table += "<th>T. Kecil</th>";
                                        table += "<th>T. Besar</th>";
                                        table += "<th>Tangan</th>";
                                        table += "<th>K. Putih</th>";
                                        table += "<th>K. Belang</th>";
                                        table += "<th>SKB</th>";
                                        table += "<th>BS</th>";
                                    table += "</tr>";
                                }
                            table += "</thead>";
                            table += "<tbody class='textAlign'>";
                                for(var j = 0;j < data['table'][i].length;j++){
                                    table += "<tr>";
                                        table += "<td>"+(j+1)+"</td>";
                                        if(i != 0){
                                            table += "<td>"+data['table'][i][j].purchaseId+"</td>";
                                        }
                                        table += "<td>"+data['table'][i][j].jenisBaju+"</td>";
                                        table += "<td>"+data['table'][i][j].ukuranBaju+"</td>";
                                        table += "<td>"+data['table'][i][j].jumlah+" Dz</td>";
                                        if(i == 0){
                                            table += "<td>"+data['table'][i][j].aKecil+"</td>";
                                            table += "<td>"+data['table'][i][j].aKetek+"</td>";
                                            table += "<td>"+data['table'][i][j].aKetekPotong+"</td>";
                                            table += "<td>"+data['table'][i][j].aSumbu+"</td>";
                                            table += "<td>"+data['table'][i][j].aBunder+"</td>";
                                            table += "<td>"+data['table'][i][j].aTanggungKecil+"</td>";
                                            table += "<td>"+data['table'][i][j].aTanggungBesar+"</td>";
                                            table += "<td>"+data['table'][i][j].aTangan+"</td>";
                                            table += "<td>"+data['table'][i][j].aKepalaPutih+"</td>";
                                            table += "<td>"+data['table'][i][j].aKepalaBelang+"</td>";
                                            table += "<td>"+data['table'][i][j].skb+"</td>";
                                            table += "<td>"+data['table'][i][j].bs+"</td>";
                                        }
                                    table += "</tr>";
                                }
                            table += "</tbody>";
                        table += "</table>";
                        $('#table').append(table);
                    }

                }
            })
        }
    </script>
</body>
</html>