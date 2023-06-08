@extends('admin.layout.app')

@section('page_title', 'Manage Room Types')

@section('content')

@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

$users = DB::table('users')->where('id', session()->get('user_id'))->get();

@endphp

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
            @if($access->canAdd == 1)
            <a href="javascript:void(0);" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#backdrop">Add Room Rates</a>

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
            @error('roomType')
                <div class="alert alert-warning">
                {{ $message }}
                </div>
            @enderror

                    

        </div>

        <div class="card-body">

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
                   @foreach($roomTypes as $list)
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
    </div>
</section>
@endsection



