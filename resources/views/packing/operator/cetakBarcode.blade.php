<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Barcode</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <table class='table table-bordered text-center' style="width: 100%;">
        @foreach ($data as $row)
            <tr>
                @foreach ($row as $item)
                    <td>
                        <img alt="{{ $item }}" src="data:image/png;base64,{{DNS1D::getBarcodePNG($item, 'EAN13')}}" height="60" width="180"><br>
                        <small style="letter-spacing: 8px;">{{$item }}</small>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>