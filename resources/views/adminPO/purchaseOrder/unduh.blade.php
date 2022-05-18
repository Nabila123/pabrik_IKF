<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        footer {
            position: fixed; 
            bottom: 0cm; 
            left: 0cm; 
            right: 0cm;
            height: 0cm;

            /** Extra personal styles **/
            text-align: left;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <table class='text-center' style="width: 100%; margin-bottom:10px;">
        <tr>
            <th style="font-size: 16px"> PT. The Indonesian Knitting Factory</th>
        </tr>
        <tr>
            <th style="font-size: 12px"> 
                Jl. Mpu Tantular 54 Phone : (024) 3546348, 3516357 <br>
                Telex : 22400 IKFSM IA, Fax : (024) 3516357 <br>
                SEMARANG - INDONESIA
            </th>
        </tr>
        <tr>
            <th style="font-size: 16px"> <u>Purchase Order</u></th>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td style="font-size: 12px"> <b>P/O No :</b> {{ $purchase->kode }}</td>
            <td style="font-size: 12px"> <b>Messsrs :</b> {{ $purchase->note }}</td>
        </tr>
    </table>

    <table class='table table-bordered text-center' style="font-size: 12px">
		<thead>
			<tr>
				<th>No</th>
				<th>Description</th>
				<th>Qty</th>
				<th>Unit</th>
				<th>Unit Price</th>
				<th>Amount</th>
				<th>Remark</th>
			</tr>
		</thead>
		<tbody>
			@php $no = 1; @endphp
			@foreach($purchaseDetail as $detail)
			<tr>
				<td>{{ $no++ }}</td>
				<td>{{ $detail->material->nama }}</td>
				<td>{{ $detail->qty }}</td>
				<td>{{ $detail->unit }}</td>
				<td>{{ $detail->unitPrice }}</td>
				<td>{{ $detail->amount }}</td>
				<td>{{ $detail->remark }}</td>
			</tr>
			@endforeach
            <tr>
                <td></td>
                <th>Total :</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th>Rp. {{ $purchase->total }}</th>
            </tr>
		</tbody>
	</table>

    <table class="text-center" style="width: 100%; margin-bottom:6em;">
        <tr>
            <td colspan="2" style="font-size: 12px"> Approve </td>
            <td rowspan="2" style="font-size: 12px; vertical-align: middle;"> Dept. Purchase Order </td>
        </tr>
        <tr>
            <td style="font-size: 12px"> Kepala Divisi Purchase Order </td>
            <td style="font-size: 12px"> Kepala Divisi Finance </td>
        </tr>
        <tr>
            <td style="font-size: 12px">  </td>
            <td style="font-size: 12px">  </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="font-size: 12px"> - Delivery Time </td>
        </tr>
        <tr>
            <td style="font-size: 12px"> - Payment Due </td>
        </tr>
        <tr>
            <td style="font-size: 12px"> - Payment can be disposed only by invoice and P/O closed </td>
        </tr>        
    </table>

    <footer>
        Copyright &copy; Purchase Order / 01 
    </footer>
</body>
</html>