@extends('admin.layout.app')
@section('page_title', 'Manage Booking')
@section('content')
@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
$users = DB::table('users')->where('id', session()->get('user_id'))->get();
@endphp
<style>

a.tooltip_type {
        /*border-bottom: 1px dashed;*/
        text-decoration: none;
      }
      a.tooltip_type:hover {
        /*cursor: help;*/
        position: relative;
      }
      a.tooltip_type span {
        display: none;
      }
      a.tooltip_type:hover span {
        border: #666 2px dotted;
        padding: 8px 8px 8px 8px;
        display: block;
        z-index: 100;
        background: black;
        left: 0px;
        /*margin: 15px;*/
        width: 250px;
        position: absolute;
        top: -48px;
        text-decoration: none;
        color: white;
      }
    
/*@media (min-width: 768px) {
.col-md-12 {
    width: 91% !important;
    margin: 0 auto !important;
}

}*/
</style>
<h3>Manage Booking</h3>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="row py-3 px-4">
                @php
                    if(session()->get('role_id') == 1) {
                    $available = DB::table('rooms')->where('roomStatus', 5)->count();
                    $blocked = DB::table('rooms')->where('roomStatus', 3)->count();
                    $reservation = DB::table('rooms')->where('roomStatus', 6)->count();
                    $unclean = DB::table('rooms')->where('roomStatus', 7)->count();
                    $occupied = DB::table('rooms')->where('roomStatus', 8)->count();
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

                <div class="col-md-1 col-12"></div>
                <div class="col-md-2 col-4">
                    <button type="button" class="btn btn-success" id="available">
                        Available <span class="badge bg-transparent">{{$available}}</span>
                    </button>
                </div>
                <div class="col-md-2 col-4">
                    <button type="button" class="btn btn-dark">
                        Blocked <span class="badge bg-transparent">{{$blocked}}</span>
                    </button>
                </div>
                <div class="col-md-2 col-4">
                    <button type="button" class="btn today-checkout" id="checkout">
                        T. Checkout <span class="badge bg-transparent">{{$todayCheckout}}</span>
                        {{-- Reservation <span class="badge bg-transparent">{{$reservation}}</span> --}}
                    </button>
                </div>
                <div class="col-md-2 col-4">
                    <button type="button" class="btn btn-primary">
                        UnClean <span class="badge bg-transparent">{{$unclean}}</span>
                    </button>
                </div>
                <div class="col-md-2 col-4">
                    <button type="button" class="btn btn-danger">
                        Occupied <span class="badge bg-transparent">{{$occupied}}</span>
                    </button>
                </div>
                <div class="col-md-1 col-12"></div>
            </div>   
        </div>
        <div class="card-body mt-5">
             <span style="float: right; display:inline-block; margin-top:-35px;margin-right:15px;">
                        @php    
                        if(session()->get('role_id') == 1) {
                            $roomType = DB::table('roomtypes')->get();
                        }else{
                            $roomType = DB::table('roomtypes')->where('brCode', session()->get('br_code'))->get();
                        }

                        @endphp
                        <label for="">Select Room Type</label>
                        <select name="roomType" id="roomType" class="form-select">
                        <option value="" disabled hidden selected>Select</option>
                        <option value="0">All</option>
                        @foreach($roomType as $type)
                        <option value="{{ $type->id }}">{{ $type->roomType }}</option>
                        @endforeach
                        </select>
                        @php
                        if(isset($_GET['type']) && $_GET['type'] != '') {

                            $selected = $_GET['type'];
                        }else{
                            $selected = 0;
                        }
                        @endphp
                </span>

            @php
            // $floors = DB::select("Select floor from rooms group by floor");
            if(session()->get('role_id') == 1) {
            $branches = DB::table('rooms')->orderBy('brCode', 'asc')->select('brCode')->groupBy(['brCode'])->get();
            }else{
                $branches = DB::table('rooms')->orderBy('brCode', 'asc')->select('brCode')->where('brCode', session()->get('br_code'))->groupBy(['brCode'])->get();    
            }
            @endphp
                @foreach($branches as $br) 
                @php
                $brname = DB::table('branchs')->where('brCode', $br->brCode)->get();
                @endphp
                <div class="treeview w-100 border">
                <h6 class="pt-3 px-3"><i class="bi bi-table"></i> {{ $brname[0]->brName }}</h6>
               
                <hr>
                    @php
                    $floor = DB::table('rooms')->select(['floor','floorRef'])->where('brCode', $br->brCode)->groupBy(['floor','floorRef'])->get();
                    @endphp
                    @foreach($floor as $fl)
                    @php
                    $floorname = DB::table('floors')->where('id', $fl->floorRef)->get();    
                    @endphp
                    <h6 class="pt-0 px-5">{{ $floorname[0]->floor }}</h6>

                    @php
                    if(isset($_GET['type']) && $_GET['type'] != '' && $_GET['type'] != 0) {
                    $roomNo = DB::table('rooms')->where('brCode', $br->brCode)
                                                ->where('floor', $floorname[0]->series)
                                                ->where('roomType', $_GET['type'])
                                                ->get();    

                    }else{
                    $roomNo = DB::table('rooms')->where('brCode', $br->brCode)
                                                ->where('floor', $floorname[0]->series)
                                                ->get();
                    }
                    
                    @endphp
                    <div class="col-md-12 col-12" style="margin-left: 40px;margin-bottom:15px;margin-top:15px;width: 91%;">
                    @foreach($roomNo as $room)
                        @php
                        $toolTip_type_rooms = DB::table('roomtypes')->where('id', $room->roomType)->first();
                        @endphp
                        @if($room->roomStatus == 7)
                        {{-- url('cleantoAvailable/'.$room->id.'/'.$room->brCode) --}}
                        <a href="{{ url('cleantoAvailable/'.$room->id) }}" class="btn btn-primary UnClean mb-2 tooltip_type">{{$room->roomNo}}
                        <span>Room Type: {{ $toolTip_type_rooms->roomType }}</span>    
                        </a>
                        @elseif($room->roomStatus == 6)
                        <a href="{{url('reservation')}}" class="btn btn-primary Reservation mb-2">{{$room->roomNo}}</a>
                        
                        @elseif($room->roomStatus == 8)
                        @php 
                        $partial_amt = '';
                        $overdue = DB::table('booking')->orderBy('id','desc')->where('brCode', $br->brCode)
                                                        ->where('room_no', $room->roomNo)
                                                        ->where('checkout_by', 'occupied')
                                                        ->first();
                        $partial = DB::table('booking')->orderBy('id','desc')->where('brCode', $br->brCode,)
                                                        ->where('room_no', $room->roomNo)
                                                        ->where('checkout_by', 'occupied')    
                                                        ->get();                                                        
                        if($partial->count()) {
                            if($partial[0]->balance > 0) {
                                $partial_amt = 'partialAmt';
                            }else{
                                $partial_amt = '';
                            }
                            
                        } 
                        @endphp
                        {{-- url('occupied/'.$room->id.'/'.$room->brCode) --}}
                        <a href="{{ url('occupied/'.$room->id) }}" class="btn btn-primary Occupied {{ $partial_amt }} mb-2">
                            @if($overdue)
                            @if(date('Y-m-d') >= date('Y-m-d', strtotime($overdue->checkout_date)))
                            <span class="overdue"></span>
                            @endif
                            @endif
                            {{$room->roomNo}}
                            
	                        @php
                                $customer = DB::table('booking')->orderBy('id','desc')->where('brCode', $br->brCode,)
                                                                ->where('room_no', $room->roomNo)
                                                                ->where('checkout_by', 'occupied')    
                                                                ->first();    
                                @endphp
                            @if($customer)
                            <span class="customtooltip">
                                <p>Room Type: {{ $toolTip_type_rooms->roomType }}</p>
                                <p>Booking Ref#: {{ $customer->id }}</p>
                                <p>Customer: {{ $customer->customer_name }}</p>
                                <p>Mobile: {{ $customer->mobile_no }}</p>
                                <p>CheckOut Date: {{ $customer->checkout_date }}</p>
                                <p>Balance Amount: {{ $customer->balance }}</p>
                            </span>
                             @endif
                        </a>
                                                
                        {{-- @elseif($room->roomStatus == 8)
                        @php 
                        $overdue = DB::table('booking')->where('room_no', $room->roomNo)
                                                        ->where('checkout_by', 'occupied')
                                                        ->first();
                        @endphp
                        @if($overdue->checkout_date <= date('Y-m-d'))    
                        <a href="{{url('occupied/'.$room->id.'/'.$room->brCode)}}" class="btn btn-primary Occupied mb-2">{{$room->roomNo}}</a>    
                        @else
                        <a href="{{url('occupied/'.$room->id.'/'.$room->brCode)}}" class="btn btn-primary today-checkout mb-2">{{$room->roomNo}}</a>
                        @endif --}}
                        
                        @elseif($room->roomStatus == 3)
                        <a href="javascript:void(0)" class="btn btn-primary Blocked mb-2 tooltip_type">{{$room->roomNo}}
                        <span>Room Type: {{ $toolTip_type_rooms->roomType }}</span>     
                        </a>
                        
                        @else 
                        @php
                        // $toolTip_type = DB::table('roomtypes')->where('id', $room->roomType)->first();
                        @endphp
                        {{-- url('available/'.$room->id.'/'.$room->brCode) --}}
                        <a href="javascript:;" class="btn btn-primary Available mb-2 tooltip_type">{{$room->roomNo}}
                        <span>Room Type: {{ $toolTip_type_rooms->roomType }}</span>
                        <form action="{{ route('available') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                        </form>
                        </a>
                        @endif
                    @endforeach
                    </div>        
                    @endforeach

                    
                </div>
@endforeach

           
        </div>
    </div>

</section>
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script>
    $(document).ready(function(){
        setTimeout(() => {
                 $(".alert").slideUp();
            }, 3000);

            $("#available").on("click",function(){

                window.location.href = '{{ url("manage_rooms") }}';
            });

            $("#checkout").on("click",function(){

                window.location.href = '{{ url("manage_booking") }}';

            });


            $(".UnClean").on("click", function(e){
                
                return confirm('Is the room clean?');

            });

            $("#roomType").on("change",function(){

                    let value = $(this).val();
                    window.location.href = '{{ url("manage_booking") }}'+'?type='+value;
                    
            });
            $('#roomType option[value="'+ <?php echo $selected; ?> +'"]').prop('selected',true);
            
            // $("#unClean").on("click", function(){

            //     let roomNo = $(this).data('rno');
            //     let brCode = $(this).data('code');
            //     $.get('{{ url("cleantoAvailable/") }}'+roomNo, function(response){

            //         console.log(response);

            //     });


            // });

            $(".Available").on("click",function(){$(this).find('form').submit();});    
});            
 
</script>
@endsection