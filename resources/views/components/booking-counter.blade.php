<div>
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
            <button type="button" class="btn btn-success w-100">
                Available <span class="badge bg-transparent">{{$available}}</span>
            </button>
        </div>
        <div class="col-md-2 col-12">
            <button type="button" class="btn btn-dark w-100">
                Blocked <span class="badge bg-transparent">{{$blocked}}</span>
            </button>
        </div>
        <div class="col-md-2 col-12">
            <button type="button" class="btn today-checkout w-100">
                T. Checkout <span class="badge bg-transparent">{{$todayCheckout}}</span>
                {{-- Reservation <span class="badge bg-transparent">{{$reservation}}</span> --}}
            </button>
        </div>
        <div class="col-md-2 col-12">
            <button type="button" class="btn btn-primary w-100">
                UnClean <span class="badge bg-transparent">{{$unclean}}</span>
            </button>
        </div>
        <div class="col-md-2 col-12">
            <button type="button" class="btn btn-danger w-100">
                Occupied <span class="badge bg-transparent">{{$occupied}}</span>
            </button>
        </div>
        <div class="col-md-1 col-12"></div>
    </div>
</div>