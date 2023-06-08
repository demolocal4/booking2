@extends('admin.layout.app')

@section('page_title', 'Update Booking')

@section('content')

@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

$users = DB::table('users')->where('id', session()->get('user_id'))->get();

$rooms = DB::table('rooms')->where('id', $id)->first();

$roomtype = DB::table('roomtypes')->where('id', $rooms->roomType)->first();

$booking = DB::table('booking')->where('roomRef', $id)->where('checkout_by', 'occupied')->first();



if($rooms->no_beds == 1) {

    $capacity = 'Single';

}

if($rooms->no_beds == 2) {

    $capacity = 'Double';

}

if($rooms->no_beds == 3) {

    $capacity = 'Triple';

}



@endphp

<h3>Booking Details</h3>

<section class="section">

    <div class="card">

        <div class="card-header">
        	<input type="button" value="Flexible Income" class="btn btn-primary" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#flexible_modal">

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

            <div class="col-md-12 col-12" style="margin-top: 0px;">

            {{-- @if($booking->balance != 0 )    

            <input type="button" value="Check Out" id="checkout" class="btn btn-danger float-end" disabled>    

            @else --}}

            {{-- <a href="{{ url('checkout/'.$booking->id) }}" class="btn btn-danger float-end" id="btn_checkout" style="display: none;">Check Out</a> --}}

            {{-- <input type="button" value="Check Out" id="checkout" data-id="{{ $booking->id }}" class="btn btn-danger float-end">         --}}

            {{-- @endif --}}

            </div>    

        </div>

        <div class="card-body">

        <form method="POST" action="{{ url('manage_booking/'.$booking->id) }}" id="fmbooking" enctype="multipart/form-data">

        @csrf    

        @method('PUT')

        <div class="row">

        

        <div class="col-md-6 col-12">

            <div class="row">

                <div class="col-md-6 col-12">

                    <?php

				        if($users[0]->role == 1) {    

                        // $branch = DB::table('branchs')->get();    

                        ?>

                        <input type="hidden" name="branch" value="{{ request('brcode') }}">

                        {{-- <div class="col-md-12 col-12">

                            <div class="form-group">

                                <label for="branch">Branch</label>

                                <select name="branch" id="branch" class="form-select elements" required>

                                    <option value="" hidden disabled selected>Select</option>

                                    @foreach($branch as $br)

                                    <option value="{{ $br->brCode }}">{{ $br->brName }}</option>    

                                    @endforeach

                                            

                                </select>

                            </div>

                        </div> --}}

                        <?php }else{ ?>

                        <input type="hidden" name="branch" value="{{ session()->get('br_code') }}">

                        <?php } ?>    

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="roomno">Room No</label>

                        <input type="text" name="roomno" readonly class="form-control" value="{{ $booking->room_no }}">

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="checkindate">Checkin Date</label>

                        <input type="text" name="checkindate" id="checkindate" class="form-control" readonly value="{{$booking->checkin_date}}">

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="roomtype">Room Type</label>

                        <input type="text" name="roomtype" class="form-control" readonly value="{{$booking->room_type}}">

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="nights">No.Nights</label>

                        <input type="text" name="nights" id="nights" readonly value="{{$booking->no_nights}}" class="form-control nights" required>

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="nopersons">No.Persons</label>    

                        <?php

                        if($rooms->no_beds == 1){

                        ?>    

                        <input type="number" name="nopersons" min="1" value="{{ $booking->no_persons }}" id="nopersons" class="form-control" disabled>

                        <?php }else{ ?>

                        <input type="number" name="nopersons" min="1" max="3" value="{{ $booking->no_persons }}" id="nopersons" class="form-control" disabled>    

                        <?php } ?>    

                        

                    </div>

                </div>  

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="checkoutdate">Checkout Date<span class="day"></span></label>    

                        <input type="text" name="checkoutdate" id="checkoutdate" value="{{ $booking->checkout_date }}" class="form-control" readonly>

                        @error('checkoutdate')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="mobile">Mobile No#</label>    

                        <input type="number" name="mobile" id="mobile" class="form-control" value="{{ $booking->mobile_no }}" readonly>

                        @error('mobile')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div> 

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="customername">Customer Name</label>    

                        <input type="text" name="customername" id="customername" value="{{ $booking->customer_name }}" class="form-control" readonly>

                        @error('customername')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div> 

                <div class="col-md-12 col-12">

                    <div class="form-group">



                        <label for="nationality">Nationality</label>

                        <select name="nationality" id="nationality" required class="form-select" disabled>

                            <option value="{{ $booking->nationality }}" selected>{{ ucfirst($booking->nationality) }}</option>

                        </select>

                        @error('nationality')

                        <span class="validate">{{ $message }}</span>

                        @enderror



                        {{-- <label for="address">Address</label>    

                        <input type="text" name="address" id="address" value="{{ $booking->address }}" class="form-control" readonly>

                        @error('address')

                        <span class="validate">{{ $message }}</span>

                        @enderror --}}

                    </div>

                </div>

                

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="idno">ID/Passport No#</label>    

                        <input type="text" name="idno" id="idno" class="form-control" value="{{ $booking->id_passport }}" readonly>

                        @error('idno')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

               

            </div>

        </div>

       

        <div class="col-md-6 col-12">

            <div class="row">

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="dailytariff">Daily Tariff</label>

                        <input type="text" name="dailytariff" id="dailytariff" value="{{ $booking->room_tariff }}" class="form-control" readonly>

                    </div>

                </div>



                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="monthlytariff">Monthly Tariff</label>

                        <input type="text" name="monthlytariff" id="monthlytariff" value="{{ $booking->monthly_tariff }}" class="form-control" readonly>

                    </div>

                </div>

               

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="var"><span class="vatlabel">Booking Fee </span></label>

                        <input type="text" name="vat" id="vat" class="form-control" value="{{ $booking->vat }}" readonly>

                    </div>

                </div>



                {{-- <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="discount">Discount %</label>

                        <input type="number" name="discount" id="discount" min="0" value="{{ $booking->discount }}" readonly class="form-control">

                    </div>

                </div>

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="discount_amount">Discount Amount</label>

                        <input type="text" name="discount_amount" id="discount_amount" value="{{ $booking->discount_amount }}" class="form-control" readonly>

                    </div>

                </div> --}}

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="total_amount">Total Amount</label>

                        <input type="text" name="total_amount" id="total_amount" class="form-control" value="{{ $booking->total_amount }}" readonly>

                    </div>

                </div>

                

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="advance_paid">Paid Amount <sup style="color:rgb(224, 10, 10);">({{ $booking->advance_paid }})</sup></label>

                        <input type="number" min="0" name="advance_paid" id="advance_paid" value="0" class="form-control">

                    </div>

                </div>

                

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="payment_mode">Payment Mode</label>

                        <select name="payment_mode" id="payment_mode" class="form-select" required>

                            {{-- <option value="" selected hidden disabled>Select</option> --}}

                            <option value="cash" selected @if(old('payment_mode') == "cash") {{ 'selected' }} @endif>Cash</option>

                            <option value="credit card" @if(old('payment_mode') == "credit card") {{ 'selected' }} @endif>Credit Card</option>

                            <option value="cheque" @if(old('payment_mode') == "cheque") {{ 'selected' }} @endif>Cheque</option>

                        </select>

                        @error('payment_mode')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="balanceamt">Balance Amount</label>

                        <input type="text" name="balanceamt" id="balanceamt" value="{{ $booking->balance }}" class="form-control" readonly>

                    </div>

                </div>

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="balance">Payable Amount</label>

                        <input type="text" name="balance" id="balance" value="{{ $booking->totalPayAmount }}" class="form-control" readonly>

                    </div>

                </div>

                <div class="col-md-4 col-12">

                    <div class="form-group">

                        <label for="payment_done" class="payment_label"></label>

                        <input type="text" name="payment_done" id="payment_done" value="" class="form-control" readonly>

                    </div>

                </div>

                <div class="col-md-4 col-12">

                    <div class="form-group card_code" style="display: none;">

                        <label for="cardcode">Card Code#</label>

                        <input type="number" name="cardcode" id="cardcode" class="form-control">

                    </div>

                </div>

                <div class="col-md-4 col-12">

                    <div class="form-group card_expiry" style="display: none;">

                        <label for="cardexpiry">Expiry Date</label>

                        <input type="date" name="cardexpiry" id="cardexpiry" class="form-control">

                    </div>

                </div>

                <div class="col-md-12 col-12 cheque" style="display: none;">

                    <label for="cheque">Upload Cheque Copy</label>

                    <input type="file" name="cheque" id="cheque" accept=".pdf,.jpeg,.jpg,.gif,.png" class="form-control">

                </div> 

               

                <div class="col-md-12 col-12" style="margin-top: 60px;">

                    @error('cheque')

                    <span class="validate">{{ $message }}</span>

                    @enderror

                    {{-- @if($booking->balance > 0 ) --}}

                    <input type="submit" value="Update" name="update" id="update" class="btn btn-dark float-end">  

                    <a href="{{ url('checkout/'.$booking->id) }}" class="btn btn-danger float-end" id="btn_checkout" style="display: none;">Check Out</a>                  

                    {{-- @else

                    <input type="submit" value="Update" name="update" id="update" class="btn btn-dark float-end" disabled>                    

                    @endif --}}

                </div>

                

            </div>

        </div>        

        </div>

        </form>

           

        </div>    



</div>

</section>

{{-- PettyCash Out Modal form --}}
<div class="modal fade text-left" id="flexible_modal" tabindex="-1" aria-labelledby="myModalLabel4" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel4">Flexibale Income
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ url('store_income') }}" method="POST">
                @csrf
                
                <div class="row" style="justify-content: center;">
                    <div class="col-md-5 col-12">
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
                </div>
                
                <input type="hidden" name="refId" value="{{ $booking->id }}">
                <input type="hidden" name="brCode" value="{{ $booking->brCode }}">
                <input type="hidden" name="roomRef" value="{{ $booking->roomRef }}">
                <input type="hidden" name="roomNo" value="{{ $booking->room_no }}">
                <input type="hidden" name="CustomerName" value="{{ $booking->customer_name }}">
                <input type="hidden" name="CustomerMobile" value="{{ $booking->mobile_no }}">

                <div class="row mt-5">
                    <div class="col-md-12 col-12">
                        <h3 class="text-center">Income Details</h3>
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

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    $(document).ready(function(){

        setTimeout(() => {

                 $(".alert").slideUp();

            }, 3000);





        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $("#nights").focus();

        $(".nights").on("change",function(){

          $("#advance_paid").val('0');  

          $("#discount").val('0');

          let night = parseInt($(this).val());

          let start_date= $("#checkindate").val();

          let end_date = new Date(start_date); // pass start date here

                         end_date.setDate(end_date.getDate() + night);

        //   $('#checkoutdate').val((end_date.getMonth() + 1)+ '-' + end_date.getDate() + '-' + end_date.getFullYear());

        $('#checkoutdate').val(end_date.getFullYear()+ '-' + (end_date.getMonth() + 1) + '-' + (end_date.getDate() - 1)+ ' ' +end_date.getHours() + ":" + end_date.getMinutes() + ":" + end_date.getSeconds());

        let d = new Date($('#checkoutdate').val());

        $(".day").html(' ( '+days[d.getDay()]+' ) ');



         let id = $(this).data('id');

         $(".vatlabel").html('');

         $.ajax({

                    url:'{{url("ratecontroll")}}',

                    type:'POST',

                    data: {_token:'{{ @csrf_token() }}', start_date:start_date, end_date:$('#checkoutdate').val(),id:id,nights:night, capacity:$("#capacity").val()},

                    

                    success:function(response) {



                        //console.log(response);

                        let data = $.parseJSON(response);

                        $("#dailytariff").val(data.normal_tariff);

                        $("#weekendtariff").val(data.week_tariff);

                        $("#weekenddays").val(data.weekday);

                        $("#vat").val(data.vat);

                        $("#total_amount").val(data.total_tariff);

                        $("#balance").val(data.totalpay);

                        $(".vatlabel").append('Vat ' + data.vat_percent + '%');

                    }



         });

         



        });



        $("#discount").on("change",function(){

                let discount = parseInt($(this).val());

                let totalAmount = parseInt($("#total_amount").val());

                let advAmount = parseInt($("#advance_paid").val());

                let discountAmount = totalAmount * discount / 100;

                let vatAmount = parseInt($("#vat").val());

                $("#discount_amount").val(discountAmount);



                if($(this).val() == 0 && advAmount == 0) {

                    $("#balance").val(totalAmount + vatAmount);        

                }else{

                $("#balance").val(totalAmount + vatAmount - advAmount - discountAmount);

                }

        });

        



        var paidAmt = parseInt($("#balanceamt").val());
        $("#advance_paid").on("change", function(){

              if($(this).val() > paidAmt) {
                if(!alert('Paid Amount Greater than Total Amount')) {
                    $(this).val('').focus();
                    $("#balanceamt").val(paidAmt);
                    $("#advance_paid").attr('required', 'required');
                }
            
              }else{  
              
              let paid = parseInt($(this).val());
              $("#balanceamt").val(paidAmt - paid);
              
              }

        });



        if($("#balanceamt").val() != 0) {  

                $("#btn_checkout").css("display", "none");

                $("#update").css("display", "block");      

              }else{



                $("#btn_checkout").css("display", "block");

                $("#update").css("display", "none");    

                $("#advance_paid").prop('readOnly', true);

                $("#payment_mode").prop('disabled', true);

                $("#nationality").prop('disabled', true);

        }

       

        $(".payment_label").html('Cash');    

        $("#payment_done").val('cash');



        $("#payment_mode").on("change", function(){



            let val = $(this).val();



            if(val == 'cash') {

                $(".payment_label").html(val);    

                $("#payment_done").val(val);

                $("#payment_done").prop('readOnly', true);

                $(".card_code").css('display', 'none');

                $(".card_code").removeAttr('required');

                $(".card_expiry").css('display', 'none');

                $(".card_expiry").removeAttr('required');

                $(".cheque").css("display", "none");

                $(".cheque").removeAttr('requierd');

            }

            if(val == 'credit card') {

                $(".payment_label").html(val+' No#');    

                $("#payment_done").prop('readOnly', false);

                $("#payment_done").val('').focus();

                $("#payment_done").attr('required', 'required');

                $(".card_code").css('display', 'block');

                $(".card_code").attr('required','required');

                $(".card_expiry").css('display', 'block');

                $(".card_expiry").attr('required','required');

                $(".cheque").css("display", "none");

                $(".cheque").removeAttr('requierd');

            }



            if(val == 'cheque') {

                $(".payment_label").html(val+' No#');    

                $("#payment_done").prop('readOnly', false);

                $("#payment_done").val('').focus();

                $("#payment_done").attr('required', 'required');

                $(".card_expiry").css('display', 'block');

                $(".card_expiry").attr('required','required');

                $(".card_code").css('display', 'none');

                $(".card_code").removeAttr('required');

                $(".cheque").css("display", "block");

                $(".cheque").attr('requierd', 'required');

            }





        });



        $.getJSON('{{ env("APP_URL") }}country-list.json', function(data){

                $.each(data, function(key, val){

                        $("#nationality").append($('<option>', {

                            value:val.name.toLowerCase(),

                            text:val.name,

                        }));

                });

        });


        $("#datepicker").datetimepicker();
        $(".cal").on("click",function(){
            $("#datepicker").datetimepicker({
                format:'Y-m-d H:i:s',
                timepicker:false,
                // minDate:'<?php echo date("Y-m-d"); ?>',
            }).datetimepicker('show');

        });

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
        

    });

</script>

@endsection

        