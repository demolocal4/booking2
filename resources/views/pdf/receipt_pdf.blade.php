@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
$payment_rec = DB::table('payment_receipt')->orderBy('id','desc')->where('refId', $receipt->id)->first();
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
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt Download</title>
</head>
<body>
        <div id="print_area" style="padding-bottom: 15px;padding-top: 15px;">
            @php
            $branchName = DB::table('branchs')->where('id', $receipt->brCode)->first();
            @endphp
            <center>
            <table style="width: 97%;table-layout:fixed;margin-top:15px;">
                <tr>
                    <td style="padding-left: 20px;">
                            <h4>{{ $branchName->brName }}</h4> 
                            <p><i class="bi bi-telephone"></i>Phone: {{ $branchName->brContact  }}</p>
                            <p><i class="bi bi-envelope"></i>{{ $branchName->landlordEmail }}</p>
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
                        <p><strong>No# {{ $payment_rec->cr_no }}</strong></p>
                        <p><strong>Date: {{$receipt->checkin_date}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td><h2 style="text-align: center;">Cash Receipt</h2></td>
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
                                        <tr><td>Name: {{ $receipt->customer_name }}</td></tr>
                                        <tr><td>Mobile: {{ $receipt->mobile_no }}</td></tr>
                                        <tr><td>Address: {{ $receipt->address }}</td></tr>
                                        <tr><td>ID/Passport#: {{ $receipt->id_passport }}</td></tr>
                                        <tr><td>Pending Amount: {{ $receipt->balance }}</td></tr>
                                        <tr><td>Payment Mode: {{ $payment_rec->payment_mode }}</td></tr>
                                        <tr><td>Booking.com Red-ID#: {{ $receipt->booking_com_ref }}</td></tr>
                                    </tbody>
                                </table>
                                </td>
                            
                                <td>
                                <table class="child">
                                    <thead>
                                        <tr><th>Check Out Date: {{ $receipt->checkout_date }}</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Booking Ref: {{ $receipt->id }}</td></tr>
                                        <tr><td>Room No: {{ $receipt->room_no }}</td></tr>
                                        <tr><td>Room Type: {{ $receipt->room_type }}</td></tr>
                                        <tr><td>Total Payable: {{ $receipt->totalPayAmount }}</td></tr>
                                        <tr><td>Advance: {{ $receipt->advance_paid }}</td></tr>
                                        <!-- <tr><td>{{$payment_rec->description}}: {{ $payment_rec->creditAmount }}</td></tr> -->
                                        <tr><td>Paid Amount: {{ $payment_rec->creditAmount }}</td></tr>
                                        <tr><td>
                                            @php
                                            $user = DB::table('users')->where('id', $payment_rec->created_by)->first();
                                            @endphp
                                            Created by: {{ $user->fullname }}
                                        </td></tr>

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
            
            <div>
                @php
                $words = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
                @endphp
                <p style="font-size:14px;margin-left:20px;margin-top:20px;">
                	@if(session()->has('received_amount'))
                    Received with thanks a sum of {{ session()->get('received_amount') }}/AED.
                    ({{ ucwords($words->format(session()->get('received_amount'))).' Only' }})  
                    @else
                    Received with thanks a sum of {{ $payment_rec->creditAmount }}/AED.
                    ({{ ucwords($words->format($payment_rec->creditAmount)).' Only' }})
                    @endif
                </p>
                {{-- @if($receipt->advance_paid > 0)
                <p class="receipt-text">Received with thanks a sum of {{ $receipt->advance_paid }}/AED.</p>
                @else
                <p class="receipt-text">Received with thanks a sum of {{ $payment_rec->amount }}/AED.</p>
                @endif --}}
            </div>
            <p>
                <span style="width:50%;display:inline-block;font-size:12px; margin-left:20px;margin-top:5px;color:dimgray;">
                    By Signing this receipt, the tenant and his companies are considered
                    to agree to {{ $branchName->brName }} terms and conditions.
                </span>
            </p>
            
           
            {{-- TERMS & CONDITIONS --}}

            <h5 style="margin-left:20px;margin-top:10px;"><strong>TERMS & CONDITIONS:</strong></h5>
            <div style="margin-left:20px;padding-right: 15px;">
                <span style="font-size: 9px; display:inline-block;margin-top:10px;">
                {!! $branchName->terms !!}
                </span>
            </div>
            <div style="margin-top:10px;margin-left:20px;">
                <p style="font-size: 9px;">
                    <span>Received By______________</span>
                    <span style="float:right;margin-right:25px;">Tenant Signature______________</span>
                </p>
            </div>
        
       
         
            
</div>
</body>
</html>