@extends('admin.layout.app')

@section('page_title', 'Reports')

@section('content')

@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;
use App\Http\Controllers\MainController;

$owners_br = MainController::ownersBr();
$users = DB::table('users')->where('id', session()->get('user_id'))->get();

@endphp

<h3>Reports</h3>

<section class="section">

 <div class="loader">

 <img src="{{asset('images/Ripple-1s-200px.svg')}}" alt="preloader">

 <span>Please Wait...</span>

 </div>

    <div class="card">

        <div class="row py-3 px-4">

            @php

                if(session()->get('role_id') == 1) {

                $available = DB::table('rooms')->where('roomStatus','=',5)->count();

                $blocked = DB::table('rooms')->where('roomStatus','=',3)->count();

                $reservation = DB::table('rooms')->where('roomStatus','=',6)->count();

                $unclean = DB::table('rooms')->where('roomStatus','=',7)->count();

                $occupied = DB::table('rooms')->where('roomStatus','=',8)->count();

                $todayCheckout = DB::table('booking')->whereDate('checkout_date','<=',date('Y-m-d'))

                                                        ->where('checkout_by', 'occupied')

                                                        ->count();
                }else if(session()->get('role_id') == 5){
                $available = DB::table('rooms')->where('roomStatus','=',5)->whereIn('brCode', $owners_br)->count();
                $blocked = DB::table('rooms')->where('roomStatus','=',3)->whereIn('brCode', $owners_br)->count();
                $reservation = DB::table('rooms')->where('roomStatus','=',6)->whereIn('brCode', $owners_br)->count();
                $unclean = DB::table('rooms')->where('roomStatus','=',7)->whereIn('brCode', $owners_br)->count();
                $occupied = DB::table('rooms')->where('roomStatus','=',8)->whereIn('brCode', $owners_br)->count();
                $todayCheckout = DB::table('booking')->whereDate('checkout_date','<=',date('Y-m-d'))
                                                        ->where('checkout_by', 'occupied')
                                                        ->whereIn('brCode', $owners_br)
                                                        ->count();                                                        

                }else{

                

                $available = DB::table('rooms')->where('roomStatus', 5)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                $blocked = DB::table('rooms')->where('roomStatus', 3)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                $reservation = DB::table('rooms')->where('roomStatus', 6)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                $unclean = DB::table('rooms')->where('roomStatus', 7)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();        

                $occupied = DB::table('rooms')->where('roomStatus', 8)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                $todayCheckout = DB::table('booking')->whereDate('checkout_date','<=',date('Y-m-d'))

                                                ->where('brCode',session()->get('br_code'))

                                                ->where('checkout_by', 'occupied')

                                                ->count();

            

                }

            @endphp

            <div class="col-md-1 col-12"></div>

            <div class="col-md-2 col-12">

                <button type="button" class="btn btn-success">

                    Available <span class="badge bg-transparent">{{$available}}</span>

                </button>

            </div>

            <div class="col-md-2 col-12">

                <button type="button" class="btn btn-dark">

                    Blocked <span class="badge bg-transparent">{{$blocked}}</span>

                </button>

            </div>

            <div class="col-md-2 col-12">

                <button type="button" class="btn today-checkout">

                    T. Checkout <span class="badge bg-transparent">{{$todayCheckout}}</span>

                    {{-- Reservation <span class="badge bg-transparent">{{$reservation}}</span> --}}

                </button>

            </div>

            <div class="col-md-2 col-12">

                <button type="button" class="btn btn-primary">

                    UnClean <span class="badge bg-transparent">{{$unclean}}</span>

                </button>

            </div>

            <div class="col-md-2 col-12">

                <button type="button" class="btn btn-danger">

                    Occupied <span class="badge bg-transparent">{{$occupied}}</span>

                </button>

            </div>

            <div class="col-md-1 col-12"></div>

        </div>

        <div class="card-header">

            

        </div>

            @php



            if(session()->get('role_id') == 1) {

            

            $partial_amount = DB::table('booking')->where('balance', '!=', 0)

                                                  ->where('checkout_by','occupied')  

                                                  ->get();

            }else if(session()->get('role_id') == 5) {

            $partial_amount = DB::table('booking')->where('balance', '!=', 0)

                                                  ->where('checkout_by','occupied')  
                                                  
                                                  ->whereIn('brCode', $owners_br)

                                                  ->get();  

            }else{    

            $partial_amount = DB::table('booking')->where('balance', '!=', 0)

                                                  ->where('checkout_by','occupied')  

                                                  ->where('brCode', session()->get('br_code'))  

                                                  ->get();

            }    

            @endphp

           <table class="table table-striped" id="table1">

                <thead>

                    <tr>

                        <th style="text-align: center;">S.No</th>

                        <th>Br.Name</th>
                        
                        <th  style="text-align: center;">Booking Ref</th>

                        <th style="text-align: center;">Room No</th>

                        <th>Customer Name</th>

                        <th style="text-align: center;">Mobile</th>

                        <th style="text-align: center;">CheckIn Date</th>

                        <th style="text-align: center;">CheckOut Date</th>

                        <th style="text-align: center;">Balance Amount</th>

                    </tr>

                </thead>

                <tbody>

                   @php

                   $counter = 1;    

                   @endphp 

                   @foreach($partial_amount as $partial)
                   @php
                   $branch = DB::table('branchs')->where('brCode', $partial->brCode)->get(); 
                   @endphp

                   @if(date('Y-m-d h:i:s') >= $partial->checkout_date)

                   <tr style="background-color: #f794f2;color:rgb(0, 0, 0);">

                       <td style="text-align: center;">{{ $counter++ }}</td>
                       
                       <td>{{ $branch[0]->brName ? $branch[0]->brName : "Branch not added" }}</td>
                       
                       <td style="text-align: center;">{{ $partial->id }}</td>

                       <td style="text-align: center;">{{ $partial->room_no }}</td>

                       <td>{{ $partial->customer_name }}</td>

                       <td style="text-align: center;"><a style="color:rgb(0, 0, 0);" href="tel:{{ $partial->mobile_no }}">{{ $partial->mobile_no }}</a></td>

                       <td style="text-align: center;">{{ $partial->checkin_date }}</td>

                       <td style="text-align: center;">{{ $partial->checkout_date }}</td>

                       <td style="text-align: center;">{{ $partial->balance }}</td>

                   </tr>

                   @else

                   <tr>

                    <td style="text-align: center;">{{ $counter++ }}</td>

                    <td>{{ $branch[0]->brName ? $branch[0]->brName : "Branch not added" }}</td>

                    <td style="text-align: center;">{{ $partial->id }}</td>

                    <td style="text-align: center;">{{ $partial->room_no }}</td>

                    <td>{{ $partial->customer_name }}</td>

                    <td style="text-align: center;"><a href="tel:{{ $partial->mobile_no }}">{{ $partial->mobile_no }}</a></td>

                    <td style="text-align: center;">{{ $partial->checkin_date }}</td>

                    <td style="text-align: center;">{{ $partial->checkout_date }}</td>

                    <td style="text-align: center;">{{ $partial->balance }}</td>

                </tr>

                   @endif

                  @endforeach

                </tbody>

            </table>

            



</div>

</section>

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    $(document).ready(function(){



        setTimeout(() => {

                 $(".alert").slideUp();

            }, 3000);

                

    });



</script>



@endsection

