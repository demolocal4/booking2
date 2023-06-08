@extends('admin.layout.app')

@section('page_title', 'Manage Branches')

@section('content')

@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;
use App\Http\Controllers\MainController;

$owners_br = MainController::ownersBr();

@endphp

<h3>Manage Branches</h3>

<section class="section">

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
                 }else if(session()->get('role_id') == 5) {
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

            @php 

            $access = DB::table('role_access')->where('levelType', session()->get('role_id'))->first();

            @endphp

            @if($access->canAdd == 1)

            <a href="{{url('manage_branches/create')}}" class="btn btn-primary float-end">Add Branch</a>

            @endif



            @if(Session::get('success'))

            <div class="alert alert-success">

                {{ Session::get('success') }}

            </div>

            @endif

                    

        </div>

        <div class="card-body">

            <table class="table table-striped" id="table1">

                <thead>

                    <tr>

                        <th>S.No</th>

                        <th>Br.Code</th>

                        <th>Branch Name</th>

                        <th>Br.Manager</th>

                        <th>Br.Floors</th>

                        <th>Br.Rooms</th>

                        <th>Occupied Rooms</th>

                        <th>Br.Contact</th>

                        <th>Occupancy Ratio</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody id="brachBody">

                   @php

                    $counter = 1;

                   @endphp 

                   @foreach($branches as $list)

                    @php
                    $roomscount = DB::table('rooms')->where('brCode', $list->brCode)->count();    
                    $occupancy = DB::table('rooms')->where('brCode', $list->brCode)
                    							   ->where('roomStatus', 8)	
                    							   ->count();
                    // $occupancy = DB::table('booking')->where('brCode', $list->brCode)->count();
                    @endphp
                    
                    <tr>

                        <td class="text-center">{{ $counter++ }}</td>

                        <td>{{$list->brCode}}</td>

                        <td>{{$list->brName}}</td>
                        
                        @php

                        $users = DB::table('users')->where('id', $list->brManager)->get();

	                    @endphp

                        <td>{{ucfirst($users[0]->name)}}</td>

                        <td class="text-center">{{$list->brFloors}}</td>

                        <td class="text-center">{{$list->brRooms}}</td>

                        <td class="text-center">{{$occupancy}}</td>

                        <td>{{$list->brContact}}</td>

                        <td style="text-align: right;">
                            @if($roomscount > 0)
                            <span style="color: green;">{{ round($occupancy/$roomscount*100,2) }} %</span>
                            @else
                            <span style="color: darkorange;">0 %</span>
                            @endif
                        </td>

                        <td>

                            {{-- <a href="" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop">More</a> --}}

                            

                            <a href="javascript:void(0)" class="btn btn-info btn-sm more" data-id="{{ $list->id }}">More</a>



                            @if($access->canEdit == 1)

                            <a href="{{url('manage_branches/'.$list->id.'/edit')}}" class="btn btn-secondary btn-sm">Edit</a>

                            @endif

                            @if($access->canDelete == 1)

                            <a href="javascript:void(0);" class="btn btn-danger btn-sm brdelete">

                                {{-- <a href="javascript:void(0);" class="btn btn-danger btn-sm brdelete" onclick="$(this).find('form').submit();"> --}}



                                <form method="POST" action="{{url('manage_branches/'.$list->id)}}">

                                    @csrf

                                    {{-- @method('DELETE')    --}}

                                    <input type="hidden" name="_method" value="DELETE">

                                </form>      

                            Delete

                            </a>

                            @endif

                            @php

                            $moreinfo = DB::table('users')->where('id', $list->created_by)->get();

                            @endphp

                            <!--Disabled Backdrop Modal -->

                                <div class="modal fade text-left" id="backdrop-{{ $list->id }}" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true" data-bs-backdrop="false">

                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <h4 class="modal-title" id="myModalLabel4">More Information

                                                </h4>

                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                                                    <i class="bi bi-x-circle"></i>

                                                </button>

                                            </div>

                                            <div class="modal-body branch">

                                            	@php
                                                $occupied = DB::table('rooms')->where('brCode', $list->brCode)
                                                                                ->where('roomStatus', 8)
                                                                                ->count();
                                                $unclean = DB::table('rooms')->where('brCode', $list->brCode)
                                                                                ->where('roomStatus', 7)
                                                                                ->count();
                                                $blocked = DB::table('rooms')->where('brCode', $list->brCode)
                                                                                ->where('roomStatus', 3)
                                                                                ->count();
                                                $available = DB::table('rooms')->where('brCode', $list->brCode)
                                                                                ->where('roomStatus', 5)
                                                                                ->count();

                                                @endphp
                                                
                                                <div class="row" style="text-align:center;">

                                                    <div class="col-6 col-lg-3 col-md-6">
                                                        <div class="card">
                                                            <div class="card-body px-3 py-4-5">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="stats-icon Available">
                                                                            <i class="iconly-boldCategory"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <h6 class="text-muted font-semibold">Available</h6>
                                                                        <h6 class="font-extrabold mb-0">{{ $available }}</h6>
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
                                                                        <div class="stats-icon Occupied">
                                                                            <i class="iconly-boldGraph"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <h6 class="text-muted font-semibold">Occupied</h6>
                                                                        <h6 class="font-extrabold mb-0">{{ $occupied }}</h6>
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
                                                                        <div class="stats-icon UnClean">
                                                                            <i class="iconly-boldInfo-Circle"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <h6 class="text-muted font-semibold">Unclean</h6>
                                                                        <h6 class="font-extrabold mb-0">{{ $unclean }}</h6>
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
                                                                        <div class="stats-icon Blocked">
                                                                            <i class="iconly-boldLock"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <h6 class="text-muted font-semibold">Blocked</h6>
                                                                        <h6 class="font-extrabold mb-0">{{ $blocked }}</h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-12 col-12">

                                                        <p><label for="">Branch Name :</label> {{ $list->brName }}</p>

                                                    </div>

                                                    <div class="col-md-12 col-12">

                                                        <p><label for="">Location :</label> {{ $list->brLocation }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Manager</label>

                                                        @php

                                                        $user = DB::table('users')->where('id', $list->brManager)->first();

                                                        @endphp

                                                        <p>{{ ucfirst($user->fullname) }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Contact No</label>

                                                        <p>{{ $list->brContact }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Branch Floors</label>

                                                        <p>{{ $list->brFloors }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Branch Rooms</label>

                                                        <p>{{ $list->brRooms }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Owner Name</label>

                                                        <p>{{ $list->ownerName }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Landlord Name</label>

                                                        <p>{{ $list->landlordName }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Landlord Email</label>

                                                        <p>{{ $list->landlordEmail }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Landlord Phone</label>

                                                        <p>{{ $list->landlordPhone }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Created By</label>

                                                        <p>{{ ucfirst($moreinfo[0]->name) }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Updated By</label>

                                                        <p>{{ ucfirst($moreinfo[0]->name) }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Created at</label>

                                                        <p>{{ $list->created_date }}</p>

                                                    </div>

                                                    <div class="col-md-6 col-12">

                                                        <label for="">Updated at</label>

                                                        <p>{{ $list->updated_date }}</p>

                                                    </div>



                                                </div>



                                            </div>

                                            <div class="modal-footer">

                                                

                                            </div>

                                        </div>

                                    </div>

                                </div>

                        </td>

                    </tr>

                    @endforeach



                    {{-- <tr>

                        <td>Nathaniel</td>

                        <td>mi.Duis@diam.edu</td>

                        <td>(012165) 76278</td>

                        <td>Grumo Appula</td>

                        <td>

                            <span class="badge bg-danger">Inactive</span>

                        </td>

                    </tr> --}}

                    

                </tbody>

            </table>

        </div>

    </div>



    



</section>



@endsection