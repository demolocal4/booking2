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
    
    <title>Print Receipt</title>
</head>
<body>

    <div class="card">
        <div class="card-header">
           <div class="row" style="padding: 10px 0;">
                <div class="col-md-1 col-12" style="padding-left: 30px;">
                    <a href="{{ url('manage_booking') }}" class="btn btn-info" id="back">Back</a>    
                </div>    
                <div class="col-md-1 col-12 col-md-offset-9">
                <a href="javascript:void(0)" class="btn icon icon-left btn-primary" id="print"><i class="bi bi-printer bi-top"></i> Print</a>
               </div>
               <div class="col-md-1 col-12">
                <a href="{{ url('receipt_pdf/'.$receipt->id) }}" class="btn icon icon-left btn-danger" id="download"><i class="bi bi-cloud-arrow-down bi-top"></i> Download</a>
                </div>
           </div>
            
          
    
        </div>
        <div class="card-body" id="print_area">
            {{-- <div class="divider">
                <div class="divider-text"><h5>Customer Copy</h5></div>
            </div> --}}
            <div class="logo">
                <img src="{{ asset('uploads/logo.png') }}" alt="Logo">
            </div>
            <div class="row company" style="margin-bottom: 10px;">
                <div class="col-md-12 col-12">
                    @php
                    $branchName = DB::table('branchs')->where('id', $receipt->brCode)-first();
                    @endphp
                    {{-- <h4 class="text-center">{{ config('app.name') }}</h4>  --}}
                    <h4 class="text-center">{{ $branchName->brName }}</h4> 

                </div>
                <div class="col-md-12 col-12 text-center">
                    <p><i class="bi bi-telephone"></i> +971 4 1234567</p>
                </div>   
                <div class="col-md-12 col-12 text-center">
                    <p><i class="bi bi-envelope"></i> abc@test.com</p>
                </div>
                <div class="col-md-12 col-12 text-center">
                    <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                </div>
            </div>
            
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
                        </tbody>
                    </table>
                </div>
                
                <div>
                    <table class="table table-bordered mb-0">
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
                            
                            {{-- <tr><td>Paid Amount: {{ $receipt->advance_paid }}</td></tr> --}}
                            {{-- <tr><td>Balance Amount: {{ $receipt->balance }}</td></tr> --}}
                            {{-- <tr><td>Payment Mode: {{ $receipt->payment_mode }}</td></tr> --}}
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="col-12" style="padding-left: 5px;">
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
            <div class="col-12" style="padding-right: 5px; text-align:right;margin-top:15px;">
                <p class="receipt-text">Manager / Receptionist</p>
            </div>
           
            {{-- Office copy divider --}}
            <div class="divider">
                <div class="divider-text">.... <i class="bi bi-scissors rorate"></i> ....</div>
            </div>

            {{-- <div class="divider">
                <div class="divider-text"><h5>Office Copy</h5></div>
            </div>  --}}

            <div class="logo">
                <img src="{{asset('uploads/logo.png')}}" alt="Logo">
            </div>
            <div class="row company" style="margin-bottom: 10px;">
                <div class="col-md-12 col-12">
                    <h4 class="text-center">{{ config('app.name') }}</h4>    
                </div>
                <div class="col-md-12 col-12 text-center">
                    <p><i class="bi bi-telephone"></i> +971 4 1234567</p>
                </div>   
                <div class="col-md-12 col-12 text-center">
                    <p><i class="bi bi-envelope"></i> abc@test.com</p>
                </div>
                <div class="col-md-12 col-12 text-center">
                    <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                </div>
            </div>
            
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
                        </tbody>
                    </table>
                </div>
                
                <div>
                    <table class="table table-bordered mb-0">
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
                            {{-- <tr><td>Paid Amount: {{ $receipt->advance_paid }}</td></tr> --}}
                            {{-- <tr><td>Balance Amount: {{ $receipt->balance }}</td></tr> --}}
                            {{-- <tr><td>Payment Mode: {{ $receipt->payment_mode }}</td></tr> --}}
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="col-12" style="padding-left: 5px;">
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
            <div class="col-12" style="padding-right: 5px; text-align:right;margin-top:15px;">
                <p class="receipt-text">Manager / Receptionist</p>
            </div>
            
           
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