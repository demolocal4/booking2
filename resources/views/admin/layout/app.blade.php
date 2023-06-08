<?php 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MainController;
use Carbon\Carbon;

$data = MainController::menu();
$owners_br = MainController::ownersBr();
if($owners_br){$brc = implode(',', $owners_br);}

$new_menu = DB::table('users')->where('id', session()->get('user_id'))->first();

$a = explode(',', $new_menu->pages);

$b = implode(',', $a);



$nav = DB::select("SELECT * from menus WHERE id IN($b) order by m_order asc");

$masternav = [3,4,5,7,10,31,35];

$booknav = [11,14,30];

$afterBooking = [28,29];

$othersnav = [1,2];

$booking_com = [32,33,34];



$booking_nav = DB::table('menus')->orderBy('m_order', 'asc')->where('access', session()->get('role_id'))

                                  ->whereIn('id', [11,13,14,15,17,18,26,27])  

                                  ->get();

$forall = DB::table('menus')->orderBy('m_order', 'asc')->where('access', session()->get('role_id'))

                                  ->whereNotIn('id', [11,13,14,3,4,5,7,9,10,12,15,16,17,18,20,21,22,23,24,25,26,27])  

                                  ->get();                                   



$branch = DB::table('branchs')->where('brCode', session()->get('br_code'))->first();

$year = date('Y');

if(session()->get('role_id') ==1) {

$jan = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-01-01')) ."'");

}else if(session()->get('role_id') ==5) {

$jan = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-01-01')) ."' AND brCode IN($brc)");

}else{

$jan = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-01-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1) {

$fab = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-02-01')) ."'");

}else if(session()->get('role_id') ==5) {

$fab = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-02-01')) ."' AND brCode IN($brc) "); 

}else{

$fab = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-02-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$mar = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-03-01')) ."'");

}else if(session()->get('role_id') ==5) {

$mar = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-03-01')) ."' AND brCode IN($brc) ");

}else{

$mar = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-03-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$apr = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-04-01')) ."'");

}else if(session()->get('role_id') ==5) {

$apr = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-04-01')) ."' AND brCode IN($brc) ");

}else{

$apr = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-04-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$may = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-05-01')) ."'");

}else if(session()->get('role_id') ==5) {

$may = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-05-01')) ."' AND brCode IN($brc) ");

}else{

$may = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-05-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$jun = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-06-01')) ."'");

}else if(session()->get('role_id') ==5) {

$jun = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-06-01')) ."' AND brCode IN($brc) "); 

}else{

$jun = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-06-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$jul = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-07-01')) ."'");

}else if(session()->get('role_id') ==5) {

$jul = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-07-01')) ."' AND brCode IN($brc) ");

}else{

$jul = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-07-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$aug = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-08-01')) ."'");

}else if(session()->get('role_id') ==5) {

$aug = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-08-01')) ."' AND brCode IN($brc) ");

}else{

$aug = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-08-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$sep = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-09-01')) ."'");

}else if(session()->get('role_id') ==5) {

$sep = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-09-01')) ."' AND brCode IN($brc) "); 

}else{

$sep = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-09-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$oct = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-10-01')) ."'");

}else if(session()->get('role_id') ==5) {

$oct = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-10-01')) ."' AND brCode IN($brc) ");

}else{

$oct = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-10-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$nov = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-11-01')) ."'");

}else if(session()->get('role_id') ==5) {

$nov = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-11-01')) ."' AND brCode IN($brc) ");

}else{

$nov = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-11-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



if(session()->get('role_id') ==1){

$dec = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-12-01')) ."'");

}else if(session()->get('role_id') ==5) {

$dec = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-12-01')) ."' AND brCode IN($brc) ");

}else{

$dec = DB::select("Select count(*) as num_booking from booking WHERE MONTH(checkin_date) = '". date('m',strtotime('2022-12-01')) ."' AND brCode = '".session()->get('br_code')."'");

}



// $booking_chart = [30,40,45,50,49,60,70,91,125,130,150,170];
$currYear = date('Y');
$avg_jan = Carbon::create($currYear, 1);
$avg_feb = Carbon::create($currYear, 2);
$avg_mar = Carbon::create($currYear, 3);
$avg_apr = Carbon::create($currYear, 4);
$avg_may = Carbon::create($currYear, 5);
$avg_jun = Carbon::create($currYear, 6);
$avg_jul = Carbon::create($currYear, 7);
$avg_aug = Carbon::create($currYear, 8);
$avg_sep = Carbon::create($currYear, 9);
$avg_oct = Carbon::create($currYear, 10);
$avg_nov = Carbon::create($currYear, 11);
$avg_dec = Carbon::create($currYear, 12);
$booking_chart = [

            round($jan[0]->num_booking / $avg_jan->daysInMonth, 2),

            round($fab[0]->num_booking / $avg_feb->daysInMonth, 2),

            round($mar[0]->num_booking / $avg_mar->daysInMonth, 2),

            round($apr[0]->num_booking / $avg_apr->daysInMonth, 2),

            round($may[0]->num_booking / $avg_may->daysInMonth, 2),

            round($jun[0]->num_booking / $avg_jun->daysInMonth, 2),

            round($jul[0]->num_booking / $avg_jul->daysInMonth, 2),

            round($aug[0]->num_booking / $avg_aug->daysInMonth, 2),

            round($sep[0]->num_booking / $avg_sep->daysInMonth, 2),

            round($oct[0]->num_booking / $avg_oct->daysInMonth, 2),

            round($nov[0]->num_booking / $avg_nov->daysInMonth, 2),

            round($dec[0]->num_booking / $avg_dec->daysInMonth, 2),


];



if(session()->get('role_id') == 1) {

                $available = DB::table('rooms')->where('roomStatus','=',5)->count();

                $blocked = DB::table('rooms')->where('roomStatus','=',3)->count();

                $unclean = DB::table('rooms')->where('roomStatus','=',7)->count();

                $occupied = DB::table('rooms')->where('roomStatus','=',8)->count();

                }else if(session()->get('role_id') == 5) { 



                $available = DB::table('rooms')->where('roomStatus','=',5)->whereIn('brCode', $owners_br)->count();

                $blocked = DB::table('rooms')->where('roomStatus','=',3)->whereIn('brCode', $owners_br)->count();

                $unclean = DB::table('rooms')->where('roomStatus','=',7)->whereIn('brCode', $owners_br)->count();

                $occupied = DB::table('rooms')->where('roomStatus','=',8)->whereIn('brCode', $owners_br)->count(); 



                }else{

                $available = DB::table('rooms')->where('roomStatus', 5)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                $blocked = DB::table('rooms')->where('roomStatus', 3)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                

                $unclean = DB::table('rooms')->where('roomStatus', 7)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();        

                $occupied = DB::table('rooms')->where('roomStatus', 8)

                                                ->where('brCode', session()->get('br_code')) 

                                                ->count();

                }

$room_chart = [$unclean,$available,$blocked,$occupied]; 



?>



<!DOCTYPE html>



<html lang="en">



<meta http-equiv="content-type" content="text/html;charset=utf-8" />



<head>



    <style>



         .sidebar-wrapper .sidebar-header img {



            height: 6.2rem !important;



          }



          .modal .modal-header .close {



              padding: 0 !important;



              border: none !important;



              outline: none !important;



              



          }



          .bi-x-circle {



            font-size: 22px !important;



          }



          .modal .modal-header .close:hover {



              background-color: transparent !important;



          }



            .loader {



                position: fixed;



                display: none;



                width: 100px;



                height: 100px;



                padding: 5px;



                left: 50%;



                top: 50%;



                transform: translate(-50%, -50%);



                z-index: 999999;



                background-color: #1558d4;



                border-radius: 5px;



           }



           .loader img {



                width: 100%;



                margin-bottom: 5px;



           }



           .loader span {



               font-size: 13px;



           }



           .sidebar-wrapper .menu .sidebar-link {



               padding: 0.2rem 0.2rem !important;



           }



           #booking_status {



               width: 100%;



               height: 300px;



           }



           .dcalendarpicker .dudp__wrapper {

                bottom: 300px !important;

            }



           @page {



            size: A4;



            margin-top: 20px;



            margin-left:10px;



            margin-bottom: 10px;



            margin-right:10px;



            }



    </style>



    <meta charset="UTF-8">



    <meta name="viewport" content="width=device-width, initial-scale=1.0">



    <title>



        @hasSection ('page_title')



        @yield('page_title')    



        @else



        {{ config('app.name') }}    



        @endif



    </title>

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/choices.js/choices.min.css')}}">

    <link rel="preconnect" href="https://fonts.gstatic.com/">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/simple-datatables/style.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/iconly/bold.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/perfect-scrollbar/perfect-scrollbar.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css')}}">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{asset('admin/assets/vendors/toastify/toastify.css')}}">

    <link rel="stylesheet" href="{{asset('admin/assets/css/app.css')}}">

    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.css')}}">

    <link rel="stylesheet" href="{{ asset('css/duDatepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/duDatepicker-theme.css') }}">



    <link rel="stylesheet" href="{{ asset('css/slick.css?v2022') }}">

    <link rel="stylesheet" href="{{ asset('css/slick-theme.css?v2022') }}">

    <link rel="shortcut icon" href="{{asset('images/favicon-32x32.png')}}" type="image/x-icon">

	<link rel="icon" href="{{asset('images/favicon-32x32.png')}}" type="image/x-icon">



    



</head>







<body>
<a name="top" id="top"></a>


<div class="loader">



<img src="{{asset('images/Ripple-1s-200px.svg')}}" alt="preloader">



<span>Please Wait...</span>



</div>      



<div id="app">



    <div id="sidebar" class="active">



        <div class="sidebar-wrapper active">



            <div class="sidebar-header">



                <div class="d-flex justify-content-between">



                    



                    <div class="logo">



                        @if(session()->get('role_id') == 1 || session()->get('role_id') == 5)



                        <a href="javascript:void(0);"><img src="{{asset('uploads/logo.png')}}" alt="Logo" srcset=""></a>



                        @else



                        <a href="javascript:void(0);"><img src="{{asset('uploads/'.$branch->logo)}}" alt="Logo" srcset=""></a>



                        @endif



                    </div>



                    <div class="toggler">



                        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>



                    </div>



                </div>



            </div>



            <div class="sidebar-menu">

                <ul class="menu">

                    <li class="sidebar-item {{ Request::is('application') ? 'active' : '' }}">

                        <a href="{{ route('application') }}" class='sidebar-link'>

                            <i class="bi bi-grid-fill"></i>

                            <span>Dashboard</span>

                        </a>

                    </li>

                    <li class="sidebar-item" style="padding: 10px 0;">

                        {{-- <a href="#" class="sidebar-link"> --}}

                            <i class="bi bi-stack"></i>

                            <span>Master</span>

                        {{-- </a> --}}

                        <ul class="submenu" style="display: block;">

                        @foreach($nav as $item)

                        @if(in_array($item->id, $masternav))

                        <li class="sidebar-item {{ Request::is($item->url) ? 'active' : '' }}">

                        <a href="{{ url($item->url) }}" class='sidebar-link'>

                            <i class="{{$item->icon}}"></i>

                            <span>{{$item->name}}</span>

                        </a>

                        </li>

                        @endif

                        @endforeach



                        </ul>



                    </li>



                    <li class="sidebar-item">

                        {{-- <a href="#" class="sidebar-link"> --}}

                            <i class="bi bi-journal-plus"></i>

                            <span>Booking</span>

                        {{-- </a> --}}

                    <ul class="submenu" style="display: block;">

                            @foreach($nav as $item1)

                            @if(in_array($item1->id,$booknav))

                        <li class="sidebar-item  {{ Request::is($item1->url) ? 'active' : '' }}">

                        <a href="{{ url($item1->url) }}" class='sidebar-link'>

                            <i class="{{$item1->icon}}"></i>

                            <span>{{$item1->name}}</span>

                        </a>

                        </li>

                    @endif      

                    @endforeach

                    </ul>

                    </li>



                    <li class="sidebar-item">

                    <i class="bi bi-check2-circle"></i>

                    <span>Booking.com</span>    

                    <ul class="submenu" style="display: block;">

                        @foreach($nav as $bookNav1)

                        @if(in_array($bookNav1->id, $booking_com))

                            <li class="sidebar-item  {{ Request::is($bookNav1->url) ? 'active' : '' }}">

                            <a href="{{ url($bookNav1->url) }}" class='sidebar-link'>

                                <i class="{{$bookNav1->icon}}"></i>

                                <span>{{$bookNav1->name}}</span>

                            </a>

                            </li>

                        @endif

                        @endforeach

                        <li class="sidebar-item  {{ Request::is('bookingComReports') ? 'active' : '' }}">
                        <a href="{{ url('bookingComReports') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-ruled"></i>    
                        <span>Booking.com Report</span>
                        </a>
                        </li>
                        <li class="sidebar-item  {{ Request::is('bookingComReports/daily_reports') ? 'active' : '' }}">
                        <a href="{{ url('bookingComReports/daily_reports') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-ruled"></i>    
                        <span>Daily Booking.com Report</span>
                        </a>
                        </li>

                    </ul>

                    </li>





                    <li class="sidebar-item  {{ Request::is('partial_amount') ? 'active' : ''}}">

                        <a href="{{ route('partial_amount') }}" class='sidebar-link'>

                            <i class="bi bi-cash-stack"></i>

                            <span>Partial Amount</span>

                        </a>

                    </li>



                    @foreach($nav as $pettyChash)

                    @if(in_array($pettyChash->id,$afterBooking))

                    <li class="sidebar-item {{ Request::is($pettyChash->url) ? 'active' : '' }}">

                        <a href="{{ url($pettyChash->url) }}" class='sidebar-link'>

                            <i class="{{$pettyChash->icon}}"></i>

                            <span>{{$pettyChash->name}}</span>

                        </a>

                    </li>

                    @endif

                    @endforeach







                    <li class="sidebar-item">
                       {{-- <a href="#" class="sidebar-link"> --}}
                            <i class="bi bi-file-spreadsheet-fill"></i>
                            <span>Reports</span>
                        {{-- </a> --}}
                        <ul class="submenu" style="display: block;">
                            <li class="sidebar-item  {{ Request::is('reports') ? 'active' : '' }}">
                            <a href="{{ url('reports') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-ruled"></i>    
                           <span>Cash Reports</span>
                           </a>
                           </li>
                           <li class="sidebar-item  {{ Request::is('daily_reports') ? 'active' : '' }}">
                           <a href="{{ url('daily_reports') }}" class='sidebar-link'>
                           <i class="bi bi-file-earmark-ruled"></i>    
                           <span>Daily Cash Report</span>
                           </a>
                           </li>
                           <li class="sidebar-item  {{ Request::is('collection_reports') ? 'active' : '' }}">
                            <a href="{{ url('collection_reports') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-ruled"></i>    
                            <span>Collection Report</span>
                            </a>
                            </li>
                        </ul>
                    </li>
                    @if(session()->get('role_id') == 1)
                    <li class="sidebar-item">
                        {{-- <a href="#" class="sidebar-link"> --}}
                            <i class="bi bi-sliders"></i>
                            <span>Setting</span>
                        {{-- </a> --}}
                        <ul class="submenu" style="display: block;">
                            <li class="sidebar-item  {{ Request::is('general_setting') ? 'active' : '' }}">
                            <a href="{{ url('general_setting') }}" class='sidebar-link'>
                            <i class="bi bi-gear"></i>
                            <span>General Settings</span>
                            </a>
                            </li>

                            {{-- add new code by tayyab @ 06-08-2023 --}}
                            
                            <li class="sidebar-item  {{ Request::is('general_setting') ? 'active' : '' }}">
                            <a href="{{ url('user_tracking') }}" class='sidebar-link'>
                            <i class="bi bi-clock-history"></i>
                            <span>User Track Report</span>
                            </a>
                            </li>
                        </ul>
                    </li>



                    @endif















                    



                    @foreach($nav as $item2)

                    @if(in_array($item2->id,$othersnav))

                    <li class="sidebar-item {{ Request::is($item2->url) ? 'active' : '' }}">



                        <a href="{{ url($item2->url) }}" class='sidebar-link'>



                            <i class="{{$item2->icon}}"></i>



                            <span>{{$item2->name}}</span>



                        </a>



                    </li>

                    @endif

                    @endforeach







                          



                    {{-- {{-- <li class="sidebar-item  ">



                        <a href="#" class='sidebar-link'>



                            <i class="bi bi-envelope-fill"></i>



                            <span>Email</span>



                        </a>



                    </li> --}}





                    <li class="sidebar-item  ">



                        @if(session()->get('role_id') != 3)

                        <a href="{{ route('logout') }}" class='sidebar-link'>

                            <i class="bi bi-key-fill"></i>

                            <span>Logout</span>

                        </a>

                        @else

                        <a href="javascript:void(0);" class='sidebar-link' data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#logoutAction">

                            <i class="bi bi-key-fill"></i>

                            <span>Logout</span>

                        </a>    

                        @endif



                    </li>	





                    {{-- <li class="sidebar-item  ">



                        <a href="{{ route('logout') }}" class='sidebar-link'>



                            <i class="bi bi-key-fill"></i>



                            <span>Logout</span>



                        </a>



                    </li> --}}







                </ul>



            </div>



            <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>



        </div>



    </div>







    <div id="main">



        <header class="mb-3">



            <a href="#" class="burger-btn d-block d-xl-none">



                <i class="bi bi-justify fs-3"></i>



            </a>



        </header>

        
        @section('content')

        @show



            



        <footer>



            <div class="footer clearfix mb-0 text-muted">



                <div class="float-start">



                    <p>2022 &copy; {{ config('app.name') }}</p>



                </div>



                <div class="float-end">



                    <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a



                            href="#">fajri.com</a></p>



                </div>



            </div>



        </footer>



    </div>



</div>



<div class="modal fade text-left" id="logoutAction" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel4">Action Required

                </h4>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <i class="bi bi-x-circle"></i>

                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6 col-12">

                        <a href="javascript:void(0);" id="temporary" data-id="{{ session()->get('user_id') }}" class="btn btn-outline-primary" style="width: 100%">Temporary Logout</a>

                    </div>

                    <div class="col-md-6 col-12">

                        <a href="{{ url('shift_change/'.session()->get('user_id')) }}" id="shift_change" data-id="{{ session()->get('user_id') }}" class="btn btn-outline-dark" style="width: 100%">Shift Change</a>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                

            </div>

        </div>

    </div>

</div>







<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script src="{{asset('js/jquery.table2excel.min.js')}}"></script>

<script src="{{asset('js/kinzi.print.min.js')}}"></script>

<script src="{{ asset('js/duDatepicker.min.js') }}"></script>

<script src="{{ asset('js/slick.js?v2022') }}"></script>

<script src="{{asset('js/jquery.datetimepicker.full.min.js')}}"></script>

<script src="{{asset('js/divjs.js')}}"></script>

<script src="{{asset('admin/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

<script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('admin/assets/vendors/simple-datatables/simple-datatables.js')}}"></script>

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script src="{{asset('admin/assets/vendors/toastify/toastify.js')}}"></script>

<script src="{{asset('admin/assets/vendors/apexcharts/apexcharts.js')}}"></script>

<script src="{{asset('admin/assets/js/pages/dashboard.js')}}"></script>

<script src="{{asset('admin/assets/vendors/choices.js/choices.min.js')}}"></script>

<script src="{{asset('admin/assets/js/main.js')}}"></script>



</body>



</html>



<script>



    var options = {

	  chart: {

	    type: 'bar',

	    height:450,

	    stacked:true,

	    zoom:{enabled:true}

  		},

  		

  series: [{

    name: 'Bookings',

    data: <?php echo json_encode($booking_chart); ?> 

  // },{



  // 	name: 'Values',

  // 	data: [200,350,400,450,600,780,900,1500,1650,1300,1400,1000]

  

  }],



  xaxis: {



    categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']



  }



}



var chart = new ApexCharts(document.querySelector("#booking_status"), options);

chart.render();



// chart-room-status

var rooms = {

      chart: {

        width: 300,

        type: 'donut',

      },

      labels: ['UnClean',

        'Available',

        'Blocked',

        'Occupied',

      ],

    //   series: [202, 80, 62, 250],

      series: <?php echo json_encode($room_chart); ?>, 

      dataLabels: {

        formatter: function (val, opts) {

            return opts.w.config.series[opts.seriesIndex]

        },

      },

    }

var chart2 = new ApexCharts(document.querySelector("#chart-room-status"), rooms);

chart2.render();

// chart-room-status



    let table1 = document.querySelector('#table1');

    let dataTable = new simpleDatatables.DataTable(table1); 

    $(document).ready(function () {



                   



            // $("#photo").on("change", function(e){



            //     let [filename] = e.target.files;



            //     $(".profile-pic").attr("src", URL.createObjectURL(filename));



        



            // });







            $("#floor").keypress(function(e) {



                    var key = e.keyCode;



                    if (key >= 48 && key <= 57) {



                        e.preventDefault();



                    }



            });



            







            setTimeout(() => {



                 $(".alert").slideUp();



            }, 3000);







            $("#floor").on("change", function(){







                    $("#roomNo").val($(this).val());

                    $("#floorRef").val($(this).find(":selected").data('floor_id'));





            });







            $("#myBody").on("click", ".edit", function(e){



                e.preventDefault();



                let url = $(this).attr('href');



                



                $(".loader").show();



                $.get(url, function(response){



                    $(".loader").hide();



                    $("#push_id").val(response.id);



                    // $('#updatebrCode option[value="'+response.brCode+'"]').prop('selected', true);

                    $('#updatebrCode').html($('#updatebrCode').find('option').filter('[value="'+ response.brCode +'"]')); 



                    $('#updatefloor option[value="'+response.floor+'"]').prop('selected', true);



                    $("#updateroomNo").val(response.roomNo);



                    $('#updateroomStatus option[value="'+response.roomStatus+'"]').prop('selected', true);



                    $('#updateroomType option[value="'+response.roomType+'"]').prop('selected', true);



                    $('#updatenobeds option[value="'+response.no_beds+'"]').prop('selected', true);



                    $('#updateremarks').val(response.remarks);



                    $('#updatefloor').html($('#updatefloor').find('option').filter('[data-branch="'+ response.brCode +'"]'));

                    $('#updateroomType').html($('#updateroomType').find('option').filter('[data-branch1="'+ response.brCode +'"]'));



                    $("#floorRef_update").val($("#updatefloor").find(":selected").data('floor_id'));



                    if($('#updateroomStatus').val() == 8) {

                        $("#btn_update_room").prop("disabled",true);

                        }else{

                        $("#btn_update_room").prop("disabled",false);

                    }



                });







            });



            

            

            $("#rtBody").on("click", ".btn-edit", function(e){

                e.preventDefault();

                let url = $(this).attr('href');

                $(".loader").show();

                $.get(url, function(response){

                    $(".loader").hide();

                        $.each(response, function(key, val) {

                               $(".elements").each(function(){

                                       if($(this).attr('name') == key) {

                                           $(this).val(val);

                                       }

                               });

                        });

                    

                        $('#roomType2').html($('#roomType2').find('option').filter('[data-id="'+ response.roomtype_id +'"]'));    

                        $("#typeId2").val(response.roomtype_id);

                });

                

                

            });







            



            $("#floorBody").on("click", ".btn-edit", function(e){



             e.preventDefault();



             let url = $(this).attr('href');



             $(".loader").show();



             $.get(url, function(response){



                $(".loader").hide();  



                



                



                $("#btn-update").data('id', response.id);



                // branch



                $('.elements option[value="'+ response.brCode +'"]').prop('selected', true);

                

                //$('.elements').html($('.elements').find('option').filter('[value="'+ response.brCode +'"]'));  

                

                $.each(response, function(key, val) {



                    $(".elements").each(function(){



                            if($(this).attr('name') == key) {



                                $(this).val(val);



                                



                            }







                    });











                    });











                 $("#new").css("display", "none");



                 $("#btn-update").css("display", "block");







             });







            });







            $("#btn-update").on("click", function(e){



             e.preventDefault();



             let id = $(this).data('id');



             let data = [];







             $(".elements").each(function(){



                if($(this).val() != '') { 



                data.push($(this).val());



                }



             });







             $.ajax({



                    url: '{{url("manage_floors")}}/'+id,



                    type:'PUT',



                    data: {data: data, _token:'{{ csrf_token() }}'},



                    success:function(response) {



                        let data = $.parseJSON(response);



                        if(data.status == 'success') {



                            if(!alert(data.message)) {



                                window.location.reload();   



                            }else{



                                return 0



                            }







                        }







                        if(data.status == 'fail') {



                            alert(data.message);



                        }



                    }



                 



             });







            });











            $(".brdelete").on("click",function(e){



                if(confirm('Are you sure?')) {



                    $(this).find('form').submit();                    



                }else{



                    event.stopPropagation();



                }







            });







            $(".statusDelete").on("click",function(e){



                if(confirm('Are you sure?')) {



                    $(this).find('form').submit();                    



                }else{



                    event.stopPropagation();



                }







            });







            $(".typeDelete").on("click",function(e){



                if(confirm('Are you sure?')) {



                    $(this).find('form').submit();                    



                }else{



                    event.stopPropagation();



                }







            });    







            $(".floorDelete").on("click",function(e){



                if(confirm('Are you sure?')) {



                    $(this).find('form').submit();                    



                }else{



                    event.stopPropagation();



                }







            });        







            $(".roomDelete").on("click",function(e){



                if(confirm('Are you sure?')) {



                    $(this).find('form').submit();                    



                }else{



                    event.stopPropagation();



                }







            });



            $(".user_delete").on("click",function(e){



                if(confirm('Are you sure?')) {



                    $(this).find('form').submit();                    



                }else{



                    event.stopPropagation();



                }







            });





            $("#brachBody").on("click",".more",function(){



                    let id = $(this).data('id');



                    $("#backdrop-"+id).modal('show');







            });



            



            $("#rtBody").on("click",".more",function(){



                let id = $(this).data('id');



                $("#moreinfo-"+id).modal('show');



            });







            $("#floorBody").on("click",".more",function(){



                let id = $(this).data('id');



                $("#floorModal-"+id).modal('show');



            });







            $("#myBody").on("click",".more",function(){



                let id = $(this).data('id');



                $("#roomModal-"+id).modal('show');



            });





            var select1 = $('#brCode'),

                select2 = $('#floor'),

                select3 = $('#roomType'),

                options = select2.find('option');

                options1 = select3.find('option');



                select1.on('change',function(){



                        let code = $(this).val();

                        select2.html(options.filter('[data-bcode="'+ code +'"]'));

                        select3.html(options1.filter('[data-bcode1="'+ code +'"]'));

                        if(select2.val('')) {

                            select2.attr('required','required');

                        }else{

                            select2.removeAttr('required');

                        }



                        if(select3.val('')) {

                            select3.attr('required','required');

                        }else{

                            select3.removeAttr('required');

                        }





                }).trigger('change');

            

                

                 

                // $("#updatebrCode").on("change",function(){



                //        let code = $(this).val();

                //        $('#updatefloor').html($('#updatefloor').find('option').filter('[data-branch="'+ code +'"]'));

                //        if($('#updatefloor').val('')) {

                //            $('#updatefloor').attr('required','required');

                //        }else{

                //             $('#updatefloor').removeAttr('required');                        

                //        }



                // });



                // $("#updatefloor").on("change",function(){



                //         let id = $(this).find(':selected').data('branch')

                //         alert(id);



                // });



            // $("#brCode").on("change",function(){



            //     //$("#floor option").hide();

            //     let code = $(this).val();

            //     $('#floor').html($('#floor').find('option').filter('[data-bcode="'+ code +'"]'));





            // });



            // $("#floor").on("change",function(){



            //         let code = $(this).find(':selected').data('bcode');

            //         alert(code);



            // });



    });



</script>



<script>

        

    $(document).ready(function(){

        $(".regular").slick({

            // dots: true,

            infinite: false,

            loop:false,

            variableWidth: true,

            cssEase: 'linear',

            slidesToShow: 4,

            slidesToScroll: 4,

            focusOnSelect: true,

            touchMove: true,

            responsive: [

            {

            breakpoint: 1024,

            settings: {

                slidesToShow: 4,

                slidesToScroll: 4,

               

            }

            },

            {

            breakpoint: 850,

            settings: {

                slidesToShow: 3,

                slidesToScroll: 3,

               

            }

            },

            {

            breakpoint: 760,

            settings: {

                slidesToShow: 2,

                slidesToScroll: 2,

                

            }

            },

            {

            breakpoint: 480,

            settings: {

                slidesToShow: 1,

                slidesToScroll: 1,

              

            }

            }

        ]

        });

            // temporary logout

            $("#temporary").on("click", function(){

                let user = $(this).data('id');

                $.get('/temporary/',{user_id:user, status:0}, function(response){

                    if(response.status == 'success') {

                        if(!alert(response.message)) {

                            window.location.href = '{{ route("logout") }}';

                        }

                    }

                    if(response.status == 'fail') {

                        alert(response.message);

                    }

                });

            });



            duDatepicker('#daterange', {

            format: 'yyyy/mm/dd', range: true, clearBtn: true, minDate:'today',

            });

            duDatepicker('#date', {

            format: 'yyyy-mm-dd', range: false, clearBtn: true, minDate:'today',    

            });



            $("#branches").on("change",function(){

                $("#fmFilter").submit();

            });



            $("#roomType1").on("change", function(){

                let id = $(this).find("option:selected").data('id');

                $("#typeId").val(id);

            });

            $("#roomType2").on("change", function(){

                let id = $(this).find("option:selected").data('id');

                $("#typeId2").val(id);

            });



            var allTypes = $("#roomType1").html();

            $("#brCode_1").on("change",function(){

                let brc = $(this).val();

                $("#roomType1").html(allTypes);

                $("#roomType1").html($("#roomType1").find("option").filter("[data-brc='"+ brc +"']"));

                $("#roomType1").prepend("<option value='' selected disabled>Select Type</option>");

            });



            

            $("#brCode_2").on("change",function(){

                let brc = $(this).val();   

                $("#roomType2").html(allTypes);

                $("#roomType2").html($("#roomType2").find("option").filter("[data-brc='"+ brc +"']"));

                $("#roomType2").prepend("<option value='' disabled selected>Select</option>");

            });

             



    });

    

</script>











