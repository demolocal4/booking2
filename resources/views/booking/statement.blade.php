@extends('admin.layout.app')

@section('page_title', 'Bookings.com Statement')

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



<h3>Booking.com Bookings</h3>

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



        <form action="{{ url('filter_statement') }}" id="fmFilter" method="post">

            @csrf 

           <div class="row mb-5" style="margin-left: 15px;">

                    <div class="col-md-2 col-12">

                    <select name="filter_field" id="filter_field" class="form-select" required>
                        <option value="" selected hidden disabled>Select Filter Field</option>
                        <option value="id" @isset($data) {{ ($data['field_name'] == 'id') ? 'selected' : '' }} @endisset>Booking ID</option>
                        <option value="booking_com_ref" @isset($data) {{ ($data['field_name'] == 'booking_com_ref') ? 'selected' : '' }} @endisset>Booking.com Ref-ID</option><option value="payout_id" @isset($data) {{ ($data['field_name'] == 'payout_id') ? 'selected' : '' }} @endisset>Payout ID</option>  
                        <option value="payout_date" @isset($data) {{ ($data['field_name'] == 'payout_date') ? 'selected' : '' }} @endisset>Payout Date</option>  
                        <option value="receiveable" @isset($data) {{ ($data['field_name'] == 'receiveable') ? 'selected' : '' }} @endisset>Receiveable</option> 
                    </select>
                </div>

               <div class="col-md-4">

                    <input type="text" name="search" id="search" class="form-control" value="{{ $data['name'] ?? '' }}" placeholder="Find What?">

                    <span class="note" style="display:none;">Search with "Booking Ref", "Customer Name" Or "Mobile"</span> 

               </div>

               <div class="col-md-2 col-12">

                   <button type="submit" name="btn_filter" class="btn btn-primary" style="width: 85%;">Search</button> 

               </div>

               <div class="col-md-2 col-12">

                   <button type="button" name="btn_clear" id="btn_clear" class="btn btn-outline-secondary" style="width: 85%;">Clear Search</button>

               </div>

               <div class="col-md-2 col-12">

                <div class="dropdown options">

                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                      Options

                    </button>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                    <li style="float:right;">

                    <a class="dropdown-item print" href="javascript:void(0);"><i class="bi bi-printer"></i> Print</a>

                    <a class="dropdown-item export-csv" href="javascript:void(0);"><i class="bi bi-file-break"></i> Export xls</a>

                    </li>

                    </ul>

                  </div>

            </div>

           </div>

           </form>



            <div class="card-body">
                <form action="{{ url('payout') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-1 col-12">
                    <button class="btn btn-outline-dark" id="btn_payout">Payout</button>    
                    </div>
                    <div class="col-md-3 col-12">
                    <input type="number" name="payout_id" id="payout_id" class="form-control" disabled placeholder="Payout ID#">    
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group" style="display:flex;align-items: center;">
                            <label for="payout_date">Date:</label>&nbsp;
                            <input type="date" name="payout_date" id="payout_date" class="form-control" disabled value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>


                <table class="table table-striped" id="table1">

                    <thead>

                        <tr>

                            <th class="noprint">#</th>

                            <th style="text-align: center">Book Ref#</th>

                            <th style="text-align: center">Booking.com Ref#</th>

                            @if(session()->get('role_id') == 1)

                            <th>Br.Name</th>

                            @endif

                            <th class="text-center">Amount</th>

                            <th class="text-center">Commission</th>

                            <th class="text-center">Pay Charge</th>

                            <th class="text-center">Online Vat</th>

                            <th class="text-center">Net.Amt</th>

                            <th class="text-center">Payout ID</th>

                            <th class="text-center">Payout Date</th>

                        </tr>

                    </thead>

                    <tbody id="bookingBody">

                        @php 
                        $counter = 1;
                        $sortBooking = $allbookings->where('checkout_by', '!=', 'occupied');
                        $netAmt = 0;
	                    @endphp
                        @foreach($sortBooking as $bookings)

                        <tr>

                        <td class="noprint">

                            <div class="form-check">

                                <div class="checkbox">

                                    <input type="checkbox" name="payCheck[]" class="form-check-input payCheck" 

                                    value="{{ $bookings->id }}"

                                    {{ $bookings->payout_id != null ? 'disabled' : '' }}

                                    {{ $bookings->payout_id != null ? 'checked' : '' }}

                                    >

                                    <label for="payCheck"></label>

                                </div>

                            </div>

                            {{-- <input type="text" name="id[]" value="{{ $bookings->id }}"> --}}

                        </td>

                        <td style="text-align: center">{{ $bookings->id }}</td>

                        <td style="text-align: center">{{ $bookings->booking_com_ref }}</td>

                        @if(session()->get('role_id') == 1)                            

                        <td>{{ $bookings->branch->brName }}</td>

                        @endif

                        <td class="text-center amt">{{ $bookings->total_amount }}</td>

                        <td class="text-center comm">{{ $bookings->commission }}</td>

                        <td class="text-center pay">{{ $bookings->payment_charge }}</td>

                        <td class="text-center vat">{{ $bookings->vat_online }}</td>

                        <td class="text-center netAmt">
                        	{{ 
                        	$netAmt = $bookings->total_amount - $bookings->commission - $bookings->payment_charge - $bookings->vat_online 
                        	}}
                    </td>

                        <td class="text-center">{{ $bookings->payout_id ?? '-' }}</td>

                        <td class="text-center">{{ $bookings->payout_date != NULL ? date('d-m-Y', strtotime($bookings->payout_date)) : '-'  }}</td>

                        </tr>

                        @endforeach

                        <tr><td colspan="11">&nbsp;</td></tr>

                        <tr style="background-color: #ddd;">
                            <td class="noprint">&nbsp;</td>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center total1">0</th>
                            <th class="text-center total2">0</th>
                            <th class="text-center total3">0</th>
                            <th class="text-center total4">0</th>
                            <th class="text-center total5">0</th>
                            <th></th>
                            <th></th>


                        </tr>

                    </tbody>

                </table>

                </form>

                <div class="pagination">

                	<div class="link">

                		<div class="pages">{{ $allbookings->appends(Request::except('page'))->links('pagination::bootstrap-4') }}</div>

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

              window.location.href = '{{ url("statement") }}';  

        });

        var ckCount = 0;
        $("#btn_payout").prop("disabled", true);
        $("#bookingBody").on("click",".payCheck",function(){
            if($(this).is(":checked")) {
                ckCount += 1;
                // $(this).val(1);
            }else{
                ckCount -= 1;
                // $(this).val(0);
            }

        if(ckCount > 0) {
        $("#btn_payout").prop("disabled", false);
        $("#payout_id").prop("disabled",false).attr("required","required").focus();
        $("#payout_date").prop("disabled",false).attr("required","required");
        }else{
        $("#btn_payout").prop("disabled", true);
        $("#payout_id").prop("disabled",true).removeAttr("required");
        $("#payout_date").prop("disabled",true).removeAttr("required");
        }
                

        });





        var total1 = 0;

        $(".amt").each(function(){

        total1 += Number($(this).html()); 

        $(".total1").html(parseFloat(total1).toFixed(2)).css("color","red");

        });



        var total2 = 0;

        $(".comm").each(function(){

        total2 += Number($(this).html()); 

        $(".total2").html(parseFloat(total2).toFixed(2)).css("color","red");

        });



        var total3 = 0;

        $(".pay").each(function(){

        total3 += Number($(this).html()); 

        $(".total3").html(parseFloat(total3).toFixed(2)).css("color","red");

        });



        var total4 = 0;

        $(".vat").each(function(){

        total4 += Number($(this).html()); 

        $(".total4").html(parseFloat(total4).toFixed(2)).css("color","red");

        });

        var total5 = 0;

        $(".netAmt").each(function(){

        total5 += Number($(this).html()); 

        $(".total5").html(parseFloat(total5).toFixed(2)).css("color","red");

        });





        $(".print").on("click",function(){

        // $('#table1').printElement({

        // css:'extend',

        // ecss:'.noprint{display:none;}',

        // // extend:"print",

        // });



        $('#table1').kinziPrint({

                printContainer:true,

                importCSS:true,

                importStyle:true,

                loadCSS:"{{ asset('css/print.css') }}",

                header:"<h3>Booking.com Statement</h3>",

                

        });



        });



        $(".export-csv").on("click",function(){
            $("#table1").table2excel({
                exclude: ".noprint",
                name: "Data",
                filename: "{{ date('d-m-Y').uniqid() }}_"+"statement",
                //columns: [0, 1, 2] // export first three columns
            });
        });

               
         $("#filter_field").on("change",function(){
            if($(this).val() == 'payout_date') {
                $("#search").val('');
                $("#search").attr('placeholder', 'e.g: DD-MM-YYYY');
            }else if($(this).val() == 'receiveable'){
                window.location.href = '{{ url("receivableFilter") }}';
            }else{    
                $("#search").val('');
                $("#search").attr('placeholder', 'Find What?');
            } 
            
        });
        

    });

</script>

@endsection