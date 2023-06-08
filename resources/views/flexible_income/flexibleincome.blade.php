@extends('admin.layout.app')
@section('page_title', 'Flexible Income')
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
<h3>Flexible Income</h3>
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
                           <input type="button" value="Print Report" class="btn btn-outline-success report" id="report" onclick='window.location.href="{{ url("flexincomerpt") }}"'> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">S.No</th>
                                        <th style="text-align: left;">Date</th>
                                        @if(session()->get('role_id') == 1)
                                        <th>Branch Name</th>
                                        @endif
                                        <th style="text-align: left;">Room No</th>
                                        <th style="text-align: left;">Customer Name</th>
                                        <th style="text-align: center;">Mobile</th>
                                        <th style="text-align: center;">Created By</th>
                                        <th style="text-align: center;">Created At</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>  
                                <tbody id="flexible">
                                    @php
                                    $counter = 1;
                                    @endphp
                                    @foreach($data as $list)
                                    @php
                                    $users = DB::table('users')->where('id', $list->created_by)->first();
                                    $branch = DB::table('branchs')->where('id', $list->brCode)->first();
                                    @endphp
                                    <tr>
                                        <td style="text-align: center;">{{ $counter++ }}</td>
                                        <td>{{ date('Y-m-d', strtotime($list->incomeDate)) }}</td>
                                        @if(session()->get('role_id') == 1)
                                        <td>{{ $branch->brName }}</td>
                                        @endif
                                        <td>
                                            {{ $list->roomNo }}
                                            {{-- @if($list->vendorName == 'none')
                                            {{ $list->vendorName }}
                                            @else
                                            <a href="javascript:void(0);" class="inv-details" data-id="{{ $list->id }}" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#invDetails_modal">{{ $list->vendorName }}</a>
                                            @endif --}}
                                        </td>
                                        <td>{{ $list->CustomerName }}</td>
                                        <td style="text-align: center;">{{ $list->CustomerMobile}}</td>
                                        <td style="text-align: center;">{{ ucfirst($users->name) }}</td>
                                        <td style="text-align: center;">{{ date('Y-m-d',strtotime($list->created_at)) }}</td>
                                        <td style="text-align: center;">

                                            <a class="btn btn-outline-primary btn-sm more" href="javascript:void(0);" data-id="{{ $list->id }}" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#invDetails_modal">Details</a>

                                            
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
                <tfoot>
                    <tr>
                        <th style="text-align: right;color:red;">Total</th><th class="Gtotal" style="text-align: center;color:red;">0</th>
                    </tr>
                </tfoot>
            </table>
            </div>
            <div class="modal-footer">
              
            </div>
        </div>
    </div>
</div>

@endsection
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script>
    
    $(document).ready(function(){
               
        $("#flexible").on("click",".more",function(){
            let refid = $(this).data('id');
            let sum = 0;
            $.ajax({

                    url: '{{ url("incomeDetails") }}/'+refid,    
                    type: 'GET',
                    success:function(response) {
                        $("#detailsBody").empty();
                        $.each(response.data, function(key, val){

                               $("#detailsBody").append('<tr>'+
                                                        '<td>'+ val.description +'</td>'+
                                                        '<td style="text-align:center;" class="total">'+ val.amount +'</td>'
                                                        +'</tr>');

                        });

                        $(".total").each(function(){

                                sum += parseFloat($(this).text());

                        });

                        $(".Gtotal").text(sum);
                    }

            });
        });

        $("#branch").on("change",function(){
            let code = $(this).val();
            window.location.href = '{{ url("flexincome") }}'+'/?code='+code;
        });
        
    });
</script>