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

        .myTable tbody tr td {

            padding: 4px !important;
        }

        @page {



        size: A3;



        margin-top: 20px;



        margin-left:10px;



        margin-bottom: 10px;



        margin-right:10px;



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

    

    <title>TENANCY CONTRACT</title>

</head>

<body>



    <div class="card">

        <div class="card-header">

           <div class="row" style="padding: 10px 0;">

                <div class="col-md-1 col-12" style="padding-left: 30px;display:none;">

                    <a href="{{ url('manage_booking') }}" class="btn btn-info" id="back">Back</a>    

                </div>    

                <div class="col-md-1 col-12 col-md-offset-10">

                <a href="javascript:void(0)" class="btn icon icon-left btn-primary" id="print"><i class="bi bi-printer bi-top"></i> Print</a>

               </div>

               <div class="col-md-1 col-12">

                <a href="{{ url('contract-pdf/'.$branch->brCode.'/'.$booking->id) }}" class="btn icon icon-left btn-danger" id="download"><i class="bi bi-cloud-arrow-down bi-top"></i> Download</a>

                </div>

           </div>

        

        </div>

        <div class="card-body" id="print_area">

            <table style="table-layout: fixed;width:100%;">

                <tr>

                    <td><img src="{{ asset('uploads/govt_of_dubai.png') }}" alt="govt_of_dubai"></td>

                    <td align="right"><img src="{{ asset('uploads/land_department.png') }}" alt="govt_of_dubai"></td>

                </tr>

            </table>



            <div class="panel panel-default">

                <table class="table myTable" style="table-layout:fixed;width:100%;">

                    <tr>

                        <td>

                            <p style="font-weight: 600">Date: {{ date('Y-m-d') }}</p>

                            <p style="font-weight: 600">No. ABSR- 000</p>

                        </td>

                        <td style="text-align: center;">

                            <img src="{{ asset('uploads/tenancy_contract.png') }}" alt="tenancy_contract">    

                        </td>

                        <td>

                            <p style="font-weight: 600;text-align: right;">Checkin Date: {{ $booking->checkin_date }}</p>
                            <p style="font-weight: 600;text-align: right;">CheckOut Date: {{ $booking->checkout_date }}</p>
                            <p style="font-weight: 600;text-align: right;">Total Amount: {{ $booking->totalPayAmount }}</p>

                        </td>

                    </tr>

                </table>

            </div>

            <table style="table-layout:fixed;width:100%;margin-bottom:15px;">

                <tr>

                    <td>Property Usage</td>

                    <td><img src="{{ asset('uploads/industrial.png') }}" alt="industrial"></td>

                    <td><img src="{{ asset('uploads/commercial.png') }}" alt="commercial"></td>

                    <td><img src="{{ asset('uploads/Residential.png') }}" alt="Residential"></td>

                </tr>

            </table>    

            <table class="table">

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

                    <img src="{{ asset('uploads/contract-p1.png') }}" alt="contract" style="width: 100%;height:830px;">

                </div>

            </div>

            <div class="row">

                <div class="col-12">

                    <img src="{{ asset('uploads/contract-p2.jpg') }}" alt="contract" style="width: 100%;height:1500px;">

                </div>

            </div>

            <div class="row">

                <div class="col-12">

                    {{-- <img src="{{ asset('uploads/contract-p3.jpg') }}" alt="contract" style="width: 100%;object-fit:cover;"> --}}

                    <table style="table-layout: fixed; width:96%;margin:0 auto;">

                        <tr>

                            <td><span style="color: red;display:inline-block;font-size:16px;">Terms and Conditions</span></td>

                            <td style="text-align: center"><h2 style="color: rgb(14, 191, 197)">{{ $branch->brName }}</h2></td>

                            <td style="text-align: right"><img src="{{ asset('uploads/'.$branch->logo) }}" alt="{{ $branch->logo }}"></td>

                        </tr>

                    </table>

                    <div style="margin-left:50px; margin-top:80px;">

                        <span style="font-size: 15px; display:inline-block;margin-top:5px;line-height:1.9;">

                        {!! $branch->terms !!}

                        </span>

                    </div>

                    <div style="margin-top:30px;margin-left:20px;">

                        <p style="font-size: 15px;font-weight:600">

                            <span>Tenant Signature______________</span>

                        </p>

                    </div>

                </div>

            </div>



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