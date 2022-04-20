<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Barcode</title>

    {{--  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">  --}}
    <style type="text/css">
		.label {
		    margin: 0;
		    padding: 10px 10px 16px 10px;
		    width: 800px;
		}
		.label li {
		    margin: 16px 12px;
		    list-style: none;
		    display: inline-block;
		    border: 2px solid;
		    border-radius: 10px;
		    width: 230px;
		    height: 100px;
		    text-align: center;
		    font-size: 12px;
		    font-weight: bold;
		    line-height: 1.2;
		    font-family: sans-serif;
		}

        .page-break {
            page-break-after: always;
        }
	</style>
</head>
<body style="margin:0">
    <ul class="label">
        @php $i = 0; @endphp
        @foreach ($data as $row)
            @foreach ($row as $item)
                <li>
                    <img style="margin-top:10px;" alt="{{ $item['barcode'] }}" src="data:image/png;base64,{{DNS1D::getBarcodePNG($item['barcode'], 'EAN13')}}" height="60" width="180"><br>
                    <small style="letter-spacing: 9.5px;">{{$item['barcode'] }}</small> <br>
                    <small>{{$item['ket'] }}</small>
                </li>
                @php $i++; @endphp


                @if ($i == 12)
                    <div class="page-break"></div>
                    @php $i = 0; @endphp
                @endif
            @endforeach
        @endforeach
    </ul>
    {{--  <table class='table table-bordered text-center' style="width: 100%;">
        @foreach ($data as $row)
            <tr>
                @foreach ($row as $item)
                    <td>
                        <img alt="{{ $item['barcode'] }}" src="data:image/png;base64,{{DNS1D::getBarcodePNG($item['barcode'], 'EAN13')}}" height="60" width="180"><br>
                        <small style="letter-spacing: 8px;">{{$item['barcode'] }}</small> <br>
                        <small>{{$item['ket'] }}</small>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>  --}}
</body>
</html>