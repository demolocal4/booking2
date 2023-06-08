@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

$payment_rec = DB::table('payment_receipt')->orderBy('id','asc')->where('refId', $data->id)

                                                                ->where('description','!=','Balance Amount')

                                                                ->get();

$invoice_no = DB::table('payment_receipt')->orderBy('id','desc')->where('refId', $data->id)

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

    

    <title>Print Invoice</title>

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

                <a href="{{ url('invoice_pdf/'.$data->id) }}" class="btn icon icon-left btn-danger" id="download"><i class="bi bi-cloud-arrow-down bi-top"></i> Download</a>

                </div>

           </div>

            

          

    

        </div>

        <div class="card-body" id="print_area">

            @php

            $branchName = DB::table('branchs')->where('id', $data->brCode)->first();

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
                        <p><strong>Date: {{date('Y-m-d h:i:s')}}</strong></p>
                        <p><strong>Check In: {{$data->checkin_date}}</strong></p>
                        <p><strong>Check Out: {{$data->checkout_date}}</strong></p>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><h2 style="text-align: center;">Invoice</h2></td>
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
                            <tr><td>Name: {{ $data->customer_name }}</td></tr>
                            <tr><td>Mobile: {{ $data->mobile_no }}</td></tr>
                            <tr><td>Address: {{ $data->address }}</td></tr>
                            <tr><td>ID/Passport#: {{ $data->id_passport }}</td></tr>
                            <tr><td>Booking.com Ref-ID#: {{ $data->booking_com_ref ?? '-' }}</td></tr>
                        </tbody>

                    </table>

                </div>

                

                <div>

                    <table class="table table-bordered mb-0">

                        <thead>

                            <tr><th>Invoice Date: {{ Carbon::now()->toDateTimeString(); }}</th></tr>

                        </thead>

                        <tbody>

                            <tr><td>No: {{ $invoice_no->refId }}</td></tr>

                            <tr><td>Room No: {{ $data->room_no }}</td></tr>

                            <tr><td>Room Type: {{ $data->room_type }}</td></tr>

                            <tr><td>Total Payable: {{ $data->totalPayAmount }}</td></tr>

                            <tr><td>Advance: {{ $data->advance_paid }}</td></tr>

                            <tr><td>

                                @php

                                $user = DB::table('users')->where('id', session()->get('user_id'))->first();

                                @endphp

                                Created by: {{ $user->fullname }}

                            </td></tr>

                            {{-- <tr><td>Discount: {{ $data->discount_amount }}</td></tr> --}}

                            

                            {{-- <tr><td>Paid Amount: {{ $receipt->advance_paid }}</td></tr> --}}

                            {{-- <tr><td>Balance Amount: {{ $receipt->balance }}</td></tr> --}}

                            {{-- <tr><td>Payment Mode: {{ $receipt->payment_mode }}</td></tr> --}}

                        </tbody>

                    </table>

                </div>

                

            </div>

            <div class="col-12">

                <table class="table table-bordered mb-0">

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

            <div class="col-12" style="padding-left: 5px;margin-top:50px;">

                @php

                $words = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );

                @endphp

                <p class="receipt-text">

                    Received with thanks a sum of {{ $data->totalPayAmount }}/AED.

                    ({{ ucwords($words->format($data->totalPayAmount)).' Only' }})

                </p>



                {{-- @if($receipt->advance_paid > 0)

                <p class="receipt-text">Received with thanks a sum of {{ $receipt->advance_paid }}/AED.</p>

                @else

                <p class="receipt-text">Received with thanks a sum of {{ $payment_rec->amount }}/AED.</p>

                @endif --}}

            </div>

            <div class="col-12" style="padding-left: 5px; text-align:left;margin-top:15px;">

                <p class="receipt-text">Authorized by</p>

            </div>

           

            {{-- Office copy divider --}}

            {{-- <div class="divider">

                <div class="divider-text">.... <i class="bi bi-scissors rorate"></i> ....</div>

            </div> --}}



            {{-- <div class="divider">

                <div class="divider-text"><h5>Office Copy</h5></div>

            </div>  --}}



            

           

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