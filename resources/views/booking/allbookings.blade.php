@extends('admin.layout.app')
@section('page_title', 'All Bookings')
@section('content')
@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\MainController;

$owners_br = MainController::ownersBr();
@endphp
<style>
    .pagination {
        display: flex;
        justify-content: center;
    }
    .link {
        display: flex;
        justify-content: center;
        align-content: center;
    }
    .dataTable-dropdown {
        display: none;
    }
    .dataTable-pagination {
        display: none;

    }
    .dataTable-search {
        display: none;
    }
    .note {
            font-size: 13px;
            color: red;
    }
    .hide {
    	display: none;
    }
    .dataTable-bottom {
        display: none;
    }
</style>

<h3>All Bookings</h3>
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
            
            <div class="col-md-2 col-12 offset-1">
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
            
        </div>
        <div class="card-header">
            <div class="col-md-12 col-12 mt-3">
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
        </div>

        <form action="{{ url('filter_booking') }}" id="fmFilter" method="post">
            @csrf 
           <div class="row mb-5" style="margin-left: 15px;">
                    <div class="col-md-2 offset-1">
                    <select name="filter_field" id="filter_field" class="form-select" required>
                        <option value="" selected hidden disabled>Select Filter Field</option>
                        <option value="id" @isset($data) {{ ($data['field_name'] == 'id') ? 'selected' : '' }} @endisset>Booking Number</option>
                        <option value="room_no" @isset($data) {{ ($data['field_name'] == 'room_no') ? 'selected' : '' }} @endisset>Room No</option>
                        <option value="customer_name" @isset($data) {{ ($data['field_name'] == 'customer_name') ? 'selected' : '' }} @endisset>Customer Name</option>
                        <option value="mobile_no" @isset($data) {{ ($data['field_name'] == 'mobile_no') ? 'selected' : '' }} @endisset>Mobile Number</option>    
                    </select>
                </div>
               <div class="col-md-4">
                    <input type="text" name="search" id="search" class="form-control" value="{{ $data['name'] ?? '' }}" placeholder="Find What?">
                    <span class="note" style="display:none;">Search with "Booking Ref", "Customer Name" Or "Mobile"</span> 
               </div>
               <div class="col-md-2">
                   <button type="submit" name="btn_filter" class="btn btn-primary" style="width: 85%;">Search</button> 
               </div>
               <div class="col-md-2">
                   <button type="button" name="btn_clear" id="btn_clear" class="btn btn-outline-secondary" style="width: 85%;">Clear Search</button>
               </div>
           </div>
           </form>

            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th style="text-align: center">S.No</th>
                            <th style="text-align: center">Book Ref#</th>
                            @if(session()->get('role_id') == 1)
                            <th>Br.Name</th>
                            @endif
                            {{-- <th>Floor</th> --}}
                            <th>Room No</th>
                            <th>Checkin Date</th>
                            <th>Checkout Date</th>
                            <th>No.Nights</th>
                            <th>Room Type</th>
                            
                            {{-- <th>No.Beds</th>
                            <th>No.Persons</th> --}}

                            <th>Customer Name</th>
                            <th>Mobile No#</th>

                            {{-- <th>Nationality</th>
                            <th>ID/Passport#</th>
                            <th>Daily Tariff</th>
                            <th>Monthly Tariff</th>
                            <th>Vat.Amt</th>
                            <th>Total Amount</th>--}}
                            <th>Advance Paid</th> 

                            <th>Balance</th>
                            {{-- <th>Payable Amount</th>
                            <th>Payment Mode</th>
                            <th>Payment done</th>
                            <th>Card Code</th>
                            <th>Card Expiry</th>
                            <th>Cheque Copy</th>
                            <th>Document Copy</th>
                            <th>Created by</th>
                            <th>Updated by</th>
                            <th>Created at</th>
                            <th>Updated at</th> --}}
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody id="bookingBody">
                        @php 
                        $counter = 1;
                        @endphp
                        @foreach($allbookings as $bookings)
                        @php
                        $brname = DB::table('branchs')->where('id', $bookings->brCode)->first();
                        @endphp
                        <tr>
                            <td style="text-align: center">{{ $counter++ }}</td>
                            <td style="text-align: center">{{ $bookings->id }}</td>
                            @if(session()->get('role_id') == 1)                            
                            <td style="text-align: center">{{ $brname->brName }}</td>
                            @endif
                            @php
                            $floor = DB::table('rooms')->where('id', $bookings->roomRef)->get();
                            @endphp
                            {{-- <td style="text-align: center">{{ $floor[0]->floor }}</td> --}}

                            <td style="text-align: center">
                                {{-- data-bs-target="#booking_details" --}}
                                <a href="javascript:void(0)" data-id="{{ $bookings->id }}" class="booking_details">
                                {{ $bookings->room_no }}
                                </a>
                            </td>
                            <td style="text-align: center">{{ date('Y-m-d',strtotime($bookings->checkin_date)) }}</td>
                            <td style="text-align: center">{{ date('Y-m-d',strtotime($bookings->checkout_date)) }}</td>
                            <td style="text-align: center">{{ $bookings->no_nights }}</td>
                            <td style="text-align: center">{{ $bookings->room_type }}</td>

                            {{-- <td style="text-align: center">{{ $bookings->capacity }}</td>
                            <td style="text-align: center">{{ $bookings->no_persons }}</td> --}}

                            <td style="text-align: center">{{ $bookings->customer_name }}</td>
                            <td style="text-align: center"><a href="tel:{{ $bookings->mobile_no }}">{{ $bookings->mobile_no }}</a></td>
                            
                            {{-- <td style="text-align: center">{{ $bookings->nationality }}</td>
                            <td style="text-align: center">{{ $bookings->id_passport }}</td>
                            <td style="text-align: center">{{ $bookings->room_tariff }}</td>
                            <td style="text-align: center">{{ $bookings->monthly_tariff }}</td>
                            <td style="text-align: center">{{ $bookings->vat }}</td>
                            <td style="text-align: center">{{ $bookings->total_amount }}</td> --}}
                            
                            <td style="text-align: center" class="advAmt">
                            {{ $bookings->advance_paid }}
                            <input type="hidden" name="cancelAmt" id="cancelAmt" value="{{ $bookings->advance_paid }}">
                            </td> 
                            
                            <td style="text-align: center">{{ $bookings->balance }}</td>
                            
                            {{-- <td style="text-align: center">{{ $bookings->totalPayAmount }}</td>
                            <td style="text-align: center">{{ $bookings->payment_mode }}</td>
                            <td style="text-align: center">{{ $bookings->payment_done }}</td>
                            <td style="text-align: center">{{ ($bookings->card_code == null) ? '-' : $bookings->card_code }}</td>
                            <td style="text-align: center">{{ ($bookings->card_expiry == null) ? '-' : $bookings->card_code }}</td>
                            <td style="text-align: center">{{ $bookings->cheque_photo }}</td>
                            <td style="text-align: center"><a href="{{asset('uploads/'.$bookings->doc_photo)}}">{{ $bookings->doc_photo }}</a></td>
                            <td style="text-align: center">{{ $bookings->created_by }}</td>
                            <td style="text-align: center">{{ $bookings->updated_by }}</td>
                            <td style="text-align: center">{{ $bookings->created_at }}</td>
                            <td style="text-align: center">{{ $bookings->updated_at }}</td> --}}
                            
                            <td width="130" style="text-align: right;">
                            @php 
                            // $start = date('h:i:s', strtotime($bookings->checkin_date));
                            // $end = date('h:i:s');
                            // $newStart = new DateTime($start);
                            // $newEnd = new DateTime($end);
                            // $interval = $newStart->diff($newEnd);
                            $duration = DB::table('settings')->where('setting_name', 'time_duration')->first();
                            $t1 = strtotime( $bookings->checkin_date );
                            $t2 = strtotime( date('Y-m-d H:i:s') );
                            $diff =  $t2 - $t1;
                            $hours = $diff / ( 60 * 60 );
                            $hours = $hours * 60;
                            $bufferTime = $duration->setting_val;                        
                            @endphp 

                            {{-- && $bookings->balance !=0 --}}

                            @if($hours < $bufferTime && $bookings->checkout_by != 'Cancelled' && $bookings->checkout_by != 'Checkout')
                            @if(session()->get('role_id') != 5)
                            <a href="javascript:void(0)" id="btn_bCancel" data-v1="{{ $bookings->brCode }}" data-v2="{{ $bookings->advance_paid }}" data-id="{{ $bookings->id }}" class="btn btn-outline-danger btn-sm btn_bCancel" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#booking_cancel">Cancel</a>
                            @endif

                            @else
                            	<!-- $bookings->checkout_by -->
                                
                                @if($bookings->checkout_by != 'occupied' && $bookings->checkout_by != 'Cancelled')
                                <span style="color:red"> Checkout </span>
                                @else
                                <span style="color:red"> {{ $bookings->checkout_by }} </span>
                                @endif                                

                                {{-- @if($bookings->balance != 0)
                                <span style="color:red"> {{ ucfirst($bookings->checkout_by) }} </span>
                                @else
                                <span style="color:red"> Checkout </span>    
                                @endif --}}

                            @endif
                            <span class="print-ico">
                                    <a href="{{ url('reprint_cash_receipt/'.$bookings->id) }}" class="print-link">
                                    <i class="bi bi-printer"></i>
                                    </a>
                                </span>
                                @if($bookings->checkout_by == 'Checkout')
                                <span class="print-ico">
                                    <a href="{{ url('invoice_reprint/'.$bookings->id) }}">
                                        <i class="bi bi-file-earmark-break"></i>
                                    </a>
                                </span>
                                @endif
                                @if($bookings->checkout_by != 'Checkout')
                                <span class="print-ico">
                                    <a href="javascrip:;" class="btn_transfer" data-room="{{ $bookings->room_no }}" data-id="{{ $bookings->id }}" title="Room Transfer" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#roomTransferModel">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </a>
                                </span>
                                @endif
                            <td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                	<div class="link">
                		<div class="pages">{{ $allbookings->appends(Request::except('page'))->links('pagination::bootstrap-4') }}</div>
                	</div>
                </div>                 
            </div>

    <!--Disabled Backdrop Modal booking cancel -->
    <div class="modal fade text-left" id="booking_cancel" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Booking Cancellation
                    </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form form-vertical" action="{{ route('booking_cancel') }}">
                        @csrf
                        <input type="hidden" name="refId" id="refId">
                        <input type="hidden" name="brcode" id="brcode">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <label for="refundedAmt">Refunded Amount</label>
                                    <div class="form-group">
                                        <input type="number" name="refundedAmt" min="0" id="refundedAmt" required class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <label for="remarks">Remarks</label>
                                    <div class="form-group">
                                        <input type="text" name="remarks" id="remarks" required class="form-control">
                                    </div>
                                </div>
                               

                                <div class="col-12 d-flex justify-content-end py-3">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Booking Cancel</button>
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

    <!--Disabled Backdrop Modal booking details -->
    <div class="modal fade text-left" id="booking_details" tabindex="-1" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel4">Booking Details
                    </h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-body allbooking">
                           <div class="row">
                               <div class="col-md-12 col-12">
                                   <p class="booking_ref"></p>
                               </div>
                                <div class="col-md-12 col-12">
                                <p class="branch_name"></p>
                                </div>
                                <div class="col-md-12 col-12">
                                <p class="no_nights"></p>
                                </div>
                                <div class="col-md-12 col-12">
                                <p class="booking_com_ref"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                <p><label for="">CheckIn Date</label></p>
                                <p class="checkindate"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                <p><label for="">CheckOut Date</label></p>
                                <p class="checkoutdate"></p>
                                </div>
                                <div class="col-md-2 col-12">
                                <p><label for="">Room No</label></p>
                                <p class="roomno"></p>
                                </div> 
                                <div class="col-md-2 col-12">
                                    <p><label for="">Room Type</label></p>
                                    <p class="roomtype"></p>
                                </div>
                                <div class="col-md-2 col-12">
                                    <p><label for="">No.Persons</label></p>
                                    <p class="noperson"></p>
                                </div>

                               <div class="col-md-3 col-12">
                                <p><label for="">Customer Name</label></p>
                                <p class="customer_name"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Customer Mobile</label></p>
                                    <p class="customer_mobile"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Nationality</label></p>
                                    <p class="nationality"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">ID/Passport</label></p>
                                    <p class="id_passport"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Daily Tariff</label></p>
                                    <p class="daily_tariff"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Monthly Tariff</label></p>
                                    <p class="monthly_tariff"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Vat</label></p>
                                    <p class="vat"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Total Amount</label></p>
                                    <p class="total_amt"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Advance Paid</label></p>
                                    <p class="adv_paid"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Balance</label></p>
                                    <p class="balance"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Payment Mode</label></p>
                                    <p class="pay_mode"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Payment Done</label></p>
                                    <p class="pay_done"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Card Code</label></p>
                                    <p class="card_code"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Card Expiry</label></p>
                                    <p class="card_expiry"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Cheque Photo</label></p>
                                    <p class="check_photto"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Attached Doc</label></p>
                                    <p class="attached_doc"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Created by</label></p>
                                    <p class="created_by"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Updated by</label></p>
                                    <p class="updated_by"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Created at</label></p>
                                    <p class="created_at"></p>
                                </div>
                                <div class="col-md-3 col-12">
                                    <p><label for="">Updated at</label></p>
                                    <p class="updated_at"></p>
                                </div>


                           </div>
                        </div>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
           
</div>

<!--Disabled Backdrop Modal booking details -->
<div class="modal fade text-left" id="receipt_no_modal" tabindex="-1" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Reprint Receipt
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="receipt_no">Select Receipt No#</label>
                                    <select name="receipt_no" id="receipt_no" class="form-select">
                                         <option value="" selected hidden disabled>Select</option>   
                                    </select>
                                </div>
                            </div>    
                    </div>                    
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<!--Room Transfer Models -->
<div class="modal fade text-left" id="roomTransferModel" tabindex="-1" aria-labelledby="myModalLabel4" data-bs-backdrop="false" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Room Transfer
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                    @php
                        if(session()->get('role_id') == 1) {
                            $brc = DB::table('branchs')->get();
                        }else{
                            $brc = DB::table('branchs')->where('brCode', session()->get('br_code'))->get();
                        }
                    @endphp
                    <form action="{{ route('booking.roomTransfer') }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    <input type="hidden" name="booking_id" id="booking_id">    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="curr_room">Current Room</label>
                                <input type="text" id="curr_room" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_room">New Room</label>
                                <select name="new_room" id="new_room" class="form-select" required>
                                    <option value="" selected disabled>Select new Room</option>
                                        @if($brc)
                                        @foreach ($brc as $b)
                                        <optgroup label="&#8618; {{ $b->brName }}">
                                            @foreach (DB::table('floors')->where('brCode', $b->brCode)->get() as $floor)
                                            <optgroup label="&nbsp;&nbsp;&nbsp;&#8210; {{ $floor->floor }}">
                                                @foreach (DB::table('rooms')->where('floorRef', $floor->id)->whereNotIn('roomStatus', [3, 8])->get() as $room)
                                                <option value="{{ $room->id }}"
                                                    @if($room->roomStatus == 3)
                                                    @php
                                                        $status = 'Blocked';
                                                    @endphp
                                                    style="color:dimgray;"
                                                    @endif
                                                    @if($room->roomStatus == 5)
                                                    @php
                                                        $status = 'Available';
                                                    @endphp
                                                    style="color:green;"
                                                    @endif
                                                    @if($room->roomStatus == 7)
                                                    @php
                                                        $status = 'Unclean';
                                                    @endphp
                                                    style="color:navy;"
                                                    @endif
                                                    @if($room->roomStatus == 8)
                                                    @php
                                                        $status = 'Occupied';
                                                    @endphp
                                                    style="color:red;"
                                                    @endif
                                                    >
                                                    &nbsp;&nbsp;&nbsp;{{ $room->roomNo }} - {{  $status }}
                                                </option>
                                                @endforeach
                                            </optgroup>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary float-end">Transfer</button>
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

        $("#fmFilter").on("submit",function(e){
        e.preventDefault();
        let val = $("#search").val();
        if(val == '') {
            alert('What you want to find?');
            $("#search").focus();          
        }else{
            $(this).get(0).submit();
        }
        });

        $("#btn_clear").on("click",function(){
              window.location.href = '{{ url("all_bookings") }}';  
        });

        // setTimeout(() => {
        //          $(".alert").slideUp();
        //     }, 3000);

        $("#bookingBody").on("click",".btn_bCancel",function(){
            
            
            let bCode = $(this).data('v1');
            let PaidAmount = $(this).data('v2');
            //let advAmount = parseInt($("#advAmt").val());
            $("#brcode").val(bCode);
            $("#refundedAmt").val(PaidAmount);
            $("#remarks").focus();
            $("#refId").val($(this).data('id'));
            
                        
        });

        $("#bookingBody").on("click",".booking_details",function(){

            let id = $(this).data('id');
            
            $(".loader").show();
            $.ajax({

                    url: '{{ url("booking_details") }}',
                    type:'GET',
                    data: {id:id},
                    success:function(response) {
                        $(".loader").hide();
                        $(".booking_ref").html('Booking Ref# '+response.data.id);
                        $(".branch_name").html('Branch Name: '+response.branch.brName);
                        $(".no_nights").html('No of Nights #: '+response.data.no_nights);
                        if(response.data.booking_com_ref != null) {
                        $(".booking_com_ref").html('Booking.com Ref-ID #: '+response.data.booking_com_ref);
                        }else{
                        $(".booking_com_ref").html('Booking.com Ref-ID #: -');    
                        }
                        $(".checkindate").html(response.data.checkin_date);
                        $(".checkoutdate").html(response.data.checkout_date);
                        $(".roomno").html(response.data.room_no);
                        $(".roomtype").html(response.data.room_type);
                        $(".noperson").html(response.data.no_persons);
                        $(".daily_tariff").html(response.data.room_tariff);
                        $(".monthly_tariff").html(response.data.monthly_tariff);
                        $(".vat").html(response.data.vat);
                        $(".total_amt").html(response.data.total_amount);
                        $(".adv_paid").html(response.data.advance_paid);
                        $(".balance").html(response.data.balance);
                        $(".pay_mode").html(response.data.payment_mode);
                        $(".pay_done").html(response.data.payment_done);
                        $(".card_code").html(response.data.card_code);
                        $(".card_expiry").html(response.data.card_expiry);
                        $(".check_photto").html(response.data.cheque_photo);
                        $(".attached_doc").html('<a href="uploads/'+ response.data.doc_photo +'">'+ response.data.doc_photo +'</a>');
                        $(".created_by").html(response.users.name);
                        $(".updated_by").html(response.users.name);
                        $(".created_at").html(response.data.created_at);
                        $(".updated_at").html(response.data.updated_at);
                        $(".customer_name").html(response.data.customer_name);
                        $(".customer_mobile").html(response.data.mobile_no);
                        $(".nationality").html(response.data.nationality);
                        $(".id_passport").html(response.data.id_passport);
                        $("#booking_details").modal('show');
                    }

            });
           

        });

        $("#bookingBody").on("click", ".print-link",function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        $("#receipt_no option:not(:first)").remove();
        $(".loader").show();
                $.get(url, function(response){
                    $(".loader").hide();
                        //console.log(response.data);
                        $.each(response.data, function(key,val){
                                $("#receipt_no").append($('<option>', {
                                    value:val.refId,
                                    text:val.cr_no,

                                }));
                        });
                        $("#receipt_no_modal").modal('show');

                });

        });

        $("#receipt_no").on("change",function(){
                
            let id = $(this).val();
            let cr_no = $("#receipt_no option:selected").text();
            
            // $.ajax({

            //          url: '{{url("reprint_receipt")}}',       
            //          type: 'GET',
            //          data: {id:id, crno:cr_no},
            //          success:function(msg) {
                       
            //             if(msg != '') {
                            
            //                 window.location.href = '{{ route("reprint_receipt") }}';
                            
            //             }

            //          }

            // });

            window.location.href = '{{ url("reprint_receipt") }}'+'/?id='+id+'&cr_no='+cr_no;

        });

         $(".btn_transfer").on("click", function(){
            let room_no = $(this).data('room');
            let book_id = $(this).data('id');
            $("#booking_id").val(book_id);
            $("#curr_room").val(room_no);
            
        });
        
    });
</script>
@endsection