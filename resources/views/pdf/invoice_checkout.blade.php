@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
$payment_rec = DB::table('payment_receipt')->orderBy('id','asc')->where('refId', $invoice->id)
                                                                ->where('description','!=','Balance Amount')        
                                                                ->get();

$invoice_no = DB::table('payment_receipt')->orderBy('id','desc')->where('refId', $invoice->id)
                                                                ->where('description','!=','Balance Amount')        
                                                                ->first();                                                                
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: sans-serif !important;
        }
        body {
            
            background-color: transparent !important;
        }
        .logo {
            width: 100px;
            margin: 0 auto;
            padding: 15px;
        }
        .logo img {
            width: 100%;
        }
        .rorate {
            transform: rotate(50deg);
        }
        p{
            margin-bottom: 0px !important;
        }
        
         .company p{
             font-size: 15px;
             text-align: center;
             font-weight: 600;
         }
         
         .divider {
             text-align: center;
             padding: 15px;
         }
         .divider-text {
             font-size: 16px;
             font-weight: 600;
         }
         .line-divider {
             border:1px solid rgb(216, 216, 216) !important;
             margin: 8px 0;
         }
         .parent {
             width: 100%;
             padding: 0 20px;
         }
         .parent td {
             width: 50%;
             vertical-align: top;
         }
         .child {
             width: 100%;
             border-collapse: collapse;
         }
         .child th {
            border: 1px solid #999;
            text-align: left;
            padding: 2px 10px;
         }
         .child td {
            border: 1px solid #999;
            text-align: left;
            padding: 2px 10px;
            font-size: 15px;
         }
         .receipt-text {
             margin-left: 15px;
             margin-right: 15px;
             margin-top: 30px;
             font-weight: 600;
             font-family: sans-serif;
         }
         .inv-details {
             padding: 20px;
             margin-top: 20px;
         }
         caption {
             margin-bottom: 20px;
         }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt Download</title>
</head>
<body>
        <div id="print_area">
            @php
            $branchName = DB::table('branchs')->where('id', $invoice->brCode)->first();
            @endphp
            <center>
            <table style="width: 97%;table-layout:fixed;margin-top:15px;">
                <tr>
                    <td style="padding-left: 20px;">
                            <h4>{{ $branchName->brName }}</h4> 
                            <p><i class="bi bi-telephone"></i>Phone: {{ $branchName->brContact }}</p>
                            <p><i class="bi bi-envelope"></i> {{ $branchName->landlordEmail }}</p>
                            <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                    </td>
                    <td>
                        
                        <div style="text-align: center">
                            @if(session()->get('role_id') != 1)
                            <img width="100" height="95" src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/'.$branchName->logo))) }}">
                            @else
                            <img width="100" height="95" src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/logo.png'))) }}">
                            @endif
                        </div> 

                    </td>
                    <td style="text-align:left;padding-left:50px;">
                        <p><strong>Date: {{date('Y-m-d h:i:s')}}</strong></p>
                        <p><strong>Check In: {{$invoice->checkin_date}}</strong></p>
                        <p><strong>Check Out: {{$invoice->checkout_date}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td><h2 style="text-align: center;">Invoice</h2></td>
                    <td></td>
                </tr>
            </table>
            </center>

                    <table class="parent">
                    <tr>
                                <td>
                                <table class="child">
                                    <thead>
                                        <tr><th>Customer Details</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Name: {{ $invoice->customer_name }}</td></tr>
                                        <tr><td>Mobile: {{ $invoice->mobile_no }}</td></tr>
                                        <tr><td>Address: {{ $invoice->address }}</td></tr>
                                        <tr><td>ID/Passport#: {{ $invoice->id_passport }}</td></tr>
                                        <tr><td>Booking.com Ref-ID#: {{ $invoice->booking_com_ref }}</td></tr>
                                    </tbody>
                                </table>
                                </td>
                            
                                <td>
                                <table class="child">
                                    <thead>
                                        <tr><th>Receipt Date: {{ Carbon::now()->toDateTimeString(); }}</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>No: {{ $invoice_no->refId }}</td></tr>
                                        <tr><td>Room No: {{ $invoice->room_no }}</td></tr>
                                        <tr><td>Room Type: {{ $invoice->room_type }}</td></tr>
                                        <tr><td>Total Payable: {{ $invoice->totalPayAmount }}</td></tr>
                                        <tr><td>Advance: {{ $invoice->advance_paid }}</td></tr>
                                        <tr><td>
                                            @php
                                            $user = DB::table('users')->where('id', session()->get('user_id'))->first();
                                            @endphp
                                            Created by: {{ $user->fullname }}
                                        </td></tr>
                                        {{-- <tr><td>Discount: {{ $invoice->discount_amount }}</td></tr> --}}
                                        {{-- <tr><td>Receipt No: {{ $receipt->id }}</td></tr>
                                        <tr><td>Room No: {{ $receipt->room_no }}</td></tr>
                                        <tr><td>Paid Amount: {{ $receipt->advance_paid }}</td></tr>
                                        <tr><td>Balance Amount: {{ $receipt->balance }}</td></tr>
                                        <tr><td>Payment Mode: {{ $receipt->payment_mode }}</td></tr> --}}
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                            </table>
                            <div class="inv-details">
                            <table class="child">
                                <caption style="text-align: center"><h3>Invoice Details</h3></caption>
                                <thead>
                                    <tr>
                                        <th style="text-align: center">SNo#</th>
                                        <th>Description</th>
                                        <th style="text-align: center">C.Amount</th>
                                        <th style="text-align: center">Payment Mode</th>
                                        <th style="text-align: center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $counter = 1;
                                    @endphp
                                    @foreach($payment_rec as $rec)
                                    <tr>
                                        <td style="text-align: center">{{ $counter++ }}</td>
                                        <td>{{ $rec->description }}</td>
                                        <td style="text-align: center">{{ $rec->creditAmount }}</td>
                                        <td style="text-align: center">{{ $rec->payment_mode }}</td>
                                        <td style="text-align: center">{{ $rec->created_at }}</td>
                                    </tr>
                                    @endforeach
                                    
                                    {{-- <tr><td>Paid Amount: {{ $receipt->advance_paid }}</td></tr> --}}
                                    {{-- <tr><td>Balance Amount: {{ $receipt->balance }}</td></tr> --}}
                                    {{-- <tr><td>Payment Mode: {{ $receipt->payment_mode }}</td></tr> --}}
                                </tbody>
                            </table>
                        </div>
            <div style="padding-left: 5px;">
                @php
                $words = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
                @endphp
                <p class="receipt-text">
                    Received with thanks a sum of {{ $invoice->totalPayAmount }}/AED.
                    ({{ ucwords($words->format($invoice->totalPayAmount)).' Only' }})
                </p>
                {{-- @if($receipt->advance_paid > 0)
                <p class="receipt-text">Received with thanks a sum of {{ $receipt->advance_paid }}/AED.</p>
                @else
                <p class="receipt-text">Received with thanks a sum of {{ $payment_rec->amount }}/AED.</p>
                @endif --}}
            </div>
            <div style="padding-right: 5px; text-align:left;margin-top:15px;">
                <p class="receipt-text">Authorized by</p>
            </div>
           
            {{-- Office copy divider --}}
            
            </div>

</body>
</html>