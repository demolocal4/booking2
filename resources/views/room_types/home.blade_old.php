@extends('admin.layout.app')

@section('page_title', 'Manage Room Types')

@section('content')

@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
$users = DB::table('users')->where('id', session()->get('user_id'))->get();
@endphp
<style>
    
    .slick-next {
       right: -10px;
       /* background-color: rgb(37, 59, 158) !important; */
       /* z-index:10; */
   }
   .slick-prev {
       left: 0px;
       /* background-color: rgb(37, 59, 158) !important; */
       /* z-index: 9999999; */
   }
   
   .slick-prev, .slick-next {
       background: rgb(14, 89, 175);
       z-index: 999999999;
   }
   .slick-prev:hover, .slick-prev:focus, .slick-next:hover, .slick-next:focus {
       background: rgb(14, 89, 175);
   }
   
   .slick-slide{
       margin-left:  15px;
       margin-right:  15px;
       padding-block: 15px;
       
     }
   
     .slick-list {
       margin-left: -15px;
       margin-right: -15px;
       pointer-events: auto;
     }
     .box-inner {
       background-color: rgb(255, 255, 255);
       /* padding: 25px; */
       padding-block: 15px;
       width: 240px;
       height: 240px;
       text-align: center;
       box-shadow: 1px 1px 5px rgba(0,0,0, 0.5);
     }
     .box-inner p {
       line-height: 0.5;
     }
     .rate-update {
        display: none;
     }
     .heightAuto{
        height:auto;
    }
   </style>
<h3>Manage Room Types</h3>

<section class="section">
    <!--Disabled Backdrop Modal -->
    <div class="modal fade text-left" id="backdrop" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Add Room Rates
                    </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form form-vertical" action="{{ url('room_types') }}">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <?php
				                if($users[0]->role == 1) {    
                                $branch = DB::table('branchs')->whereNotIn('id',[1000])->get();    
                                ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="brCode">Branch</label>
                                        <select name="brCode" id="brCode_1" class="form-select elements" required>
                                            <option value="" disabled selected>Select</option>
                                            @foreach($branch as $br)
                                            <option value="{{ $br->brCode }}">{{ $br->brName }}</option>    
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <input type="hidden" name="brCode" value="{{ session()->get('br_code') }}">    
                                <?php } ?>    

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="roomType">Room Type</label>
                                        <select name="roomType" id="roomType1" class="form-select elements" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach (\App\Models\RoomType::all() as $rType)
                                            <option value="{{ $rType->rType }}" 
                                                data-id="{{ $rType->id }}"
                                                data-brc="{{ $rType->branch_id }}"
                                                >{{ $rType->rType }}</option>   
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="id" id="typeId">
                                        {{-- <input type="text" id="roomType" class="form-control" name="roomType" required> --}}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                    <label for="daterange">Select Date</label>
                                    <input type="text" name="daterange" id="daterange" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="regular">Regular</label>
                                        <input type="number" id="regular" class="form-control" min="0" step="0.01" name="regular" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="weekly">Weekly</label>
                                        <input type="number" id="weekly" class="form-control" min="0" step="0.01" name="weekly" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="single">Monthly</label>
                                        <input type="number" id="monthly" class="form-control" min="0" step="0.01" name="monthly" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{-- <label for="vat">Vat %</label> --}}
                                        <label for="vat">Booking Fee %</label>
                                        <input type="number" id="vat" class="form-control" min="0" name="vat" required>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end py-3">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Save Rate</button>
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
       <x-booking-counter />
        <div class="card-header">
            @php 
            $access = DB::table('role_access')->where('levelType', session()->get('role_id'))->first();
            @endphp
            <div class="row mb-3">
            <div class="col-12">
            <button class="btn btn-outline-primary" id="list_view">List View</button>
            <button class="btn btn-outline-dark" id="calendar_view">Calendar View</button>
            @if($access->canAdd == 1)
            <a href="javascript:void(0);" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop">Add Room Rates</a>
            @endif
            </div>
            </div>
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
            @error('roomType')
                <div class="alert alert-warning">
                {{ $message }}
                </div>
            @enderror
        </div>
        <div class="card-body">

            {{-- list view ------------------------------------------------------------------}}
            <div class="list-view" style="@if(isset($_GET['page'])) display:block; @else display:none; @endif">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <!-- <th>S.No</th> -->
                        <th>Branch</th>
                        <th class="text-center">Room Type ID#</th>
                        <th>Room Type</th>
                        <th>Date</th>
                        <th>Regular</th>
                        <th>Weekly</th>
                        <th>Monthly</th>
                        <th>Fee %</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>
                <tbody id="rtBody">
                   @php
                       $counter = 1;
                   @endphp 
                   @foreach($roomTypes->sortByDesc('id') as $list)
                    <tr>
                        @php
                        $username = DB::table('users')->where('id', $list->created_by)->get();
                        $brname = DB::table('branchs')->where('brCode', $list->brCode)->first();
                        @endphp
                        <!-- <td class="text-center">{{ $counter++ }}</td> -->
                        <td>
                            {{$brname->brName}}
                        </td>
                        <td class="text-center">{{ $list->roomtype_id }}</td>
                        <td>{{$list->roomType}}</td>
                        <td>{{ date('d-m-Y', strtotime($list->date)) }}</td>
                        <td>{{$list->regular}}</td>
                        <td>{{$list->weekly}}</td>
                        <td>{{$list->monthly}}</td>
                        <td>{{$list->vat}}</td>
                        <td>
                            {{-- <a href="" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#moreinfo">More</a> --}}
                            <a href="javascript:void(0)" data-id="{{ $list->id }}" class="btn btn-info btn-sm more" >More</a>
                             <!--Disabled Backdrop Modal for more info -->
                             <div class="modal fade text-left" id="moreinfo-{{ $list->id }}" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true" data-bs-backdrop="false">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel4">More Information
                                            </h4>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body room-type">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <p style="font-weight: 600;">Branch Name: {{ $brname->brName }}</p>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <p style="font-weight: 600;">Room Type: {{ $list->roomType }}</p>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <p style="font-weight: 600;">Booking Fee(%): {{ $list->vat }}</p>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <label for="">Tariff(Regular)</label>
                                                    <p>{{ $list->regular }}</p>
                                                </div>

                                                <div class="col-md-4 col-12">
                                                    <label for="">Tariff(Weekly)</label>
                                                    <p>{{ $list->weekly }}</p>
                                                </div>

                                                <div class="col-md-4 col-12">
                                                    <label for="">Tariff(Monthly)</label>
                                                    <p>{{ $list->monthly }}</p>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <label for="">Created By</label>
                                                    <p>{{ ucfirst($username[0]->name) }}</p>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <label for="">Updated By</label>
                                                    <p>{{ ucfirst($username[0]->name) }}</p>
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

                            <!--Disabled Backdrop Modal for more info -->

                            @if($access->canEdit == 1)
                            <a href="{{url('room_types/'.$list->id.'/edit')}}" class="btn btn-secondary btn-sm btn-edit" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#update">Edit</a>
                            @endif
                            @if($access->canDelete == 1)
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm typeDelete">
                            {{-- <a href="javascript:void(0);" class="btn btn-danger btn-sm typeDelete" onclick="$(this).find('form').submit();"> --}}
                                <form method="POST" action="{{url('room_types/'.$list->id)}}">
                                    @csrf
                                    {{-- @method('DELETE')    --}}
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>      
                            Delete
                            </a>
                            @endif
                        </td>
                    </tr>

<!--Disabled Backdrop Modal update-->
    <div class="modal fade text-left" id="update" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Update Room Type
                    </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form form-vertical" action="{{ url('room_types/id') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-body">
                            <div class="row">
                                <?php
				                if($users[0]->role == 1) {    
                                $branch = DB::table('branchs')->get();    
                                ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="brCode">Branch</label>
                                        <select name="brCode" id="brCode_2" class="form-select elements" required>
                                            <option value="" hidden disabled selected>Select</option>
                                            @foreach($branch as $br)
                                            <option value="{{ $br->brCode }}">{{ $br->brName }}</option>    
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <input type="hidden" name="brCode" value="{{ session()->get('br_code') }}">    
                                <?php } ?>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <input type="hidden" name="id" class="form-control elements">
                                        <label for="roomType">Room Type</label>
                                        <select name="roomType" id="roomType2" class="form-select elements" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach (\App\Models\RoomType::all() as $rType)
                                            <option value="{{ $rType->rType }}" 
                                            data-id="{{ $rType->id }}"
                                            data-brc="{{ $rType->branch_id }}"
                                            >{{ $rType->rType }}</option>   
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="id2" id="typeId2">
                                        {{-- <input type="text" class="form-control elements" name="roomType" required> --}}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                    <label for="date">Select Date</label>
                                    <input type="text" name="date" id="date" class="form-control elements" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="regular">Regular</label>
                                        <input type="number" class="form-control elements" name="regular" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="weekly">Weekly</label>
                                        <input type="number" class="form-control elements" name="weekly" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="monthly">Monthly</label>
                                        <input type="number" class="form-control elements" name="monthly" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        {{-- <label for="updateVat">Vat %</label> --}}
                                        <label for="updateVat">Booking Fee %</label>
                                        <input type="number" id="updateVat" class="form-control elements" min="0" name="vat" required>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end py-3">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update Room Rate</button>
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
            <div class="row">
                <div class="col-12" style="display:flex;justify-content:center;">
                    {{ $roomTypes->links('pagination::bootstrap-4') }}
                </div>
            </div>
    </div>
            {{-- list view ------------------------------------------------------------------}}



            {{-- calendar view ------------------------------------------------------------------}}
            <div class="calendar-view" style="@if(isset($_GET['page'])) display:none; @else display:block; @endif">
            @if(session()->get('role_id') == 1)
            <form action="{{ url('room_types') }}" id="fmFilter" method="GET">
                <input type="hidden" name="view" value="calendar">
               <div class="row mb-5 mt-5">
                    <div class="col-md-4 offset-3">
                        <div class="form-grop">
                        <select name="branches" id="branches" class="form-select" required>
                            <option value="" disabled selected>Select Branch</option>
                            @foreach($branches as $brc)
                            <option value="{{ $brc->brCode }}"
                            @if(Request('branches'))
                            {{ $brc->brCode == Request('branches') ? 'selected' : '' }}    
                            @endif
                            >{{ $brc->brName }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                   <div class="col-md-2">
                       <a href="{{ url('room_types') }}" class="btn btn-outline-secondary">Clear Search</a>
                   </div>
               </div>
               </form>
               @endif
                <section class="regular slider">
                @forelse ($data->sortByDesc('id') as $card )
                    <div>
                    <div class="box-inner" id="box_{{ $card->id }}">
                    <h5>{{ date('d', strtotime($card->date)). ' ' .date('F', strtotime($card->date)) }}</h5>
                    <p class="card-text">{{ date('l', strtotime($card->date)) }}</p>
                    <p class="card-text">{{ $card->roomType }}</p>
                    <p class="card-text">{{ $card->branch->brName }}</p>
                    <table style="width:90%;margin:-8px auto;">
                      <tr>
                          <th>Regular:</th>
                          <td>{{ 'AED '.$card->regular }}</td>
                      </tr>
                      <tr>
                          <th>Weekly:</th>
                          <td>{{ 'AED '.$card->weekly }}</td>
                      </tr>
                      <tr>
                          <th>Monthly:</th>
                          <td>{{ 'AED '.$card->monthly }}</td>
                      </tr>
                    </table>
                    <button class="btn btn-outline-dark btn-sm rate-edit mt-3" id={{ $card->id }}>Edit</button>
                    <div class="rate-update" id="rate_{{ $card->id }}">
                    <hr>
                    <form action="{{ url('rates_update') }}" method="POST" id="frm_"{{ $card->id }}>
                    @csrf
                    <input type="hidden" name="id" value="{{ $card->id }}">   
                    <div class="row">
                        <div class="col-md-6 col-12">Regular</div>
                        <div class="col-md-6 col-12"><input type="number" name="regular" step="0.01" min="1" 
                            value="{{ $card->regular }}"
                            class="form-control element" disabled></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">Weekly</div>
                        <div class="col-md-6 col-12"><input type="number" name="weekly" step="0.01" min="1" 
                            value="{{ $card->weekly }}"
                            class="form-control element" disabled></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">Monthly</div>
                        <div class="col-md-6 col-12"><input type="number" name="monthly" step="0.01" min="1" 
                            value="{{ $card->monthly }}"
                            class="form-control element" disabled></div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            <button type="button" class="btn btn-dark btn-sm btn_close" data-id={{ $card->id }}>Close</button>
                        </div>
                    </div>
                    </form>
                    </div>
                    </div>  
                  </div>    
                @empty
                <div class="row">
                <div class="col-12">
                <img src="{{ asset('uploads/calendar-thumbnail.png') }}">    
                </div>    
                </div>
                @endforelse    
                </section>

            </div>

            {{-- calendar view ------------------------------------------------------------------}}


        </div>
    </div>
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>    
<script>
    $(document).ready(function(){
        $("#calendar_view").on("click",function(){
            $(".list-view").hide();
            $(".calendar-view").show();
        });
        $("#list_view").on("click",function(){
            $(".list-view").show();
            $(".calendar-view").hide();
        });

        $(".box-inner").on("click", ".rate-edit",function(){
            let id = $(this).attr('id');
            $("#box_"+id).animate({height: '430px'});
            $("#rate_"+id).show();
            $(".element").each(function(){
                $(this).removeAttr('disabled');
            });
        });

        $(".box-inner").on("click",".btn_close",function(){
            let id = $(this).data('id');
            $("#box_"+id).animate({height: '240px'});
            $("#rate_"+id).hide();
            $(".element").each(function(){
                $(this).attr('disabled','disabled');
            });
        });
       
    });
</script>
</section>
@endsection



