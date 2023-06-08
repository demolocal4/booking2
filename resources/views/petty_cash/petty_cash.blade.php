@extends('admin.layout.app')

@section('page_title', 'Petty Cash')

@section('content')

@php

use App\Http\Controllers\MainController;



$owners_br = MainController::ownersBr();

@endphp

<style>

    .dropdown-toggle:after {

        color: #999 !important;

    }

    .dropdown-menu {

        min-width: 10rem !important;

    }    

    .btn-outline-success {

       min-width: 130px !important;

    }



    #pettyChash tr:last-child td:nth-child(8) {

        color: rgb(231, 27, 27);

        font-weight: 600;

        position: relative;

    }

    

    

</style>

@php

$users = DB::table('users')->where('status', 1)->get();

if(session()->get('role_id') == 1) {

    $branches = DB::table('branchs')->get();    

}else if(session()->get('role_id') == 5) {

    $branches = DB::table('branchs')->whereIn('brCode', $owners_br)->get();

}else{

    $branches = DB::table('branchs')->where('brCode', session()->get('br_code'))->get();

}



$selected = '';

if(isset($_GET['code'])) {

    $selected = $_GET['code'];

}

@endphp

<h3>Petty Cash</h3>

<div class="row">

    <div class="col-md-12 col-12">

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

                    <button type="button" class="btn btn-success" style="width: 100%;">

                        Available <span class="badge bg-transparent">{{$available}}</span>

                    </button>

                </div>

                <div class="col-md-2 col-12">

                    <button type="button" class="btn btn-dark" style="width: 100%;">

                        Blocked <span class="badge bg-transparent">{{$blocked}}</span>

                    </button>

                </div>

                <div class="col-md-2 col-12">

                    <button type="button" class="btn today-checkout" style="width: 100%;">

                        T. Checkout <span class="badge bg-transparent">{{$todayCheckout}}</span>

                        {{-- Reservation <span class="badge bg-transparent">{{$reservation}}</span> --}}

                    </button>

                </div>

                <div class="col-md-2 col-12">

                    <button type="button" class="btn btn-primary" style="width: 100%;">

                        UnClean <span class="badge bg-transparent">{{$unclean}}</span>

                    </button>

                </div>

                <div class="col-md-2 col-12">

                    <button type="button" class="btn btn-danger" style="width: 100%;">

                        Occupied <span class="badge bg-transparent">{{$occupied}}</span>

                    </button>

                </div>

                <div class="col-md-1 col-12"></div>

            </div>

            <div class="card-header">

                {{-- <h4 class="card-title">Vertical Form</h4> --}}

                @if(Session::get('success'))

                        <div class="alert alert-success">

                        <i class="bi bi-check-circle"></i> 

                        {{ Session::get('success') }}

                        </div>

                    @endif

                    @if(Session::get('fail'))

                        <div class="alert alert-danger">

                        <i class="bi bi-check-circle"></i> 

                        {{ Session::get('fail') }}

                        </div>

                    @endif



            </div>

            <div class="card-content">

                <div class="card-body">

                    @if(session()->get('role_id') != 5)

                    <div class="row" style="margin-bottom: 30px;">

                        <div class="col-md-6 col-12">

                            <input type="button" value="PettyCash In" class="btn btn-success" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#pettyCashIn_modal">    

                        </div>    

                        <div class="col-md-6 col-12" style="text-align: right;">

                            <input type="button" value="PettyCash Out" class="btn btn-primary" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#pettyCash_modal">

                        </div>

                    </div>

                    @endif



                    <div class="row mb-5">

                        <div class="col-md-6 col-12">

                            @if(session()->get('role_id') == 1 || session()->get('role_id') == 5)

                            <label for="branch">Select Branch</label>

                            <select name="branch" id="branch" class="form-select" style="width: 50%;">

                                <option value="" hidden disabled selected>Select</option>

                                @foreach($branches as $br)

                                <option value="{{ $br->brCode }}" {{ $selected == $br->brCode ? 'selected' : '' }}>{{ $br->brName }}</option>

                                @endforeach

                            </select>

                            @endif

                        </div>

                        <div class="col-md-6 col-12" style="text-align: right;">

                           <input type="button" value="Print Report" class="btn btn-outline-success report" id="report" onclick='window.location.href="{{ url("pettyCashrpt") }}"'> 

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12 col-12">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">S.No</th>
                                        <th style="text-align: center;" width="150">Date</th>
                                        <th style="text-align: center;">Invoice No#</th>
                                        <th style="text-align: left;">Vendor Name</th>
                                        <th style="text-align: left;">Attached Inv</th>
                                        <th style="text-align: center;">CashIn</th>
                                        <th style="text-align: center;">CashOut</th>
                                        <th style="text-align: center;">Balance</th>
                                        <th style="text-align: center;">Created By</th>
                                        <th style="text-align: center;" width="150">Created At</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>  
                                <tbody id="pettyChash">
                                    @php
                                    $counter = 1;
                                    $balance = 0;
                                    @endphp
                                    @foreach($data as $list)
                                    @php
                                    $users = DB::table('users')->where('id', $list->created_by)->first();
                                    @endphp
                                    <tr>
                                        <td style="text-align: center;">{{ $counter++ }}</td>
                                        <td style="text-align: center;">{{ date('Y-m-d', strtotime($list->cashDate)) }}</td>
                                        <td style="text-align: center;">{{ $list->invNumber }}</td>
                                        <td>
                                            {{ $list->vendorName }}
                                            <span style="visibility:hidden" class="remarks">{{ $list->remarks }}</span>
                                            {{-- @if($list->vendorName == 'none')
                                            {{ $list->vendorName }}
                                            @else
                                            <a href="javascript:void(0);" class="inv-details" data-id="{{ $list->id }}" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#invDetails_modal">{{ $list->vendorName }}</a>
                                            @endif --}}
                                        </td>
                                        <td>
                                            @if($list->invPhoto != 'none')
                                            <a href="{{ asset('public/uploads/'.$list->invPhoto) }}" target="_blank">
                                            {{ $list->invPhoto }}
                                            </a>
                                            @else
                                            {{ $list->invPhoto }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{ $list->cashIn}}</td>
                                        <td style="text-align: center;">{{ $list->cashOut}}</td>
                                        <td style="text-align: center;" class="balance-cell">
                                            {{ $balance = number_format((float)($balance + $list->cashIn - $list->cashOut),2,'.','') }}
                                        </td>                                        
                                        <td style="text-align: center;">{{ ucfirst($users->name) }}</td>
                                        <td style="text-align: center;">{{ date('Y-m-d', strtotime($list->created_at)) }}</td>
                                        <td style="text-align: center;">
                                            <div class="dropdown options">
                                                <button class="btn btn-outline dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                                                    <i class="bi bi-menu-down"></i>

                                                </button>

    

    @if($list->vendorName != 'none')
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li>
        <a class="dropdown-item more" href="javascript:void(0);" data-id="{{ $list->id }}" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#invDetails_modal">
            <i class="bi bi-info-square"></i> More
        </a>
    </li>
    <li>
        <a class="dropdown-item edit" href="javascript:void(0);" data-id="{{ $list->id }}" data-fdate="" date-tdate=""><i class="bi bi-pencil-square"></i> Edit
        </a>
    </li>
    </ul>
    @else
    @if(session()->get('role_id') == 1)
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li>
    <a class="dropdown-item edit_cashIn" href="javascript:void(0);" data-id="{{ $list->id }}" data-fdate="" date-tdate=""><i class="bi bi-pencil-square"></i> Edit
    </a>
    </li>
    </ul>
    @endif
    @endif

                                              </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>   

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- PettyCash Out Modal form --}}

<div class="modal fade text-left" id="pettyCash_modal" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel4">Add PettyCash

                </h4>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <i class="bi bi-x-circle"></i>

                </button>

            </div>

            <div class="modal-body">

            <form action="{{ url('store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 col-12">

                    <div class="row">

                    <label for="datepicker">Select Date</label>

                    <div class="col-md-11 col-12">

                        <input type="text" name="date" id="datepicker" class="form-control" readonly value="{{ date('Y-m-d H:i:s') }}" required>

                    </div>

                    <div class="col-md-1 col-12">

                        <span class="cal" style="margin-top: 10px; cursor:pointer;display:inline-block;">

                            <img src="{{ asset('images/calendar.gif') }}">

                        </span> 

                    </div>

                    </div>

                    </div>



                    <div class="col-md-6 col-12">

                        <div class="row">

                            <label for="inv_number">Invoice No.</label>

                            <div class="col-md-12 col-12">

                            <input type="text" name="inv_number" id="inv_number" class="form-control" required>   

                            </div>

                        </div>

                    </div>    

                </div>

                <div class="row mt-3">

                    <div class="col-md-6 col-12">

                        <div class="row">

                            <label for="vendor_name">Vendor Name</label>

                            <div class="col-md-12 col-12">

                                <input type="text" name="vendor_name" id="vendor_name" class="form-control" required>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6 col-12">

                        <div class="row">

                            <label for="inv_copy">Invoice Copy</label>

                            <div class="col-md-12 col-12">

                                <input type="file" name="inv_copy" id="inv_copy" accept=".pdf,.jpeg,.jpg,.gif,.png" class="form-control" required>   

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-12 col-12">

                        <h3 class="text-center">Invoice Details</h3>

                    </div>

                </div>

                <div class="row mt-3 mb-3">

                    <div class="col-md-12 col-12" style="text-align: right">

                        <input type="button" value="+ Add more" class="btn btn-outline-dark btn-more">    

                    </div>

                </div>

                <hr>

                <div class="row mt-3">

                    

                    <table class="table table-striped">

                        <tbody id="details-section">

                            <tr>

                                <td>

                                    <input type="text" name="description[]" class="form-control" required placeholder="Description">

                                </td>

                                <td width="200">

                                    <input type="number" name="amount[]" class="form-control" min="1" step="0.01" required placeholder="Amount">

                                </td>

                                <td style="text-align: right;" width="20">

                                    {{-- <span class="btn-delete"><i class="bi bi-x-circle"></i></span> --}}

                                    <span class="asterisk" style="margin-top: 20px; display:inline-block;font-size:13px;">

                                        <i class="bi bi-asterisk"></i>

                                    </span>

                                    &nbsp;

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            

            </div>

            <div class="modal-footer">

                <div class="row">

                    <div class="col-md-12 col-12">

                        <input type="submit" value="Submit" class="btn btn-outline-primary" style="width: 150px;">

                    </div>

                </div>

            </div>

        </form>

        </div>

    </div>

</div>



{{-- PettyCash In Modal form --}}

<div class="modal fade text-left" id="pettyCashIn_modal" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel4">PettyCash In

                </h4>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <i class="bi bi-x-circle"></i>

                </button>

            </div>

            <div class="modal-body">

            <form action="{{ url('pettyCashIn') }}" method="POST">

                @csrf

                <div class="row">

                    <div class="col-md-6 col-12">

                    <label for="datepicker">Select Date</label>

                    <input type="text" name="cashin_date" id="datepickerin" class="form-control" readonly value="{{ date('Y-m-d H:i:s') }}" required>

                    </div>

                    <div class="col-md-1 col-12">

                        <span class="calIn" style="margin-top: 30px; cursor:pointer;display:inline-block;">

                            <img src="{{ asset('images/calendar.gif') }}">

                        </span>     

                    </div>

                </div>

                

                <div class="row">

                <div class="col-md-6 col-12">

                <label for="vc_number">Voucher No#</label>

                <input type="text" name="vc_number" id="vc_number" class="form-control" required>   

                </div>  

                <div class="col-md-6 col-12">

                <label for="amount">Amount</label>

                <input type="number" name="amount_in" id="amount_in" step="0.01" min="0" class="form-control" required>   

                </div>  

                </div>

                

                <div class="row">

                    <div class="col-md-12 col-12">

                        <label for="remarks">Remarks</label>

                        <input type="text" name="remarks" id="remarks" class="form-control">

                    </div>

                </div>

                 

            </div>

             

            <div class="modal-footer">

                <div class="row">

                    <div class="col-md-12 col-12">

                        <input type="submit" value="Submit" class="btn btn-outline-primary" style="width: 150px;">

                    </div>

                </div>

            </div>

        </form>

        </div>

    </div>

</div>

{{-- PettyCash Update Modal form at 04-04-2023 --}}
<div class="modal fade text-left" id="pettyCashUpdate_modal" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">PettyCash Update
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ url('pettyCashUpdate') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="row_id" class="form-control">
                <div class="row">
                    <div class="col-md-6 col-12">
                    <label for="datepicker">Select Date</label>
                    <input type="text" name="cashupdate_date" id="datepickerupdate" class="form-control" readonly value="" required>
                    </div>
                    <div class="col-md-1 col-12">
                        <span class="calupdate" style="margin-top: 30px; cursor:pointer;display:inline-block;">
                            <img src="{{ asset('images/calendar.gif') }}">
                        </span>     
                    </div>
                </div>
                
                <div class="row">
                <div class="col-md-6 col-12">
                <label for="vc_number">Voucher No#</label>
                <input type="text" name="vc_number_update" id="vc_number_update" class="form-control" required>   
                </div>  
                <div class="col-md-6 col-12">
                <label for="amount">Amount</label>
                <input type="number" name="amount_update" id="amount_update" step="0.01" min="0" class="form-control" required>   
                </div>  
                </div>
                
                <div class="row">
                    <div class="col-md-12 col-12">
                        <label for="remarks">Remarks</label>
                        <input type="text" name="remarks_update" id="remarks_update" class="form-control">
                    </div>
                </div>
                 
            </div>
             
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <input type="submit" value="Update" class="btn btn-outline-primary" style="width: 150px;">
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>



{{-- PettyCash Invoice Details --}}



<div class="modal fade text-left" id="invDetails_modal" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel4">Invoice Details

                </h4>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <i class="bi bi-x-circle"></i>

                </button>

            </div>

            <div class="modal-body">

            <table class="table table-striped">

                <thead>

                    <tr>

                        <th>Description</th>

                        <th width="150" style="text-align: center;">Amount</th>

                    </tr>

                </thead>

                <tbody id="detailsBody">

                </tbody>

            </table>

            </div>

            <div class="modal-footer">

              

            </div>

        </div>

    </div>

</div>



{{-- Update PettyCash Out Modal form --}}

<div class="modal fade text-left" id="updatePettyCash_modal" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title" id="myModalLabel4">Update PettyCash

                </h4>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <i class="bi bi-x-circle"></i>

                </button>

            </div>

            <div class="modal-body">

            <form action="{{ url('update_pettycash') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    

                    <div class="col-md-6 col-12">

                        <div class="row">

                            <label for="inv_number">Invoice No.</label>

                            <div class="col-md-12 col-12">

                            <input type="text" name="inv_number" id="uinv_number" class="form-control" required>   

                            </div>

                        </div>

                    </div>    

                </div>

                <div class="row mt-3">

                    <div class="col-md-6 col-12">

                        <div class="row">

                            <label for="vendor_name">Vendor Name</label>

                            <div class="col-md-12 col-12">

                                <input type="text" name="vendor_name" id="uvendor_name" class="form-control" required>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6 col-12">

                        <div class="row">

                            <label for="inv_copy">Invoice Copy</label>

                            <div class="col-md-12 col-12">

                                <input type="file" name="inv_copy" id="inv_copy" accept=".pdf,.jpeg,.jpg,.gif,.png" class="form-control">   

                                <input type="hidden" name="hinv_copy" id="hinv_copy" value="">

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-12 col-12">

                        <h3 class="text-center">Invoice Details</h3>

                    </div>

                </div>

                <div class="row mt-3 mb-3">

                    <div class="col-md-12 col-12" style="text-align: right">

                        <input type="button" value="+ Add more" class="btn btn-outline-dark btn-umore">    

                    </div>

                </div>

                <hr>

                <div class="row mt-3">

                   

                    <table class="table table-striped">

                        <tbody id="udetails-section">

                          

                        </tbody>

                    </table>

                </div>

            

            </div>

            <div class="modal-footer">

                <div class="row">

                    <div class="col-md-12 col-12">

                        <input type="hidden" name="row_id" id="row_id" value="">

                        <input type="submit" value="Update" class="btn btn-outline-warning" style="width: 150px;">

                    </div>

                </div>

            </div>

        </form>

        </div>

    </div>

</div>



@endsection

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    

    $(document).ready(function(){

        setTimeout(() => {

                 $(".alert").slideUp();

            }, 3000);


        $("#datepicker").datetimepicker();
        $(".cal").on("click",function(){
            $("#datepicker").datetimepicker({
                format:'Y-m-d H:i:s',
                timepicker:false,
                // minDate:'<?php echo date("Y-m-d"); ?>',
            }).datetimepicker('show');

        });

        $("#datepickerin").datetimepicker();
        $(".calIn").on("click",function(){
            $("#datepickerin").datetimepicker({
                format:'Y-m-d H:i:s',
                timepicker:false,
                // minDate:'<?php echo date("Y-m-d"); ?>',
            }).datetimepicker('show');
        });

        // PettyCash update module added on 04-04-2023
        $("#pettyChash").on("click",".edit_cashIn",function(){
            let id = $(this).data('id');
            $("#pettyCashUpdate_modal").modal("show");
            $("#row_id").val(id);

            $("#datepickerupdate").val($(this).closest('tr').find('td:nth-child(2)').html());
            $("#vc_number_update").val($(this).closest('tr').find('td:nth-child(3)').html());
            $("#amount_update").val($(this).closest('tr').find('td:nth-child(6)').html());
            $("#remarks_update").val($(this).closest('tr').find('td .remarks').html());

        });

        $("#datepickerupdate").datetimepicker();
        $(".calupdate").on("click",function(){
            $("#datepickerupdate").datetimepicker({
                format:'Y-m-d H:i:s',
                timepicker:false,
                // minDate:'<?php echo date("Y-m-d"); ?>',
            }).datetimepicker('show');
            
        });
        // PettyCash update module added on 04-04-2023


        $(".btn-more").on("click",function(){

                $("#details-section").append('<tr>'+

    '<td><input type="text" class="form-control" name="description[]" placeholder="Description" required></td>' +

    '<td><input type="number" class="form-control" name="amount[]" placeholder="Amount" step="0.01" required></td>' +

    '<td><span class="btn-delete"><i class="bi bi-x-circle"></i></span></td>'                    

                +'</tr>');

        });





        $("#details-section").on("click",".btn-delete",function(){



            $(this).closest('tr').remove();



        });



        $("#pettyChash").on("click",".more",function(){

            let refid = $(this).data('id');

            $.ajax({



                    url: '{{ url("cashDetails") }}/'+refid,    

                    type: 'GET',

                    success:function(response) {

                        $("#detailsBody").empty();

                        $.each(response.data, function(key, val){



                               $("#detailsBody").append('<tr>'+

                                                        '<td>'+ val.description +'</td>'+

                                                        '<td style="text-align:center;">'+ val.amount +'</td>'

                                                        +'</tr>');



                        });

                    }



            });

        });





        $("#pettyChash").on("click",".edit",function(){

            let id = $(this).data('id');

            $("#udetails-section").empty();

            $.ajax({



                    url: '{{ url("pettyCashedit") }}/'+id,    

                    type: 'GET',

                    success:function(response) {

                       

                        $("#row_id").val(response.parent.id);

                        $("#uinv_number").val(response.parent.invNumber);

                        $("#uvendor_name").val(response.parent.vendorName);

                        $("#hinv_copy").val(response.parent.invPhoto);

                        

                        $.each(response.child, function(key, val){



    $("#udetails-section").append('<tr>'+

    '<td><input type="text" class="form-control" name="description[]" placeholder="Description" value="'+ val.description +'" required></td>' +

    '<td><input type="number" class="form-control" name="amount[]" placeholder="Amount" value="' + val.amount + '" step="0.01" required><input type="hidden" name="child_id[]"  value="' + val.id + '" ></td>' +

    '<td><span class="btn-udelete" data-id="'+ val.id +'"><i class="bi bi-x-circle"></i></span></td>'                    

                +'</tr>');        



                        });



                        $("#updatePettyCash_modal").modal('show');



                        

                    }



            });

        });



        $(".btn-umore").on("click",function(){

                $("#udetails-section").append('<tr>'+

    '<td><input type="text" class="form-control" name="description[]" placeholder="Description" required></td>' +

    '<td><input type="number" class="form-control" name="amount[]" placeholder="Amount" step="0.01" required><input type="hidden" name="child_id[]" value=""></td>' +

    '<td><span class="btn-udelete"><i class="bi bi-x-circle"></i></span></td>'                    

                +'</tr>');

        });



        $("#udetails-section").on("click",".btn-udelete",function(){

            let id = $(this).data('id') ? $(this).data('id') : 0;

            let thisRow = $(this).closest("tr");

            let totalRow = thisRow.closest('#udetails-section').find('tr').length;



            if(id != 0) {



                 $.get('{{ url("deleteCash") }}/'+id, function(response){



                            if(confirm('Are you sure want to delete?')) {

                                    

                                if(response.status == 'success') {



                                        if(!alert(response.message)){



                                            if(totalRow > 1) thisRow.remove();



                                        }



                                }



                            }else{

                                return false;

                            }



                            if(response.status == 'fail') {



                                alert(response.message);

                            }



                 });  



            }else{

                

                $(this).closest('tr').remove();

                // if(totalRow > 1) thisRow.remove();

            }

                

               

            



        });



        $("#branch").on("change",function(){

            let code = $(this).val();

            window.location.href = '{{ url("petty_cash") }}'+'/?code='+code;

        });



        

    });

</script>