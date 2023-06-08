@extends('admin.layout.app')
@section('page_title', 'Reports')
@section('content')
@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\MainController;

$owners_br = MainController::ownersBr();
$users = DB::table('users')->where('id', session()->get('user_id'))->get();
@endphp

<style>
    #tbPettyCash tr:last-child td:nth-child(6) {
        color: rgb(231, 27, 27);
        font-weight: 600;
        position: relative;
    }
    .table-md.dataTable-table tr td, .table-md.dataTable-table tr th, .table-sm.dataTable-table tr td, .table-sm.dataTable-table tr th, .table.table-md tr td, .table.table-md tr th, .table.table-sm tr td, .table.table-sm tr th {
        padding: 0.2rem;
    }
</style>

<h3>Reports</h3>
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
                        @endphp
                        <select class="form-select elements" id="brCode" name="brCode" required>
                         <option value="" disabled hidden selected>Select</option>
                         @foreach($branch as $br)
                         <option value="{{ $br->id }}" {{ $selected }}>{{ $br->brName }}</option>
                         @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-12">
                    <label for="fromdate">From Date:<sup style="color: red;">*</sup></label>
                    <input type="text" name="fromdate" id="fromdate" class="form-control elements" autocomplete="off">
                </div>
                <div class="col-md-2 col-12">
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
                            <p><i class="bi bi-telephone"></i> +971 4 1234567</p>
                            <p><i class="bi bi-envelope"></i> abc@test.com</p>
                            <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                    </td>
                    <td valign="top">
                        <div style="text-align: center">
                            @if(session()->get('role_id') != 1 && session()->get('role_id') != 5)
                            <img style="width: 100px;" src="{{ asset('uploads/'.$logo[0][0]->logo) }}" alt="Logo">
                            @else
                            <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">
                            @endif
                        </div> 
                    </td>
                    <td style="text-align:left;padding-left:50px;">
                        <table>
                            <tr>
                                <td><p><strong>Print Date: {{ date('Y-m-d') }}</strong></p></td>
                            </tr>
                            <tr><td class="fromdate"></td></tr>
                            <tr><td class="todate"></td></tr>
                        </table>
                        
                        
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><h2 style="text-align: center;margin-bottom:20px;">Flexible Income Report</h2></td>
                    <td>
                        
                    </td>
                </tr>
            </table>
            
            <div class="table-responsive" id="tbflexincome">
                <table class="table table-md" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Branch Name</th>
                            <th class="text-center">Room No#</th>
                            <th>Customer Name</th>
                            <th class="text-center">Mobile</th>
                            <th>Description</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="flexicomerpt"></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="text-align: right;color:red;">Total</th><th class="Gtotal" style="text-align: center;color:red;">0</th>
                        </tr>
                    </tfoot>
                </table>
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
        
        $("#fromdate").datepicker({
            // showOn: "button",
            // buttonImage: "{{asset('images/calendar.gif')}}",
            // buttonImageOnly: true,
            // buttonText: "Select date",
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear:true,
            onSelect:function(date) {

                var date2 = $("#fromdate").datepicker('getDate');
                $("#todate").datepicker('setDate',date2);
                $("#todate").datepicker('option','minDate', date2);
            }
            //minDate: '<?php echo date("Y-m-d"); ?>',
            // onSelect: function(datetext){
            // var d = new Date(); // for now
            // var h = d.getHours();
        	// 	h = (h < 10) ? ("0" + h) : h ;

        	// 	var m = d.getMinutes();
            // m = (m < 10) ? ("0" + m) : m ;

            // var s = d.getSeconds();
            // s = (s < 10) ? ("0" + s) : s ;

        	// 	datetext = datetext + " " + h + ":" + m + ":" + s;
            //     $('#fromdate').val(datetext);
            // },  
            
        });

        $("#todate").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear:true,
        });

        $("#btn-filter").on("click",function(){
            let sum = 0;    
            let brcode = $("#brCode").val();
            let fromdate = $("#fromdate").val();
            let todate = $("#todate").val();
            if(brcode,fromdate,todate) {
                let filters = [];
                $(".elements").each(function(){
                        if($(this).val() != '') {
                            filters.push($(this).val());
                        }
                });
                $(".loader").show();
                $.ajax({

                        url: '{{ url("flexIncomeprint") }}',
                        type: 'POST',
                        data: {_token: '{{ csrf_token() }}',data:filters},
                        success:function(response) {
                            $(".loader").hide();
                            $("#flexicomerpt").empty();
                            
                            //console.log(response.brname);
                            $(".company").html($("#brCode option:selected").text());
                            $(".fromdate").html('<p><strong>From Date: '+ fromdate +'</strong></p>');
                            $(".todate").html('<p><strong>To Date: '+ todate +'</strong></p>');
                           
                           
                           
                            if(response.status == 'found') {
                                $(".options").show();
                                $.each(response.data, function(key,val){
                                       
                                        // $(".xlsx").data('id', brcode);
                                        // $(".xlsx").data('fdate', fromdate);
                                        // $(".xlsx").data('tdate', todate);

                                        // $(".mail").data('id', brcode);
                                        // $(".mail").data('fdate', fromdate);
                                        // $(".mail").data('tdate', todate);
                                        // $(".mail").data('brname', $("#brCode option:selected").text());

                                        $("#flexicomerpt").append(

                                                        "<tr>"
                                                            +   
                                                                "<td>"+ val.date +"</td>"+
                                                                "<td>"+ val.brName +"</td>"+
                                                                "<td class='text-center'>"+ val.roomNo +"</td>"+
                                                                "<td>"+ val.CustomerName +"</td>"+
                                                                "<td class='text-center'>"+ val.CustomerMobile +"</td>"+
                                                                "<td style='color:gray;'>"+ val.description +"</td>"+
                                                                "<td class='text-center total'><strong>"+ val.amount +"</strong></td>"
                                                            +
                                                        "</tr>"
                                                        
                                        );


                                });

                                $(".total").each(function(){

                                sum += parseFloat($(this).text());

                                });

                                $(".Gtotal").text(sum);


                            }else{  
                                $("#flexicomerpt").empty();
                                $(".options").hide();
                                Toastify({
                                text: response.data,
                                duration: 3000,
                                close: true,
                                gravity: "top", // `top` or `bottom`
                                position: "right", // `left`, `center` or `right`
                                backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",
                                }).showToast();

                                $("#PettyCashrpt").append(

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

        // $(".xlsx").on("click",function(){

        //     let id = $(this).data('id');
        //     let fromdate = $(this).data('fdate');
        //     let todate = $(this).data('tdate');
        //     window.location.href = '{{ url("export_xlsx") }}'+'/'+id+'/'+fromdate+'/'+todate;

        // });

        // $(".mail").on("click",function(){
        //     $(".loader").show();
        //     let id = $(this).data('id');
        //     let fromdate = $(this).data('fdate');
        //     let todate = $(this).data('tdate');
        //     let brname = $(this).data('brname');
        //     window.location.href = '{{ url("cash_mail") }}'+'/'+id+'/'+fromdate+'/'+todate+'/'+brname;

        // });
        
    });

</script>

@endsection
