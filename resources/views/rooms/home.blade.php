@extends('admin.layout.app')



@section('page_title', 'Manage Rooms')



@section('content')



@php



use Illuminate\Support\Facades\DB;



use Illuminate\Support\Carbon;

use App\Http\Controllers\MainController;



$owners_br = MainController::ownersBr();



$users = DB::table('users')->where('id', session()->get('user_id'))->get();



@endphp



<h3>Manage Rooms</h3>



<section class="section">



    <!--Disabled Backdrop Modal -->



    <div class="modal fade text-left" id="backdrop1" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">



        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">



            <div class="modal-content">



                <div class="modal-header">



                    <h4 class="modal-title" id="myModalLabel4">Add New Room



                    </h4>



                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                        <i class="bi bi-x-circle"></i>



                    </button>



                </div>



                <div class="modal-body">



                    <form method="POST" class="form form-vertical" action="{{ url('manage_rooms') }}">



                        @csrf



                        <div class="form-body">



                            <div class="row">



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="brCode">Branch Code</label>



                                        @php



                                            if(session()->get('role_id') == 1) {



                                            $branch = DB::table('branchs')->get();



                                            }else{



                                            $branch = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();



                                            }



                                        @endphp



                                        <select class="form-select" id="brCode" name="brCode" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         @foreach($branch as $br)



                                         <option value="{{ $br->id }}">{{ $br->brName }}</option>



                                         @endforeach



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="floor">Floor</label>



                                        <select id="floor" name="floor" class="form-select">



                                        <option value="" hidden selected disabled>Select</option> 



                                           @php 



                                               if(session()->get('role_id') == 1) { 



                                               $floors = DB::table('floors')->get();



                                               }else{



                                                $floors = DB::table('floors')->where('brCode', session()->get('br_code'))->get();    



                                               }







                                           @endphp



                                           @foreach($floors as $f) 



<option value="{{ $f->series }}" data-bcode="{{ $f->brCode }}" data-floor_id="{{ $f->id }}">{{ $f->floor }}</option> 



                                           @endforeach



                                        </select>

                                        <input type="hidden" name="floorRef" id="floorRef" class="form-control">

                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="roomNo">Room No</label>



                                        <input type="text" name="roomNo" id="roomNo" class="form-control" required>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="roomStatus">Room Status</label>



                                        @php



                                            $roomstatus = DB::table('roomsstatus')->whereNotIn('id', [6,8])->get();



                                        @endphp



                                        <select class="form-select" id="roomStatus" name="roomStatus" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         @foreach($roomstatus as $roomst)



                                         <option value="{{ $roomst->id }}">{{ $roomst->roomStatus }}</option>



                                         @endforeach



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="roomType">Room Type</label>



                                        @php



                                        	if(session()->get('role_id') == 1) { 

                                            $roomtypes = DB::table('roomtype')->get();

                                        	}else{

                                        	$roomtypes = DB::table('roomtype')->where('branch_id', session()->get('br_code'))->get();	

                                        	}



                                        @endphp



                                        <select class="form-select" id="roomType" name="roomType" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         @foreach($roomtypes as $roomtype)



                                         <option value="{{ $roomtype->id }}" data-bcode1="{{ $roomtype->branch_id }}">{{ $roomtype->rType }}</option>



                                         @endforeach



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="nobeds">No.Beds</label>



                                        <select class="form-select" id="nobeds" name="nobeds" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         <option value="1">Single</option>



                                         <option value="2">Double</option>



                                         <option value="3">Triple</option>



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-12 col-12">



                                    <div class="form-group">



                                        <label for="remarks">Remarks (optional)</label>



                                        <input type="text" name="remarks" id="remarks" class="form-control">



                                    </div>



                                </div>







                                <div class="col-12 d-flex justify-content-end py-3">



                                    <button type="submit" class="btn btn-primary me-1 mb-1">Add Room</button>



                                </div>



                            </div>



                        </div>



                    </form>



                </div>



                <div class="modal-footer">



                    



                </div>



            </div>



        </div>



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



            <button class="btn btn-info" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#import">Bulk Import</button>



            <br/><span style="font-size:14px;display:inline-block;">



                <a href="{{ asset('uploads/room_sample.csv') }}">Sample file</a>



            </span>



            @endif







            @if($access->canAdd == 1)



            <a href="javascript:void(0);" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop1">Add Room</a>



            @endif







            @if(Session::get('success'))



                        <div class="alert alert-success mt-3">



                        <i class="bi bi-check-circle"></i> 



                        {{ Session::get('success') }}



                        </div>



            @endif



            @if(Session::get('fail'))



                        <div class="alert alert-danger mt-3">



                        {{ Session::get('fail') }}



                        </div>



            @endif



                    



        </div>



        



        <div class="card-body">



            <table class="table table-striped" id="table1">



                <thead>



                    <tr>



                        <th>S.No</th>



                        <th>Br.Code</th>



                        <th>Floor</th>



                        <th>Room No</th>



                        <th>Room Status</th>



                        <th>Room Type</th>



                        <th>No.Beds</th>



                        <th>Creatd by</th>



                        <th>Remarks</th>



                        <th width="200">Action</th>



                    </tr>



                </thead>



                <tbody id="myBody">



                   @php



                    $counter = 1;



                   @endphp 



                   @foreach($rooms as $list)



                   @php



                   $brname = DB::table('branchs')->where('id', $list->brCode)->first();



                   $rootype = DB::table('roomtype')->where('id', $list->roomType)->first();



                   @endphp



                    <tr>



                        <td class="text-center">{{ $counter++ }}</td>



                        <td>{{$brname->brName}}</td>



                        <td>{{$list->floor}}</td>



                        <td>{{$list->roomNo}}</td>



                        @php



                        $room_status = DB::table('roomsstatus')->where('id', $list->roomStatus)->first();



                        @endphp



                        <td class={{$room_status->roomStatus}}>{{$room_status->roomStatus}}</td>



                        <td class="text-center">{{$rootype->rType ?? ''}}</td>



                        <td class="text-center">{{$list->no_beds}}</td>



                        @php



                            $createBy = DB::table('users')->where('id', $list->created_by)->get();



                        @endphp



                        <td>{{ucfirst($createBy[0]->name)}}</td>



                        <td>{{$list->remarks}}</td>



                        <td>



                            



                            {{-- <a href="" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop">More</a> --}}







                            <a href="javascript:void(0)" data-id="{{ $list->id }}" class="btn btn-info btn-sm more">More</a>







                            @if($access->canEdit == 1 && $list->roomStatus != 8)



                            <a href="{{url('manage_rooms/'.$list->id.'/edit')}}" data-id="{{ $list->id }}" class="btn btn-secondary btn-sm edit" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#update">Edit</a>



                            @endif



                            



                            @if($access->canDelete == 1)



                            <a href="javascript:void(0);" class="btn btn-danger btn-sm roomDelete">



                            {{-- <a href="javascript:void(0);" class="btn btn-danger btn-sm roomDelete" onclick="$(this).find('form').submit();"> --}}



                                <form method="POST" action="{{url('manage_rooms/'.$list->id)}}">



                                    @csrf



                                    {{-- @method('DELETE')    --}}



                                    <input type="hidden" name="_method" value="DELETE">



                                </form>      



                            Delete



                            </a>



                            @endif



                            <!--Disabled Backdrop Modal -->



                                <div class="modal fade text-left" id="roomModal-{{ $list->id }}" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true" data-bs-backdrop="false">



                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">



                                        <div class="modal-content">



                                            <div class="modal-header">



                                                <h4 class="modal-title" id="myModalLabel4">More Information



                                                </h4>



                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                                                    <i class="bi bi-x-circle"></i>



                                                </button>



                                            </div>



                                            <div class="modal-body rooms">



                                                <div class="row">



                                                    <div class="col-md-12 col-12">



                                                        <p style="font-weight: 600;">Branch Name: {{ $brname->brName }}</p>



                                                    </div>



                                                    <div class="col-md-3 col-12">



                                                        <label for="">Floor</label>



                                                        <p>{{ $list->floor }}</p>



                                                    </div>



                                                    <div class="col-md-3 col-12">



                                                        <label for="">Room No</label>



                                                        <p>{{ $list->roomNo }}</p>



                                                    </div>



                                                    <div class="col-md-3 col-12">



                                                        <label for="">Room Type</label>



                                                        <p>{{ $rootype->rType ?? '' }}</p>



                                                    </div>



                                                    <div class="col-md-3 col-12">



                                                        <label for="">Room Status</label>



                                                        <p>{{ $room_status->roomStatus }}</p>



                                                    </div>



                                                    <div class="col-md-3 col-12">



                                                        <label for="">Number of Beds</label>



                                                        <p>{{ $list->no_beds }}</p>



                                                    </div>



                                                    <div class="col-md-9 col-12">



                                                        <label for="">Remarks</label>



                                                        <p>{{ $list->remarks }}</p>



                                                    </div>



                                                    <div class="col-md-6 col-12">



                                                        <label for="">Created By</label>



                                                        <p>{{ ucfirst($createBy[0]->name) }}</p>



                                                    </div>



                                                    <div class="col-md-6 col-12">



                                                        <label for="">Updated By</label>



                                                        <p>{{ ucfirst($createBy[0]->name) }}</p>



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







                                <!--Disabled Backdrop Modal update-->



    <div class="modal fade text-left" id="update" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">



        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">



            <div class="modal-content">



                <div class="modal-header">



                    <h4 class="modal-title" id="myModalLabel4">Update Room



                    </h4>



                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                        <i class="bi bi-x-circle"></i>



                    </button>



                </div>



                <div class="modal-body">



                    <form method="POST" class="form form-vertical" action="{{ url('manage_rooms/id') }}">



                        @csrf



                        @method('PUT')



                        <div class="form-body">



                            <div class="row">



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <input type="hidden" name="id" id="push_id">



                                        <label for="updatebrCode">Branch Code</label>



                                        @php



                                            if(session()->get('role_id') == 1) {



                                            $branch = DB::table('branchs')->get();



                                            }else{



                                            $branch = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();



                                            }



                                        @endphp



                                        <select class="form-select" id="updatebrCode" name="updatebrCode" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         @foreach($branch as $br)



                                         <option value="{{ $br->id }}">{{ $br->brName }}</option>



                                         @endforeach



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="updatefloor">Floor</label>



                                        <select id="updatefloor" name="updatefloor" class="form-select">



                                        <option value="" hidden selected disabled>Select</option> 



                                           @php 



                                               $floors = DB::table('floors')->get();



                                           @endphp



                                           @foreach($floors as $f) 



<option value="{{ $f->series }}" data-branch="{{ $f->brCode }}" data-floor_id={{ $f->id }}>{{ $f->floor }}</option> 



                                           @endforeach



                                        </select>

                                    <input type="hidden" name="floorRef_update" id="floorRef_update" class="form-control">

                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="updateroomNo">Room No</label>



                                        <input type="text" name="updateroomNo" id="updateroomNo" class="form-control" required>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="updateroomStatus">Room Status</label>



                                        @php



                                            $roomstatus = DB::table('roomsstatus')->whereNotIn('id', [6,8])->get();



                                        @endphp



                                        <select class="form-select" id="updateroomStatus" name="updateroomStatus" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         @foreach($roomstatus as $roomst)



                                         <option value="{{ $roomst->id }}">{{ $roomst->roomStatus }}</option>



                                         @endforeach



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="updateroomType">Room Type</label>



                                        @php



                                            $roomtypes = DB::table('roomtype')->get();



                                        @endphp



                                        <select class="form-select" id="updateroomType" name="updateroomType" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         @foreach($roomtypes as $roomtype)



                                         <option value="{{ $roomtype->id }}" data-branch1="{{ $roomtype->branch_id }}">{{ $roomtype->rType }}</option>



                                         @endforeach



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-6 col-12">



                                    <div class="form-group">



                                        <label for="updatenobeds">No.Beds</label>



                                        <select class="form-select" id="updatenobeds" name="updatenobeds" required>



                                         <option value="" disabled hidden selected>Select</option>



                                         <option value="1">Single</option>



                                         <option value="2">Double</option>



                                         <option value="3">Triple</option>



                                        </select>



                                    </div>



                                </div>



                                <div class="col-md-12 col-12">



                                    <div class="form-group">



                                        <label for="updateremarks">Remarks (optional)</label>



                                        <input type="text" name="updateremarks" id="updateremarks" class="form-control">



                                    </div>



                                </div>







                                <div class="col-12 d-flex justify-content-end py-3">



                                    <button type="submit" class="btn btn-primary me-1 mb-1" id="btn_update_room">Update Room</button>



                                </div>



                            </div>



                        </div>



                    </form>



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







    <!--Disabled Backdrop Modal Import Bulk Rooms -->



    <div class="modal fade text-left" id="import" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">



        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">



            <div class="modal-content">



                <div class="modal-header">



                    <h4 class="modal-title" id="myModalLabel4">Import Bulk Data



                    </h4>



                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                        <i class="bi bi-x-circle"></i>



                    </button>



                </div>



                <div class="modal-body">



                    <form method="POST" class="form form-vertical" action="{{ route('import') }}" enctype="multipart/form-data">



                        @csrf



                        <div class="form-body">



                            <div class="row">



                                <div class="col-md-12 col-12">



                                    <label for="file">Import CSV</label>



                                    <div class="form-group">



                                        <input type="file" name="file" id="file" accept=".csv" required>



                                    </div>



                                </div>







                                <div class="col-12 d-flex justify-content-end py-3">



                                    <button type="submit" class="btn btn-primary me-1 mb-1">Upload</button>



                                </div>



                            </div>



                        </div>



                    </form>



                </div>



                <div class="modal-footer">



                    



                </div>



            </div>



        </div>



    </div>



    







</section>



<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>



        $(document).ready(function(){

            var id;

            $("#myBody").on("click", ".edit", function(e){

                e.preventDefault();

                id = $(this).data('id');



                status_chk();    



            }); 



            status_chk();



            function status_chk() {

                setInterval(() => {

                    $.ajax({

                            url: '{{ url("status_check") }}'+'/'+id,

                            type:'GET',

                            success:function(response) {

                                //console.log(response);

                                if(response.status === 8) {

                                    $("#btn_update_room").prop("disabled",true);

                                }else{

                                    $("#btn_update_room").prop("disabled",false);

                                }



                            }

                    });

                    

                }, 2000);



            }



             $(document).on("change", "#updatefloor", function(){



                $("#floorRef_update").val($(this).find(":selected").data('floor_id'));





            });



        });



</script>



@endsection



