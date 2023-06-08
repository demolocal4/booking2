@extends('admin.layout.app')



@section('page_title', 'Manage Floors')



@section('content')



@php



use Illuminate\Support\Facades\DB;



use Illuminate\Support\Carbon;



$users = DB::table('users')->where('id', session()->get('user_id'))->get();



@endphp



<h3>Manage Floors</h3>



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



            <div class="row">



                <div class="col-md-5 col-12 py-3" style="background-color: rgb(247, 247, 247); border-radius:10px;max-height:300px;">







                    <form method="POST" class="form form-vertical" action="{{ url('manage_floors') }}">



                    @csrf



                    



                    



                        <div class="form-body">



                            <div class="row">



                                <div class="col-md-12 col-12">



                                <div class="form-group">



                                    <label for="floor">Floor (e.g. First Floor)</label>



                                    <input type="text" name="floor" class="form-control elements" id="floor" required>



                                </div>



                                </div>



                                <div class="col-md-12 col-12">



                                    <div class="form-group">



                                        <label for="series">Series (e.g. 101)</label>



                                        <input type="number" name="series" class="form-control elements" required>



                                    </div>



                                </div>



                                <?php



                                if($users[0]->role == 1) {    



                                $branch = DB::table('branchs')->get();    



                                ?>



                                <div class="col-md-12 col-12">



                                    <div class="form-group">



                                        <label for="branch">Branch</label>



                                        <select name="branch" id="branch" class="form-select elements" required>



                                            <option value="" hidden disabled selected>Select</option>



                                            @foreach($branch as $br)



                                            <option value="{{ $br->brCode }}">{{ $br->brName }}</option>    



                                            @endforeach



                                            



                                        </select>



                                    </div>



                                </div>



                                <?php }else{ ?>



                                    {{-- <div class="col-md-12 col-12">



                                        <div class="form-group">



                                            <label for="branch">Branch</label>



                                            <select name="branch" id="branch" class="form-select elements" disabled>



                                                <option value="" hidden disabled selected>Select</option>



                                                @foreach($branch as $br)



                                                <option value="{{ $br->brCode }}">{{ $br->brName }}</option>    



                                                @endforeach



                                                



                                            </select>



                                        </div>



                                    </div> --}}



                                    <input type="hidden" name="branch" class="elements" value="{{ session()->get('br_code') }}">



                                <?php } ?>    



                                @php 



                                $access = DB::table('role_access')->where('levelType', session()->get('role_id'))->first();



                                @endphp







                                @if($access->canAdd == 1)



                                <div class="col-12 d-flex justify-content-end py-3">



                    



                                    <button type="submit" id="new" class="btn btn-primary me-1 mb-1">Add Floor</button>



                                    <button type="button" id="btn-update" data-id="" class="btn btn-warning me-1 mb-1" style="display: none;">Update</button>



                    



                                </div>



                                @endif



                                



                            </div>



                        </div>



                    </form>







                </div> 



                <div class="col-md-7 col-12">



                <table class="table table-striped" id="table1">



                <thead>



                    <tr>



                        <th>S.No</th>



                        <th>Floor</th>



                        <th>Ref</th>



                        <th>Series</th>



                        <th>Branch</th>



                        <th width="150">Action</th>



                    </tr>



                </thead>



                <tbody id="floorBody">



                   @php



                    $counter = 1;



                   @endphp 



                   @foreach($floors as $list)



                   @php



                   $brname = DB::table('branchs')->where('brCode', $list->brCode)->first();



                   @endphp



                    <tr>



                        <td class="text-center">{{ $counter++ }}</td>



                        <td>{{$list->floor}}</td>



                        <td>{{$list->id}}</td>



                        <td>{{$list->series}}</td>



                        <td>



                            {{$brname->brName}}



                        </td>



                        @php



                            $createBy = DB::table('users')->where('id', $list->created_by)->get();



                        @endphp



                        <td>



                            {{-- <a href="" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop">More</a> --}}







                            <a href="javascript:void(0)" data-id="{{ $list->id }}" class="btn btn-info btn-sm more">More</a>







                            @php



                            $moreinfo = DB::table('users')->where('id', $list->created_by)->get();



                            @endphp



                            <!--Disabled Backdrop Modal -->



                                <div class="modal fade text-left" id="floorModal-{{ $list->id }}" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true" data-bs-backdrop="false">



                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">



                                        <div class="modal-content">



                                            <div class="modal-header">



                                                <h4 class="modal-title" id="myModalLabel4">More Information



                                                </h4>



                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">



                                                    <i class="bi bi-x-circle"></i>



                                                </button>



                                            </div>



                                            <div class="modal-body floorModal">



                                                <div class="row">



                                                    <div class="col-md-12 col-12">



                                                        <p style="font-weight: 600;">Branch Name: {{ $brname->brName }}</p>



                                                    </div>







                                                    <div class="col-md-6 col-12">



                                                        <label for="">Floor</label>



                                                        <p>{{ $list->floor }}</p>



                                                    </div>



                                                    <div class="col-md-6 col-12">



                                                        <label for="">Series</label>



                                                        <p>{{ $list->series }}</p>



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



                            @if($access->canEdit == 1)    



                            <a href="{{url('manage_floors/'.$list->id.'/edit')}}" class="btn btn-secondary btn-sm btn-edit">Edit</a>



                            @endif







                            @if($access->canDelete == 1)



                            <a href="javascript:void(0);" class="btn btn-danger btn-sm floorDelete">



                            {{-- <a href="javascript:void(0);" class="btn btn-danger btn-sm floorDelete" onclick="$(this).find('form').submit();"> --}}



                                <form method="POST" action="{{url('manage_floors/'.$list->id)}}">



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







        </div>



    </div>



</section>







@endsection



