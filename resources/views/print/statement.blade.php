<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Statement</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Booking.com Ref-ID</th>
                <th>Branch</th>
                <th>Total Amount</th>
                <th>Commission</th>
                <th>Payment Charge</th>
                <th>Vat Online</th>
                <th>Payout ID</th>
            </tr>
        </thead>
        @php
        $total_amt = 0;
        $total_comm = 0;
        $total_pay = 0;
        $total_vat = 0;
        @endphp
        @foreach ($allbookings as $book)
        @php
            $total_amt += $book->total_amount;
            $total_comm += $book->commission;
            $total_pay += $book->payment_charge;
            $total_vat += $book->vat_online;
        @endphp
        <tr>
            <td>{{ $book->id }}</td>
            <td>{{ $book->booking_com_ref }}</td>
            <td>{{ $book->branch->brName }}</td>
            <td>{{ $book->total_amount }}</td>
            <td>{{ $book->commission }}</td>
            <td>{{ $book->payment_charge }}</td>
            <td>{{ $book->vat_online }}</td>
            <td>{{ $book->payout_id }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3"></td>
            <td>{{ $total_amt }}</td>
            <td>{{ $total_comm }}</td>
            <td>{{ $total_pay }}</td>
            <td>{{ $total_vat }}</td>
            <td></td>
        </tr>
    </table>
</body>
</html>