@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
// $payment_rec = DB::table('payment_receipt')->orderBy('id','desc')->where('refId', $receipt->id)->first();
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
          
        </div>
        <div class="card-body" id="print_area">
           
                <table style="width: 100%;table-layout:fixed;">
                    <tr>
                        <td>
                                <h4>{{ config('app.name') }}</h4> 
                                <p><i class="bi bi-telephone"></i> +971 4 1234567</p>
                                <p><i class="bi bi-envelope"></i> abc@test.com</p>
                                <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                        </td>
                        <td>
                            <div style="text-align: center">
                                <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">
                            </div> 
                        </td>
                        <td style="text-align:right;">
                            <p><strong>Date: 22-03-2022</strong></p>
                            <p><strong>Receipt No# 101</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><h2 style="text-align: center;">Cash Receipt</h2></td>
                        <td></td>
                    </tr>
                </table>
                
                @php
                $terms = DB::table('branchs')->where('id', 1001)->first();
                @endphp
                     <div style="padding-left: 30px;">
                     {!! $terms->terms !!}
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