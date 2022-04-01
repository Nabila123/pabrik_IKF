<!DOCTYPE html>
<html>
<head>
    <title>kings</title>
</head>
<body>
    <h3>Rekap Baju yang dipacking Hari ini :</h3>
    <table width="300" border="1">
        @foreach($packingBaju as $key=>$jenis)
            <tr>
                <td rowspan="{{count($jenis)}}">Jenis Baju {{$key}}</td>
            @foreach($jenis as $key2=>$ukuran)
                 @if ($ukuran == reset($jenis))
                    <td>Ukuran {{$ukuran['ukuran']}} = {{$ukuran['jumlah']}}</td>
                </tr>
                @else
                    <tr>
                        <td>Ukuran {{$ukuran['ukuran']}} = {{$ukuran['jumlah']}}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </table>
</body>
</html>