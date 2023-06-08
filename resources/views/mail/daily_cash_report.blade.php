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

         .card {

             width: 100%;

             max-width: 800px;

             margin: 0 auto;

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

    

    <title>Cash Report</title>

</head>

<body>

    

    <div class="card">

        <div class="card-header">

          

        </div>

        <div class="card-body" id="print_area">

            <table style="width: 100%;table-layout:fixed;">

                @php

                $logo = DB::table('branchs')->where('brName', $brname)->first();

                @endphp

                <tr>

                    <td>

                            <h4>{{ $brname }}</h4> 

                            <p><i class="bi bi-telephone"></i> +971 4 1234567</p>

                            <p><i class="bi bi-envelope"></i> {{ $logo->landlordEmail }}</p>

                            <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>

                    </td>

                    <td>

                        <div style="text-align: center">

                            @if(session()->get('role_id') == 1)

                            <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">

                            @else

                            <img style="width: 100px;" src="{{ asset('uploads/'.$logo->logo) }}" alt="Logo">

                            @endif

                        </div> 

                    </td>

                    <td style="text-align:right;">

                        <p><strong>Date: {{ date('Y-m-d h:i:s') }}</strong></p>

                    </td>

                </tr>

                <tr>

                    <td></td>

                    <td><h2 style="text-align: center;">Daily Cash Report</h2></td>

                    <td></td>

                </tr>

            </table>

            <table class="table table-borderd" style="width: 100%;table-layout:fixed;margin-top:15px;">

                    <thead>

                    <tr>

                        <th style="text-align: center">Date</th>

                        <th>Customer Name</th>

                        <th style="text-align: center">Room No</th>

                        <th style="text-align: center">Booking Ref</th>

                        <th style="text-align: center">Cash In</th>

                        <th style="text-align: center">Cash Out</th>

                        <th style="text-align: center">Balance</th>

                    </tr>

                    </thead>

                    <tbody>

                        @foreach($data as $list)

                        <tr>

                            <td style="text-align: center">{{ $list->Date }}</td>

                            <td>{{ $list->customer_name }}</td>

                            <td style="text-align: center">{{ $list->room_no }}</td>

                            <td style="text-align: center">{{ $list->refId }}</td>

                            <td style="text-align: center">{{ $list->creditAmount }}</td>

                            <td style="text-align: center">{{ $list->debitAmount }}</td>

                            <td style="text-align: center">{{ $list->Balance }}</td>

                        </tr>

                        @endforeach

                    </tbody>

            </table>

            

            <div style="margin-top:15px;margin-left:0px;">

                @php

                $username = DB::table('users')->where('id', session()->get('user_id'))->first();

                @endphp

                <span style="display: block;">Authorized By: Vladimir Norchin {{-- {{ ucfirst($username->name) }} --}}</span>

            

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



              



            });



</script>