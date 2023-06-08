@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
// $payment_rec = DB::table('payment_receipt')->orderBy('id','desc')->where('refId', $receipt->id)->first();
$branch = DB::table('branchs')->where('brCode', Request('id'))->first();
$booking = DB::table('booking')->where('id', Request('bookid'))->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            
        }
        body {
            
            background-color: transparent !important;
        }
        .panel {
            border-radius: 13px !important;
            border-color: navy !important;
            overflow: hidden !important;
            perspective: 1px !important;
            margin-top: 10px;
        }
        .tb1 {

            border: 1px solid navy;
            border-collapse: collapse;
            padding: 5px;
            width: 94%;
            margin: 10px auto;
            border-radius: 10px !important;
                        
        }
        .tb1 td {
            padding: 8px;
        }
        .tb2 {
            width: 94%;
            margin: 10px auto;
            border-collapse: collapse;
        }
        .tb2 td {
            padding: 5px;
            border-bottom: 1px dashed rgb(158, 192, 241);
            font-family: sans-serif;
            font-size: 13px;
        }
        @page {

        size: A3;

        margin-top: 40px;

        margin-left:10px;

        margin-bottom: 10px;

        margin-right:10px;

        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TENANCY CONTRACT</title>
</head>
<body>

    <div class="card">
        <div class="card-header">
          
        </div>
        <div class="card-body" id="print_area">
            <table style="table-layout: fixed;width:94%;margin:10px auto;">
                <tr>
                    <td>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/govt_of_dubai.png'))) }}">
                    </td>
                    <td style="text-align: right">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/land_department.png'))) }}">    
                    </td>
                </tr>
            </table>

            <div class="panel panel-default">
                <table style="table-layout:fixed;" class="tb1">
                    <tr>
                        <td>
                            <p style="font-weight: 600">Date: {{ date('Y-m-d') }}</p>
                            <p style="font-weight: 600">No. ABSR- 000</p>
                        </td>
                        <td style="text-align: center;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/tenancy_contract.png'))) }}">
                        </td>
                        <td>
                            <p style="font-weight: 600;text-align: right;">Checkin Date: {{ $booking->checkin_date }}</p>
                            <p style="font-weight: 600;text-align: right;">CheckOut Date: {{ $booking->checkout_date }}</p>
                            <p style="font-weight: 600;text-align: right;">Total Amount: {{ $booking->totalPayAmount }}</p>
                        </td>
                    </tr>
                </table>
            </div>
            <table style="table-layout:fixed;width:90%;margin:0 auto;">
                <tr>
                    <td>Property Usage</td>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/industrial.png'))) }}">
                    </td>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/commercial.png'))) }}">
                    </td>
                    <td>
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/Residential.png'))) }}">
                    </td>
                </tr>
            </table>    
            <table class="tb2">
                <tr>
                    <td>Owner Name:</td>
                    <td>{{ $branch->ownerName }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Landlord Name:</td>
                    <td>{{ $branch->landlordName }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr>
                    <td>Tenant Name:</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
                </tr>
                <tr>
                    <td>Tenant Email</td><td>Null@gmail.com</td>
                    <td>Landlord Email</td><td>{{ $branch->landlordEmail }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tenant Phone</td><td>{{ $booking->mobile_no }}</td>
                    <td>Landlord Phone</td><td>{{ $branch->landlordPhone }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Building Name</td><td>{{ $branch->brName }}</td>
                    <td>Location</td><td>{{ $branch->brLocation }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Property Size</td><td></td>
                    <td>Property Type</td><td>{{ $branch->propertyType }}</td>
                    <td>Property No</td><td>{{ $booking->room_no }}</td>
                </tr>
                <tr>
                    <td>Premises No(DEWA)</td><td></td>
                    <td>Plot No</td><td>{{ $branch->plotNo }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Contract Period To</td><td></td>
                    <td>From</td><td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Annual Rent</td><td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Contract Value</td><td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Security Deposit Amount</td><td>{{ $branch->sDepositAmount }}</td>
                    <td>Mode of Payment</td><td>{{ $branch->modePayment }}</td>
                    <td></td>
                    <td></td>
                </tr>

            </table>




            <div class="row">
                <div class="col-12">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/contract-p1.png'))) }}" style="width: 100%;height:830px;">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/contract-p2.jpg'))) }}" style="width: 100%;height:1500px;">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    {{-- <img src="{{ asset('uploads/contract-p3.jpg') }}" alt="contract" style="width: 100%;object-fit:cover;"> --}}
                    <table style="table-layout: fixed; width:96%;margin:0 auto;">
                        <tr>
                            <td><span style="color: red;display:inline-block;font-size:16px;">Terms and Conditions</span></td>
                            <td style="text-align: center"><h2 style="color: rgb(14, 191, 197)">{{ $branch->brName }}</h2></td>
                            <td style="text-align: right">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(asset('uploads/'.$branch->logo))) }}">    
                        
                        </td>
                        </tr>
                    </table>
                    <div style="margin-left:50px; margin-top:80px;">
                        <span style="font-size:15px;font-family:sans-serif;display:inline-block;margin-top:5px;line-height:1.9;">
                        {!! $branch->terms !!}
                        </span>
                    </div>
                    <div style="margin-top:30px;margin-left:20px;">
                     <span style="font-size:15px;font-weight:600;font-family:sans-serif;">Tenant Signature______________</span>
                    </div>
                </div>
            </div>

        </div>
            
           
</div>
</div>

        
</body>
</html>