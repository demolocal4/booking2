@extends('admin.layout.app')

@section('page_title', 'Manage Room Status')

@section('content')

@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

@endphp

<h3>Manage Rooms Status</h3>

<section class="section">

    <!--Disabled Backdrop Modal -->

    <div class="modal fade text-left" id="backdrop" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel4">Add Room Status

                    </h4>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <i class="bi bi-x-circle"></i>

                    </button>

                </div>

                <div class="modal-body">

                    <form method="POST" class="form form-vertical" action="{{ url('room_status') }}">

                        @csrf

                        <div class="form-body">

                            <div class="row">

                                <div class="col-md-12 col-12">

                                    <div class="form-group">

                                        <label for="roomStatus">Room Status</label>

                                        <input type="text" id="roomStatus" class="form-control" name="roomStatus" required>

                                    </div>

                                </div>



                                <div class="col-12 d-flex justify-content-end py-3">

                                    <button type="submit" class="btn btn-primary me-1 mb-1">Add Status</button>

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

            <a href="javascript:void(0);" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop">Add Room Status</a>

            @endif



            @if(Session::get('success'))

                        <div class="alert alert-success">

                        <i class="bi bi-check-circle"></i> 

                        {{ Session::get('success') }}

                        </div>

            @endif

            @if(Session::get('fail'))

                        <div class="alert alert-danger">

                        {{ Session::get('fail') }}

                        </div>

            @endif



            @error('roomStatus')

                <div class="alert alert-warning">

                {{ $message }}

                </div>

            @enderror

                    

        </div>

        <div class="card-body">

            <table class="table table-striped" id="table1">

                <thead>

                    <tr>

                        
                        <th class="text-center">Room Status ID#</th>

                        <th>Room Status</th>

                        <th>Created by</th>

                        <th>Updated by</th>

                        <th>Created at</th>

                        <th>Updated at</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                   @php

                   $counter = 1;    

                   @endphp 

                   @foreach($roomStatus as $list)

                    <tr>

                        @php

                        $username = DB::table('users')->where('id', $list->created_by)->get();

                        @endphp

                        <!-- <td class="text-center">{{ $counter++ }}</td> -->
                        <td class="text-center">{{ $list->id }}</td>

                        <td>{{$list->roomStatus}}</td>

                        <td>{{ucfirst($username[0]->name)}}</td>

                        <td>{{ucfirst($username[0]->name)}}</td>

                        <td>{{$list->created_date}}</td>

                        <td>{{$list->updated_date}}</td>

                        <td>

                            @if($access->canDelete == 1)

                            <a href="javascript:void(0);" class="btn btn-danger btn-sm statusDelete">

                            {{-- <a href="javascript:void(0);" class="btn btn-danger btn-sm statusDelete" onclick="$(this).find('form').submit();"> --}}

                                <form method="POST" action="{{url('room_status/'.$list->id)}}">

                                    @csrf

                                    {{-- @method('DELETE')    --}}

                                    <input type="hidden" name="_method" value="DELETE">

                                </form>      

                            Delete

                            </a>

                            @endif

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