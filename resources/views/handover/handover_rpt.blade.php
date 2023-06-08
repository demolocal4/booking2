@extends('admin.layout.app')
@section('page_title', 'HandOver/TakeOver')
@section('content')

@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
$users = DB::table('users')->where('id', session()->get('user_id'))->get();
$brInfo = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();
@endphp
<h3>HandOver/TakeOver</h3>
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
                        @php
                            if(session()->get('role_id') == 1) {
                            $branch = DB::table('branchs')->get();
                            $selected = '';
                            }else{
                            $branch = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();
                            $logo[] = DB::table('branchs')->where('brCode', $users[0]->brCode)->get();
                            $selected = 'selected';
                            }
                        @endphp
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-12"></div>
                <div class="col-md-2 col-12">
                    <input type="button" value="Print" class="btn btn-outline-primary print" style="width: 100%">
                </div>
                <div class="col-md-2 col-12">
                    <input type="button" value="LogOut" data-id="{{ session()->get('user_id') }}" class="btn btn-outline-danger logout" style="width: 100%">
                </div>
                <div class="col-md-4 col-12"></div>
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
                            <p><i class="bi bi-telephone"></i> {{ $brInfo[0]->landlordPhone }}</p>
                            <p><i class="bi bi-envelope"></i> {{ $brInfo[0]->landlordEmail }}</p>
                            <p><i class="bi bi-globe"></i> www.sevenoceans.com</p>
                    </td>
                    <td valign="top">
                        <div style="text-align: center">
                            @if(session()->get('role_id') != 1)
                            <img style="width: 100px;" src="{{ asset('uploads/'.$logo[0][0]->logo) }}" alt="Logo">
                            @else
                            <img style="width: 100px;" src="{{ asset('uploads/logo.png') }}" alt="Logo">
                            @endif
                        </div> 
                    </td>
                    <td style="float: right;">
                        <table>
                            <tr>
                            <td><p><strong>Date: {{ date('Y-m-d h:i:s') }}</strong></p></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><h2 style="text-align: center;margin-bottom:20px;">HandOver/TakeOver</h2></td>
                    <td>
                        
                    </td>
                </tr>
            </table>
            
            <div class="table-responsive">
                <table class="table table-md">
                    <thead>
                        <tr>
                            <th>CheckIn Time</th>
                            <th>Customer Name</th>
                            <th class="text-center">Room No</th>
                            <th class="text-center">Booking Ref</th>
                            <th class="text-center">Cash In</th>
                            <th class="text-center">Cash Out</th>
                            <th class="text-center">Balance</th>
                        </tr>
                    </thead>
                    <tbody id="rptBody">
                        @foreach($result as $list)
                        <tr>
                            <td>{{ $list->Time }}</td>
                            <td>{{ $list->customer_name }}</td>
                            <td style="text-align: center">{{ $list->room_no }}</td>
                            <td style="text-align: center">{{ $list->refId }}</td>
                            <td style="text-align: center">{{ $list->creditAmount }}</td>
                            <td style="text-align: center">{{ $list->debitAmount }}</td>
                            <td style="text-align: center">{{ $list->Balance }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:15px;margin-left:0px;">
                    @php
                    $username = DB::table('users')->where('id', session()->get('user_id'))->first();
                    @endphp
                    <span style="display: block;">HandOver By: {{ ucfirst($username->fullname) }}</span>
                    <span style="display:inline-block;margin-top:15px;">Signature______________</span>
                
            </div>
            <div style="margin-top:0px;position:absolute;right:20px">
                <span style="display:block;">TakenOver By_________________</span>
                <span style="display:inline-block;margin-top:15px;">Signature_____________________</span>
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
        
        $(".print").on("click",function(){

                $('#print_area').printElement({

                    css:'extend'

                });

        });

        $(".logout").on("click",function(){
                let id = $(this).data('id');
                if(confirm('You want Closed Shift?')) {
                    
                    window.location.href = '{{ url("shift_closed") }}/'+id;
                    
                }else{
                    return 0;
                }

        });

        
    });

</script>

@endsection
