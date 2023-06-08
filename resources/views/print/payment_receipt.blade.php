@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

if(Request('cr_no') != '') {
$payment_rec = DB::table('payment_receipt')->orderBy('id','asc')->where('cr_no', Request('cr_no'))
                                                                 ->where('description','<>','Balance Amount')           
                                                                 ->first();
}else{
$payment_rec = DB::table('payment_receipt')->orderBy('id','desc')->where('refId', $receipt->id)->first();
}
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
        .logo {
            width: 100px;
            margin: 0 auto;
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
        .details {
            display: flex;
            padding: 0 20px;
        }
        .details div {
            width: 50%;
        }
        .table>:not(caption)>*>* {
           padding: 0.1rem !important;
           padding-left: 1rem !important;
    
         }
         tbody tr td {
             font-size: 14px !important;
            
         }
         .company p{
             font-size: 15px;
         }
         .receipt-text {
             font-weight: 600;
         }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.css')}}"> --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('admin/assets/vendors/simple-datatables/style.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/app.css')}}">
    
    <title>Payment Receipt</title>
</head>
<body>

    <div class="card">
        <div class="card-header">
           <div class="row" style="padding: 10px 0;">
                
                <div class="col-md-1 col-12" style="padding-left: 30px;">
                    <a href="{{ url('manage_booking') }}" class="btn btn-info" id="back">Booking</a>    
                </div>    

                <div class="col-md-1 col-12">
                    <a href="{{ url('all_bookings') }}" class="btn btn-success" id="back1">All Booking</a>    
                </div>  


                <div class="col-md-1 col-12 col-md-offset-8">
                <a href="javascript:void(0)" class="btn icon icon-left btn-primary" id="print"><i class="bi bi-printer bi-top"></i> Print</a>
               </div>
               <div class="col-md-1 col-12">
                <a href="{{ url('payment_receipt_pdf/'.$receipt->id) }}" class="btn icon icon-left btn-danger" id="download"><i class="bi bi-cloud-arrow-down bi-top"></i> Download</a>
                </div>
           </div>
            
          
    
        </div>
        <div class="card-body" id="print_area">
            @php
            $branchName = DB::table('branchs')->where('id', $receipt->brCode)->first();
            @endphp
            <table style="width: 100%;table-layout:fixed;">
                <tr>
                    <td>
                            <h4>{{ $branchName->brName }}</h4> 
                            <p><i class="bi bi-telephone"></i> {{ $branchName->brContact }}</p>
                            <p><i class="bi bi-envelope"></i> {{ $branchName->landlordEmail }}</p>
                            <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                    </td>
                    <td>
                        <div style="text-align: center">
                            @if(session()->get('role_id') != 1)
                            <img style="width: 100px;" src="{{ asset('uploads/'.$branchName->logo) }}" alt="Logo">
                            @else
                            <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">
                            @endif
                        </div> 
                    </td>
                    <td style="text-align:left;padding-left:50px;">
                        <p><strong>No# {{ $payment_rec->cr_no }}</strong></p>
                        <p><strong>Date: {{$payment_rec->created_at}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><h2 style="text-align: center;">Payment Receipt</h2></td>
                    <td></td>
                </tr>
            </table>
            
            
            
            <div class="row details">
                <div>
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr><th>Customer Details</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Name: {{ $receipt->customer_name }}</td></tr>
                            <tr><td>Mobile: {{ $receipt->mobile_no }}</td></tr>
                            <tr><td>Address: {{ $receipt->address }}</td></tr>
                            <tr><td>ID/Passport#: {{ $receipt->id_passport }}</td></tr>
                            <tr><td>Booking.com Ref-ID#: {{ $receipt->booking_com_ref }}</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <div>
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr><th>Booking Cancelled</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>Booking Ref: {{ $receipt->id }}</td></tr>
                            <tr><td>Room No: {{ $receipt->room_no }}</td></tr>
                            <tr><td>Room Type: {{ $receipt->room_type }}</td></tr>
                            <tr><td>Total Payable: {{ $receipt->totalPayAmount }}</td></tr>
                            <tr><td>Advance: {{ $receipt->advance_paid }}</td></tr>
                            <tr><td>{{$payment_rec->description}}: {{ $payment_rec->debitAmount }}</td></tr>
                            <tr><td>Payment Mode: {{ $payment_rec->payment_mode }}</td></tr>
                            <tr><td>
                                @php
                                $user = DB::table('users')->where('id', session()->get('user_id'))->first();
                                @endphp
                                Created by: {{ $user->fullname }}
                            </td></tr>
                            {{-- <tr><td>Paid Amount: {{ $receipt->advance_paid }}</td></tr> --}}
                            {{-- <tr><td>Balance Amount: {{ $receipt->balance }}</td></tr> --}}
                            {{-- <tr><td>Payment Mode: {{ $receipt->payment_mode }}</td></tr> --}}
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="col-12" style="padding-left: 20px;">
                @php
                $words = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
                @endphp
                <p class="receipt-text">
                    @if(session()->has('received_amount'))
                    Return Amount to the customer <strong>{{ $receipt->customer_name }}</strong> a sum of {{ session()->get('received_amount') }}/AED.
                    ({{ ucwords($words->format(session()->get('received_amount'))).' Only' }})    
                    @else
                    Return Amount to the customer <strong>{{ $receipt->customer_name }}</strong> a sum of {{ $payment_rec->debitAmount }}/AED.
                    ({{ ucwords($words->format( $payment_rec->debitAmount )).' Only' }})  
                    @endif
                </p>

                {{-- @if($receipt->advance_paid > 0)
                <p class="receipt-text">Received with thanks a sum of {{ $receipt->advance_paid }}/AED.</p>
                @else
                <p class="receipt-text">Received with thanks a sum of {{ $payment_rec->amount }}/AED.</p>
                @endif --}}
            </div>
            
            {{-- <p>
                <span style="width:50%;display:inline-block;font-size:12px; margin-left:20px;margin-top:5px;color:dimgray;">
                    By Signing this receipt, the tenant and his companies are considered
                    to agree to {{ $branchName->brName }} terms and conditions.
                </span>
            </p> --}}

            <div style="margin-top:15px;margin-left:20px;">
                <p style="font-size: 13px;">
                    <span>Authorized By______________</span>
                    <span style="float:right;margin-right:25px;">Received By______________</span>
                </p>
            </div>
           {{-- TERMS & CONDITIONS --}}
            {{-- <h5 style="margin-left:20px;margin-top:25px;"><strong>TERMS & CONDITIONS:</strong></h5>
            <div style="margin-left:33px;">
                <span style="font-size: 10px; display:inline-block;margin-top:5px;">
                {!! $branchName->terms !!}
                </span>
            </div> --}}
            
           
        </div>
    </div>

        
</body>
</html>
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>    
{{-- <script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script> --}}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="{{asset('admin/assets/vendors/simple-datatables/simple-datatables.js')}}"></script>
<script src="{{asset('js/divjs.js')}}"></script>
<script>

            $(document).ready(function(){

                $("#print").on("click", function(){

                    $('#print_area').printElement({

                        css:'extend'

                    });


                });
                

            });

</script>