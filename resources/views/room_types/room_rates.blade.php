@extends('admin.layout.app')
@section('page_title', 'Room Rates Calendar')
@section('content')
@php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
$users = DB::table('users')->where('id', session()->get('user_id'))->get();
@endphp
<style>
 .slick-next {
    right: -10px;
    /* background-color: rgb(37, 59, 158) !important; */
    /* z-index:10; */
}
.slick-prev {
    left: 0px;
    /* background-color: rgb(37, 59, 158) !important; */
    /* z-index: 9999999; */
}

.slick-prev, .slick-next {
    background: rgb(14, 89, 175);
    z-index: 999999999;
}
.slick-prev:hover, .slick-prev:focus, .slick-next:hover, .slick-next:focus {
    background: rgb(14, 89, 175);
}

.slick-slide{
    margin-left:  15px;
    margin-right:  15px;
    padding-block: 15px;
    
  }

  .slick-list {
    margin-left: -15px;
    margin-right: -15px;
    pointer-events: none;
  }
  .box-inner {
    background-color: rgb(255, 255, 255);
    /* padding: 25px; */
    padding-block: 15px;
    width: 240px;
    height: 200px;
    text-align: center;
    box-shadow: 1px 1px 5px rgba(0,0,0, 0.5);
  }
  .box-inner p {
    line-height: 0.5;
  }
</style>
<h3>Room Rates Calendar</h3>
<section class="section">
    <div class="card">
       <x-booking-counter />
        <div class="card-header">
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
            @if(session()->get('role_id') == 1)
            <form action="{{ url('room_rates') }}" id="fmFilter" method="GET">
               <div class="row mb-5">
                    <div class="col-md-4 offset-1">
                        <div class="form-grop">
                        <select name="branches" id="branches" class="form-select" required>
                            <option value="" disabled selected>Select Branch</option>
                            @foreach($branches as $brc)
                            <option value="{{ $brc->brCode }}"
                            @if(Request('branches'))
                            {{ $brc->brCode == Request('branches') ? 'selected' : '' }}    
                            @endif

                            >{{ $brc->brName }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                   {{-- <div class="col-md-2">
                       <button type="submit" class="btn btn-primary" style="width: 85%;">Search</button> 
                   </div> --}}
                   <div class="col-md-2">
                       <a href="{{ url('room_rates') }}" class="btn btn-outline-secondary">Clear Search</a>
                   </div>
               </div>
               </form>
               @endif
                <section class="regular slider">
                @forelse ($data as $card )
                    <div>
                    <div class="box-inner">
                    <h5>{{ date('d', strtotime($card->date)). ' ' .date('F', strtotime($card->date)) }}</h5>
                    <p class="card-text">{{ date('l', strtotime($card->date)) }}</p>
                    <p class="card-text">{{ $card->roomType }}</p>
                    <p class="card-text">{{ $card->branch->brName }}</p>
                    <table style="width:90%;margin:-8px auto;">
                      <tr>
                          <th>Regular:</th>
                          <td>{{ 'AED '.$card->regular }}</td>
                      </tr>
                      <tr>
                          <th>Weekly:</th>
                          <td>{{ 'AED '.$card->weekly }}</td>
                      </tr>
                      <tr>
                          <th>Monthly:</th>
                          <td>{{ 'AED '.$card->monthly }}</td>
                      </tr>
                    </table>
                    </div>  
                  </div>    
                @empty
                <div class="row">
                <div class="col-12">
                <img src="{{ asset('uploads/calendar-thumbnail.png') }}">    
                </div>    
                </div>                    
                @endforelse    
                </section>

            {{-- <div class="row justify-content-center">
                @foreach ($data as $card)
                <div class="col-md-3 col-12 slider rate_slider" style="margin-bottom:1rem;margin-right:1rem;box-shadow:1px 1px 5px rgba(0,0,0, 0.5);">
                    <div class="card">
                        <div class="card-body" style="text-align:center;">
                          <h5 class="card-title">{{ date('d', strtotime($card->date)). ' ' .date('F', strtotime($card->date)) }}</h5>
                          <p class="card-text">{{ date('l', strtotime($card->date)) }}</p>
                          <table style="width:100%;">
                            <tr>
                                <th>Regular:</th>
                                <td>{{ 'AED '.$card->regular }}</td>
                            </tr>
                            <tr>
                                <th>Weekly:</th>
                                <td>{{ 'AED '.$card->weekly }}</td>
                            </tr>
                            <tr>
                                <th>Monthly:</th>
                                <td>{{ 'AED '.$card->monthly }}</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                </div>
                @endforeach
            </div> --}}
        </div>
    </div>
    
</section>
@endsection



