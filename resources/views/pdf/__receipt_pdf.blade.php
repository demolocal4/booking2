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
        <div id="print_area">
            <div class="logo">
                {{-- <img src="{{ url("storage/uploads/logo.png") }}" width="100" height="100" alt="Logo"> --}}
                <img width="100" height="95" src="data:image/png;base64,{{ base64_encode(file_get_contents('uploads/logo.png')) }}">
            </div>
            <div class="company" style="margin-bottom: 10px;">
                <div style="text-align: center;">
                    @php
                    $branchName = DB::table('branchs')->where('id', $receipt->brCode)->first();
                    @endphp
                    {{-- <h4>{{ config('app.name') }}</h4>    --}}
                    <h4>{{ $branchName->brName }}</h4>   
                </div>
                <div>
                    <p><i class="bi bi-telephone"></i> +971 4 1234567</p>
                </div>   
                <div>
                    <p><i class="bi bi-envelope"></i> abc@test.com</p>
                </div>
                <div>
                    <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                </div>
            </div>

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
                                    </tbody>
                                </table>
                                </td>
                            
                                <td>
                                <table class="child">
                                    <thead>
                                        <tr><th>Receipt Date: {{ $receipt->checkin_date }}</th></tr>
                                        <tr><th>Check Out Date: {{ $receipt->checkout_date }}</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>Receipt No: {{ $receipt->id }}</td></tr>
                                        <tr><td>Room No: {{ $receipt->room_no }}</td></tr>
                                        <tr><td>Total Payable: {{ $receipt->totalPayAmount }}</td></tr>
                                        <tr><td>Advance: {{ $receipt->advance_paid }}</td></tr>
                                        <tr><td>{{$payment_rec->description}}: {{ $payment_rec->creditAmount }}</td></tr>
                                        <tr><td>Payment Mode: {{ $payment_rec->payment_mode }}</td></tr>
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
                    Received with thanks a sum of {{ session()->get('received_amount') }}/AED.
                    ({{ ucwords($words->format(session()->get('received_amount'))).' Only' }})  
                </p>
                {{-- @if($receipt->advance_paid > 0)
                <p class="receipt-text">Received with thanks a sum of {{ $receipt->advance_paid }}/AED.</p>
                @else
                <p class="receipt-text">Received with thanks a sum of {{ $payment_rec->amount }}/AED.</p>
                @endif --}}
            </div>
            <p>
                <span style="width:50%;display:inline-block;font-size:11px; margin-left:20px;margin-top:5px;color:dimgray;">
                    By Signing this receipt, the tenant and his companies are considered
                    to agree to {{ $branchName->brName }} terms and conditions.
                </span>
            </p>
            <div style="margin-top:15px;margin-left:20px;">
                <p style="font-size: 13px;">
                    <span>Received By______________</span>
                    <span style="float:right;margin-right:25px;">Tenant Signature______________</span>
                </p>
            </div>
           
            {{-- Office copy divider --}}

            <hr class="line-divider">
            
            <div class="logo">
            <img width="100" height="95" src="data:image/png;base64,{{ base64_encode(file_get_contents('uploads/logo.png')) }}">
            </div>
            <div class="company" style="margin-bottom: 10px;">
                <div style="text-align: center;">
                    <h4>{{ config('app.name') }}</h4>    
                </div>
                <div>
                    <p><i class="bi bi-telephone"></i> +971 4 1234567</p>
                </div>   
                <div>
                    <p><i class="bi bi-envelope"></i> abc@test.com</p>
                </div>
                <div>
                    <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                </div>
            </div>

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
                                </tbody>
                            </table>
                            </td>
                        
                            <td>
                            <table class="child">
                                <thead>
                                    <tr><th>Receipt Date: {{ $receipt->checkin_date }}</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Receipt No: {{ $receipt->id }}</td></tr>
                                    <tr><td>Room No: {{ $receipt->room_no }}</td></tr>
                                    <tr><td>Total Payable: {{ $receipt->totalPayAmount }}</td></tr>
                                    <tr><td>Advance: {{ $receipt->advance_paid }}</td></tr>
                                    <tr><td>{{$payment_rec->description}}: {{ $payment_rec->creditAmount }}</td></tr>
                                    <tr><td>Payment Mode: {{ $payment_rec->payment_mode }}</td></tr>
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
        
        <div style="padding-left: 5px;">
            @php
            $words = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
            @endphp
            <p class="receipt-text">
                Received with thanks a sum of {{ session()->get('received_amount') }}/AED.
                ({{ ucwords($words->format(session()->get('received_amount'))).' Only' }})  
            </p>
                {{-- @if($receipt->advance_paid > 0)
                <p class="receipt-text">Received with thanks a sum of {{ $receipt->advance_paid }}/AED.</p>
                @else
                <p class="receipt-text">Received with thanks a sum of {{ $payment_rec->amount }}/AED.</p>
                @endif --}}
        </div>
        <div style="padding-right: 5px; text-align:right;margin-top:15px;">
            <p class="receipt-text">Manager / Receptionist</p>
        </div>
         
            
</div>
</body>
</html>
