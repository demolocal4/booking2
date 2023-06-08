@extends('admin.layout.app')

@section('page_title', 'Booking.com Reports')

@section('content')



@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;
use App\Http\Controllers\MainController;

$owners_br = MainController::ownersBr();
$users = DB::table('users')->where('id', session()->get('user_id'))->get();
$brInfo = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();

@endphp

<h3>Booking.com Report</h3>

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
                }else if(session()->get('role_id') == 5){                                                        
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

            <div class="col-md-12 col-12">

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

            <div class="row mt-3">

                <div class="col-md-2 col-12"></div>

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="brCode">Branch<sup style="color: red;">*</sup></label>

                        @php

                            if(session()->get('role_id') == 1) {

                            $branch = DB::table('branchs')->get();

                            $selected = '';

                            }else if(session()->get('role_id') == 5) {

                            $branch = DB::table('branchs')->whereIn('brCode', $owners_br)->get();

                            $selected = '';    

                            }else{

                            $branch = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();

                            $logo[] = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();

                            $selected = 'selected';

                            }

                            if(isset($_GET['brCode'])) {
                                $branchInfo = DB::table('branchs')->where('brCode', request('brCode'))->first();
                            }

                        @endphp

                        <form action="{{ url('bookingComReports/daily_reports') }}" method="GET" id="fmBranch">
                        <select class="form-select elements" id="brCode" name="brCode" required onchange="document.getElementById('fmBranch').submit();">

                         <option value="" disabled hidden selected>Select</option>

                         @foreach($branch as $br)

                         <!-- <option value="{{ $br->id }}" {{ $selected }}>{{ $br->brName }}</option> -->
                         <option value="{{ $br->id }}" {{ request('brCode') == $br->id ? 'selected' : '' }}>{{ $br->brName }}</option>

                         @endforeach

                        </select>
                        </form>
                    </div>

                </div>

                

                <div class="col-md-2 col-12">

                    <label for="fromdate">Select Date:<sup style="color: red;">*</sup></label>

                    <input type="text" name="fromdate" id="fromdate" class="form-control elements" autocomplete="off">

                </div>

                 <div class="col-md-2 col-12 d-none">

                    <label for="todate">To Date:<sup style="color: red;">*</sup></label>

                    <input type="text" name="todate" id="todate" class="form-control elements" autocomplete="off">

                </div>

                <div class="col-md-1 col-12" style="margin-top:23px;">

                    <input type="button" value="Submit" id="btn-filter" class="btn btn-primary">

                </div>

                <div class="col-md-2 col-12" style="margin-top:23px;">

                    <div class="dropdown options" style="display: none;">

                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                          Options

                        </button>

                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                          <li><a class="dropdown-item print" href="javascript:void(0);"><i class="bi bi-printer"></i> Print</a></li>

                          <li><a class="dropdown-item xlsx" href="javascript:void(0);" data-id="" data-fdate="" date-tdate=""><i class="bi bi-file-earmark-spreadsheet"></i> Export Excel</a></li>

                          <li><a class="dropdown-item mail" href="javascript:void(0);" data-id="" data-fdate="" date-tdate="" data-brname=""><i class="bi bi-envelope"></i> Send Mail</a></li>

                        </ul>

                      </div>

                </div>

                

              

            </div>

                               

        </div>

        <hr>

        <div class="card-body" id="print_area">

            @php

            //$branchName = DB::table('branchs')->where('id', $receipt->brCode)->first();

            @endphp

            

            <table style="width: 100%;table-layout:fixed;">

                <tr>

                    <td>

                            {{-- <h4>{{ $branchName->brName }}</h4>  --}}

                            <h4 class="company"></h4> 

                            <p><i class="bi bi-telephone"></i> {{ $branchInfo->landlordPhone ?? $brInfo[0]->landlordPhone }}</p>

                            <p><i class="bi bi-envelope"></i> {{ $branchInfo->landlordEmail ?? $brInfo[0]->landlordEmail }}</p>

                            <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>

                    </td>

                    <td valign="top">

                        <div style="text-align: center">

                           <!--  @if(session()->get('role_id') != 1 && session()->get('role_id') != 5)

                            <img style="width: 100px;" src="{{ asset('uploads/'.$logo[0][0]->logo) }}" alt="Logo">

                            @else

                            <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">

                            @endif -->

                            @if(isset($_GET['brCode'])) 
                            <img style="width: 100px;" src="{{ asset('uploads/'.$branchInfo->logo) }}" alt="Logo">
                            @else
                            <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">
                            @endif

                        </div> 

                    </td>

                    <td style="text-align:left;padding-left:50px;">

                        <table>

                            <tr>

                            <td><p><strong>Date: {{ date('Y-m-d h:i:s') }}</strong></p></td>

                            </tr>

                            <tr><td class="fromdate"></td></tr>

                        </table>

                        

                        

                    </td>

                </tr>

                <tr>
                    <td colspan="3"><h2 style="text-align:center;margin-bottom:20px;">Daily Cash Report (Booking.com)</h2></td>
                </tr>

            </table>

            

            <div class="table-responsive">

                <table class="table table-md">

                    <thead>

                        <tr>

                            <th>Customer Name</th>

                            <th class="text-center">Room No</th>

                            <th class="text-center">Booking Ref</th>

                            <th class="text-center">Cash In</th>

                            <th class="text-center">Cash Out</th>

                            <th class="text-center">Balance</th>

                        </tr>

                    </thead>

                    <tbody id="rptBody"></tbody>

                </table>

            </div>

            <div style="margin-top:15px;margin-left:0px;">

                    @php

                    $username = DB::table('users')->where('id', session()->get('user_id'))->first();
                    // $username = DB::table('users')->where('role', session()->get('role_id'))->first();

                    @endphp

                    <span style="display: block;">Authorized By: Vladimir Norchin {{-- {{ ucfirst($username->fullname) }} --}}</span>

                    <span style="display:inline-block;margin-top:15px;">Signature______________</span>

                

            </div>

            <div style="margin-top:0px;position:absolute;right:20px">

                <span style="display:block;">Name_________________</span>

                <span style="display:inline-block;margin-top:15px;">Signature______________</span>

            </div>    

            <div style="padding: 30px 0;">

                &nbsp;

            </div>

        </div>

        

    </div>



</section>

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    $(document).ready(function(){
             setTimeout(() => {
                 $(".alert").slideUp();
            }, 3000);

        $("#fromdate,#todate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear:true,
        });

        $("#btn-filter").on("click",function(){
            let brcode = $("#brCode").val();
            let fromdate = $("#fromdate").val();
            let todate = $("#todate").val();
            if(brcode,fromdate) {
                let filters = [];
                $(".elements").each(function(){
                        if($(this).val() != '') {
                            filters.push($(this).val());

                        }

                });
                $(".loader").show();
                $.ajax({
                        url: '{{ url("bookingComReports/daily_report_ledger") }}',
                        type: 'POST',
                        data: {_token: '{{ csrf_token() }}',data:filters},
                        success:function(response) {
                            $(".loader").hide();
                            $("#rptBody").empty();
                            // console.log(response);
                            //console.log(response.brname);
                            $(".company").html($("#brCode option:selected").text());
                            $(".fromdate").html('<p><strong>Report Date: '+ fromdate +'</strong></p>');
                            if(response.status == 'found') {
                                $(".options").show();
                                $.each(response.data, function(key,val){
                                        $(".xlsx").data('id', brcode);
                                        $(".xlsx").data('fdate', fromdate);
                                        $(".xlsx").data('tdate', todate);
                                        $(".mail").data('id', brcode);
                                        $(".mail").data('fdate', fromdate);
                                        $(".mail").data('tdate', todate);
                                        $(".mail").data('brname', $("#brCode option:selected").text());

                                        $("#rptBody").append(
                                                        "<tr>"
                                                            +   
                                                                "<td>"+ val.customer_name +"</td>"+
                                                                "<td class=text-center>"+ val.room_no +"</td>"+
                                                                "<td class=text-center>"+ val.refId +"</td>"+
                                                                "<td class=text-center>"+ val.creditAmount +"</td>"+
                                                                "<td class=text-center>"+ val.debitAmount +"</td>"+
                                                                "<td class=text-center>"+ val.Balance +"</td>"
                                                            +
                                                        "</tr>"
                                                        

                                        );

                                });

                            }else{  
                                $("#rptBody").empty();
                                $(".options").hide();
                                Toastify({
                                    text: response.data,
                                    duration: 3000,
                                    close: true,
                                    gravity: "top", // `top` or `bottom`
                                    position: "right", // `left`, `center` or `right`
                                    backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",
                                }).showToast();
                                $("#rptBody").append(
                                                        "<tr>"
                                                            +   
                                                                "<td class=text-center colspan=5>"+ response.data +"</td>"+

                                                            +
                                                        "</tr>"

                                );

                            }

                        }

                });
            }else{
                Toastify({
                text: "All fields mandatory",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",
                }).showToast();
            }
        });
        $(".print").on("click",function(){
            $('#print_area').printElement({
                css:'extend'

            });
        });

        $(".xlsx").on("click",function(){
            let id = $(this).data('id');
            let fromdate = $(this).data('fdate');
            let todate = $(this).data('tdate');
            window.location.href = '{{ url("bookingComReports/daily_report_export") }}'+'/'+id+'/'+fromdate;

        });



        $(".mail").on("click",function(){

            $(".loader").show();

            let id = $(this).data('id');

            let fromdate = $(this).data('fdate');

            let brname = $(this).data('brname');

            window.location.href = '{{ url("bookingComReports/daily_report_mail") }}'+'/'+id+'/'+fromdate+'/'+brname;



        });

        

    });



</script>



@endsection

