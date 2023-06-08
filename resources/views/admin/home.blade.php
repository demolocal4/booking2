@extends('admin.layout.app')



@section('page_title', 'User Dashboard')



@section('content')



@php



use Illuminate\Support\Facades\DB; 

use App\Http\Controllers\MainController;



$owners_br = MainController::ownersBr();

$branch = DB::table('branchs')->where('brCode', session()->get('br_code'))->first();



@endphp



    <div class="page-heading">



        @if(session()->get('role_id') == 1)



        <h3>{{ config('app.name') }}</h3>



        @else



        <h3>{{ $branch->brName }}</h3>



        @endif







    </div>    <div class="page-content">



        <section class="row">



            <div class="col-12 col-lg-9">



                <div class="row">



                    @if(session()->get('role_id') == 1)



                    <div class="col-6 col-lg-3 col-md-6">



                        <div class="card">



                            <div class="card-body px-3 py-4-5">



                                <div class="row">



                                    <div class="col-md-4">



                                        <div class="stats-icon purple">



                                            <i class="bi bi-building"></i>



                                        </div>



                                    </div>



                                    <div class="col-md-8">



                                        @php



                                        $branchcount = DB::table('branchs')->count();



                                        @endphp



                                        <h6 class="text-muted font-semibold">Total Branches</h6>



                                        <h6 class="font-extrabold mb-0">{{ $branchcount }}</h6>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                    @endif    











                    <div class="col-6 col-lg-3 col-md-6">



                        <div class="card">



                            <div class="card-body px-3 py-4-5">



                                <div class="row">



                                    <div class="col-md-4">



                                        <div class="stats-icon blue">



                                            <i class="bi bi-door-open"></i>



                                        </div>



                                    </div>



                                    <div class="col-md-8">

                                        <!-- booking -->

                                        @php



                                        if(session()->get('role_id') == 1) {



                                        $roomscount = DB::table('rooms')->count();

                                        $occupancy = DB::table('rooms')->where('roomStatus', 8)->count();



                                        }else if(session()->get('role_id') == 5) {

                                        $roomscount = DB::table('rooms')->whereIn('brCode', $owners_br)->count();

                                        $occupancy = DB::table('rooms')->where('roomStatus', 8)->whereIn('brCode', $owners_br)->count(); 



                                        }else{



                                        $roomscount = DB::table('rooms')->where('brCode', session()->get('br_code'))->count();    

                                        $occupancy = DB::table('rooms')->where('brCode', session()->get('br_code'))

                                                                        ->where('roomStatus', 8)

                                                                         ->count();

                                        }



                                        @endphp



                                        <h6 class="text-muted font-semibold">Total Rooms</h6>



                                        <h6 class="font-extrabold mb-0">{{ $roomscount }}</h6>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                    <div class="col-6 col-lg-3 col-md-6">



                        <div class="card">



                            <div class="card-body px-3 py-4-5">



                                <div class="row">



                                    <div class="col-md-4">



                                        <div class="stats-icon red">



                                            <i class="iconly-boldBookmark"></i>



                                        </div>



                                    </div>



                                    <div class="col-md-8">

                                        <!-- booking -->

                                        @php



                                        if(session()->get('role_id') == 1) {



                                        $booking = DB::table('rooms')->where('roomStatus', 8)->count();



                                        }else if(session()->get('role_id') == 5) {

                                        $booking = DB::table('rooms')->where('roomStatus', 8)->whereIn('brCode', $owners_br)->count();



                                        }else{



                                        $booking = DB::table('rooms')->where('brCode', session()->get('br_code'))->where('roomStatus', 8)->count();    



                                        }



                                        @endphp



                                        <h6 class="text-muted font-semibold">Occupied</h6>



                                        <h6 class="font-extrabold mb-0">{{ $booking }}</h6>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                    <div class="col-6 col-lg-3 col-md-6">



                        <div class="card">



                            <div class="card-body px-3 py-4-5">



                                <div class="row">



                                    <div class="col-md-4">



                                        <div class="stats-icon green">



                                            <i class="bi bi-bar-chart-line-fill"></i>



                                        </div>



                                    </div>



                                    <div class="col-md-8">



                                        <!-- @php



                                        if(session()->get('role_id') == 1) {



                                        $userscount = DB::table('users')->count();



                                        }else{



                                        $userscount = DB::table('users')->where('brCode', session()->get('br_code'))->count();    



                                        }



                                        @endphp -->



                                        <h6 class="text-muted font-semibold">T. Occupancy</h6>

                                        @if($roomscount > 0)

                                        <h6 class="font-extrabold mb-0">{{ round($occupancy/$roomscount*100,2) }} %</h6>

                                        @else

                                        <h6 class="font-extrabold mb-0">0 %</h6>

                                        @endif

                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                    



                </div>



                <div class="row" style="display: block;">



                    <div class="col-12">



                        <div class="card">



                            <div class="card-header">



                                <h4>Avg.Bookings</h4>



                            </div>



                            <div class="card-body">



                                <div id="booking_status"></div>



                                {{-- <div id="chart-profile-visit"></div> --}}



                            </div>



                        </div>



                    </div>



                </div>



                <div class="row" style="display: none;">



                    <div class="col-12 col-xl-4">



                        <div class="card">



                            <div class="card-header">



                                <h4>Profile Visit</h4>



                            </div>



                            <div class="card-body">



                                <div class="row">



                                    <div class="col-6">



                                        <div class="d-flex align-items-center">



                                            <svg class="bi text-primary" width="32" height="32" fill="blue"



                                                 style="width:10px">



                                                <use



                                                    xlink:href="assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill" />



                                            </svg>



                                            <h5 class="mb-0 ms-3">Europe</h5>



                                        </div>



                                    </div>



                                    <div class="col-6">



                                        <h5 class="mb-0">862</h5>



                                    </div>



                                    <div class="col-12">



                                        <div id="chart-europe"></div>



                                    </div>



                                </div>



                                <div class="row">



                                    <div class="col-6">



                                        <div class="d-flex align-items-center">



                                            <svg class="bi text-success" width="32" height="32" fill="blue"



                                                 style="width:10px">



                                                <use



                                                    xlink:href="assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill" />



                                            </svg>



                                            <h5 class="mb-0 ms-3">America</h5>



                                        </div>



                                    </div>



                                    <div class="col-6">



                                        <h5 class="mb-0">375</h5>



                                    </div>



                                    <div class="col-12">



                                        <div id="chart-america"></div>



                                    </div>



                                </div>



                                <div class="row">



                                    <div class="col-6">



                                        <div class="d-flex align-items-center">



                                            <svg class="bi text-danger" width="32" height="32" fill="blue"



                                                 style="width:10px">



                                                <use



                                                    xlink:href="assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill" />



                                            </svg>



                                            <h5 class="mb-0 ms-3">Indonesia</h5>



                                        </div>



                                    </div>



                                    <div class="col-6">



                                        <h5 class="mb-0">1025</h5>



                                    </div>



                                    <div class="col-12">



                                        <div id="chart-indonesia"></div>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                    <div class="col-12 col-xl-8">



                        <div class="card">



                            <div class="card-header">



                                <h4>Latest Comments</h4>



                            </div>



                            <div class="card-body">



                                <div class="table-responsive">



                                    <table class="table table-hover table-lg">



                                        <thead>



                                        <tr>



                                            <th>Name</th>



                                            <th>Comment</th>



                                        </tr>



                                        </thead>



                                        <tbody>



                                        <tr>



                                            <td class="col-3">



                                                <div class="d-flex align-items-center">



                                                    <div class="avatar avatar-md">



                                                        <img src="assets/images/faces/5.jpg">



                                                    </div>



                                                    <p class="font-bold ms-3 mb-0">Si Cantik</p>



                                                </div>



                                            </td>



                                            <td class="col-auto">



                                                <p class=" mb-0">Congratulations on your graduation!</p>



                                            </td>



                                        </tr>



                                        <tr>



                                            <td class="col-3">



                                                <div class="d-flex align-items-center">



                                                    <div class="avatar avatar-md">



                                                        <img src="assets/images/faces/2.jpg">



                                                    </div>



                                                    <p class="font-bold ms-3 mb-0">Si Ganteng</p>



                                                </div>



                                            </td>



                                            <td class="col-auto">



                                                <p class=" mb-0">Wow amazing design! Can you make another



                                                    tutorial for



                                                    this design?</p>



                                            </td>



                                        </tr>



                                        </tbody>



                                    </table>



                                </div>



                            </div>



                        </div>



                    </div>



                </div>



            </div>



            <div class="col-12 col-lg-3">



                <div class="card">



                    <div class="card-body py-4 px-5">



                        <div class="d-flex align-items-center">



                            <div class="avatar avatar-xl">



                                <img src="{{asset('uploads/'.$userinfo[0]->profile_pic)}}" alt="Profile Photo">



                            </div>



                            <div class="ms-3 name">



                                <h5 class="font-bold">{{ ucfirst($userinfo[0]->name); }}</h5>



                                <h6 class="text-muted mb-0">{{'@ '. ucfirst($role[0]->levelType); }}</h6>



                            </div>



                        </div>



                    </div>



                </div>



                <div class="card" style="display: none;">



                    <div class="card-header">



                        <h4>Recent Messages</h4>



                    </div>



                    <div class="card-content pb-4">



                        <div class="recent-message d-flex px-4 py-3">



                            <div class="avatar avatar-lg">



                                <img src="{{asset('admin/assets/images/faces/4.jpg')}}">



                            </div>



                            <div class="name ms-4">



                                <h5 class="mb-1">Hank Schrader</h5>



                                <h6 class="text-muted mb-0">@johnducky</h6>



                            </div>



                        </div>



                        <div class="recent-message d-flex px-4 py-3">



                            <div class="avatar avatar-lg">



                                <img src="{{asset('admin/assets/images/faces/5.jpg')}}">



                            </div>



                            <div class="name ms-4">



                                <h5 class="mb-1">Dean Winchester</h5>



                                <h6 class="text-muted mb-0">@imdean</h6>



                            </div>



                        </div>



                        <div class="recent-message d-flex px-4 py-3">



                            <div class="avatar avatar-lg">



                                <img src="{{asset('admin/assets/images/faces/1.jpg')}}">



                            </div>



                            <div class="name ms-4">



                                <h5 class="mb-1">John Dodol</h5>



                                <h6 class="text-muted mb-0">@dodoljohn</h6>



                            </div>



                        </div>



                        <div class="px-4">



                            <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Start



                                Conversation</button>



                        </div>



                    </div>



                </div>



                <div class="card" style="display: block;">



                    <div class="card-header">



                        <h4>Room Status</h4>



                    </div>



                    <div class="card-body">



                        <!-- <div id="chart-visitors-profile"></div> -->

                        <div id="chart-room-status"></div>



                    </div>



                </div>



            </div>



        </section>



    </div>



@endsection







