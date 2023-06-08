@extends('admin.layout.app')

@section('page_title', 'Add Booking')

@section('content')

@php

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

$users = DB::table('users')->where('id', session()->get('user_id'))->get();

$rooms = DB::table('rooms')->where('id', $id)->first();

$roomtype = DB::table('roomtypes')->where('id', $rooms->roomType)->first();



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

<h3>Add Booking</h3>

<section class="section">

    <div class="card">

        <div class="card-header">

           <div class="row booking_opt" style="margin-bottom: 50px;">

               <div class="col-md-1 col-12">

                <input type="radio" class="btn-check" name="options-outlined" id="daily_booking" autocomplete="off" checked="">

                <label class="btn btn-outline-success" for="daily_booking">Daily Booking</label>

               </div>

               <div class="col-md-1 col-12">

                <input type="radio" class="btn-check" name="options-outlined" id="monthly_booking" data-id="" autocomplete="off">

                <label class="btn btn-outline-primary" for="monthly_booking">Monthly Booking</label>    

               </div>

               <div class="col-md-6 col-12"></div>

           </div>

        </div>

        <div class="card-body">

        <form action="{{ url('manage_booking') }}" method="POST" id="fmbooking" enctype="multipart/form-data">

        @csrf    

        <div class="row">

        <input type="hidden" name="roomRef" value="{{ $id }}">

        <div class="col-md-6 col-12">

            <div class="row">

                <div class="col-md-6 col-12">

                    <?php

				        if($users[0]->role == 1) {    

                        //$branch = DB::table('branchs')->get();    

                        ?>

                        <input type="hidden" name="branch" id="branch" value="{{ request('brcode') }}">

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

                        <input type="text" name="roomno" readonly class="form-control" value="{{$rooms->roomNo}}">

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="checkindate">Checkin Date</label>
                        {{-- <input type="text" name="checkindate" id="checkindate" style="width: 90%" class="form-control" readonly value="{{Carbon::now()->toDateString(). " 12:01:00"}}"> --}}
                        <input type="text" name="checkindate" id="checkindate" style="width: 90%" class="form-control" readonly value="{{ date('Y-m-d H:i:s') }}">

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="roomtype">Room Type</label>

                        <input type="hidden" name="capacity" id="capacity" value="{{ $rooms->no_beds }}">

                        <input type="text" name="roomtype" class="form-control" readonly value="{{$roomtype->roomType}}">

                        {{-- <input type="text" name="roomtype" class="form-control" readonly value="{{$roomtype->roomType.' '.$capacity}}"> --}}

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="nights">No.Nights</label>

                        <input type="number" name="nights" id="nights" min="1" data-id="{{ $rooms->roomType  }}" value="{{ old('nights') }}" class="form-control nights" required>

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="nopersons">No.Persons</label>    

                        <?php

                        if($rooms->no_beds == 1){

                        ?>    

                        <input type="number" name="nopersons" min="1" value="1" id="nopersons" class="form-control" readonly>

                        <?php }else{ ?>

                        <input type="number" name="nopersons" min="1" max="3" value="1" id="nopersons" class="form-control">    

                        <?php } ?>    

                        

                    </div>

                </div>  

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="checkoutdate">Checkout Date<span class="day"></span></label>    

                        <input type="text" name="checkoutdate" id="checkoutdate" class="form-control" readonly>
                        <input type="hidden" name="checkoutdate_hidden" id="checkoutdate_hidden" class="form-control" readonly>

                        @error('checkoutdate')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="mobile">Mobile No#</label>    

                        <input type="number" name="mobile" id="mobile" class="form-control" value="{{ old('mobile') }}" required>

                        @error('mobile')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div> 

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="customername">Customer Name</label>    

                        <input type="text" name="customername" id="customername" value="{{ old('customername') }}" class="form-control" required>

                        @error('customername')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div> 

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        {{-- <label for="address">Address</label>    

                        <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control" required>

                        @error('address')

                        <span class="validate">{{ $message }}</span>

                        @enderror --}}

                        <label for="nationality">Nationality</label>

                        <select name="nationality" id="nationality" required class="form-select">

                            <option value="" selected hidden disabled>Select</option>

                        </select>

                        @error('nationality')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

                

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="idno">ID/Passport No#</label>    

                        <input type="text" name="idno" id="idno" class="form-control" value="{{ old('idno') }}" required>

                        @error('idno')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

               

                <div class="col-md-12 col-12">

                    <label for="doc">Upload ID Copy</label>

                    <input type="file" name="doc" id="doc" accept=".pdf,.jpeg,.jpg,.gif,.png" required class="form-control">

                    @error('doc')

                    <span class="validate">{{ $message }}</span>

                    @enderror

                </div>  

                

            </div>

        </div>

       

        <div class="col-md-6 col-12">

            <div class="row">

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="dailytariff">Daily Tariff</label>

                        <input type="text" name="dailytariff" id="dailytariff" value="{{ old('dailytariff') }}" class="form-control" readonly>

                    </div>

                </div>

                

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="monthlytariff">Monthly Tariff</label>

                        <input type="text" name="monthlytariff" id="monthlytariff" value="{{ old('monthlytariff') }}" class="form-control" readonly>

                    </div>

                </div>



                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="weekendtariff">Weekend Tariff</label>

                        <input type="text" name="weekendtariff" id="weekendtariff" value="{{ old('weekendtariff') }}" class="form-control" readonly>

                    </div>

                </div>



                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="weekenddays">Weekend Days</label>

                        <input type="text" name="weekenddays" id="weekenddays" value="{{ old('weekenddays') }}" class="form-control" readonly>

                    </div>

                </div>



                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="var"><span class="vatlabel">Vat </span></label>

                        <input type="text" name="vat" id="vat" class="form-control" value="{{ old('vat') }}" readonly>

                    </div>

                </div>

                <div class="col-md-3 col-12" style="display: none;">

                    <div class="form-group">

                        <label for="discount">Discount %</label>

                        <input type="number" name="discount" id="discount" min="0" value="0" class="form-control">

                    </div>

                </div>

                <div class="col-md-3 col-12" style="display: none">

                    <div class="form-group">

                        <label for="discount_amount">Discount Amount</label>

                        <input type="text" name="discount_amount" id="discount_amount" value="0" class="form-control" readonly>

                    </div>

                </div>

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="total_amount">Total Amount</label>

                        <input type="text" name="total_amount" id="total_amount" class="form-control" value="{{ old('total_amount') }}" readonly>

                    </div>

                </div>

                

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="advance_paid">Advance Paid</label>

                        <input type="number" min="0" value="" name="advance_paid" id="advance_paid" value="{{ old('advance_paid') }}" class="form-control" required>

                    </div>

                </div>

                

                <div class="col-md-3 col-12">

                    <div class="form-group">

                        <label for="payment_mode">Payment Mode</label>

                        <select name="payment_mode" id="payment_mode" class="form-select" required>

                            <option value="cash" selected @if(old('payment_mode') == "cash") {{ 'selected' }} @endif>Cash</option>

                            <option value="unpaid" @if(old('payment_mode') == "unpaid") {{ 'selected' }} @endif>Unpaid</option>

                            <option value="credit card" @if(old('payment_mode') == "credit card") {{ 'selected' }} @endif>Credit Card</option>

                            <option value="cheque" @if(old('payment_mode') == "cheque") {{ 'selected' }} @endif>Cheque</option>

                        </select>

                        @error('payment_mode')

                        <span class="validate">{{ $message }}</span>

                        @enderror

                    </div>

                </div>

                <div class="col-md-6 col-12">

                    <div class="form-group">

                        <label for="balance">Payable Amount</label>

                        <input type="text" name="balance" id="balance" value="{{ old('balance') }}" class="form-control" readonly>

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

                    <input type="submit" value="Check In" name="checkin" id="checkin" class="btn btn-primary float-end">                    

                </div>



            </div>

        </div>        

        

        </div>

        </form>

        </div>    



</div>

</section>

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>

<script>

    $(document).ready(function(){

        

        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $("#nights").focus();

        $(".nights").on("keydown",function(e){

        var code = e.keyCode || e.which;

        if(code === 13 || code === 9) {

        $("#advance_paid").val('');  

        //$("#discount").val('0');

          let night = parseInt($(this).val());

          let start_date= $("#checkindate").val();

          let end_date = new Date(); // pass start date here

          end_date.setDate(end_date.getDate() + night);



        let outdate = end_date.getFullYear()+'-'+((end_date.getMonth() > 8) ? (end_date.getMonth() + 1) : ('0' + (end_date.getMonth() + 1)))+'-'+((end_date.getDate() > 9) ? end_date.getDate() : ('0' + end_date.getDate()))+ ' ' + "12" + ":" + "00" + ":" + "00";   

        let outdate_hidden = end_date.getFullYear()+'-'+((end_date.getMonth() > 8) ? (end_date.getMonth() + 1) : ('0' + (end_date.getMonth() + 1)))+'-'+((end_date.getDate() > 9) ? end_date.getDate() : ('0' + end_date.getDate()));

        //  let outdate = ((end_date.getMonth() > 8) ? (end_date.getMonth() + 1) : ('0' + (end_date.getMonth() + 1))) + '-' + ((end_date.getDate() > 9) ? end_date.getDate() : ('0' + end_date.getDate())) + '-' + end_date.getFullYear()+ ' ' +end_date.getHours() + ":" + end_date.getMinutes() + ":" + end_date.getSeconds();  

                           

         $('#checkoutdate').val(outdate);
         $('#checkoutdate_hidden').val(outdate_hidden);

        //   $('#checkoutdate').val((end_date.getMonth() + 1)+ '-' + end_date.getDate() + '-' + end_date.getFullYear());

        // $('#checkoutdate').val(end_date.getFullYear()+ '-' + (end_date.getMonth() + 1) + '-' + (end_date.getDate()-1)+ ' ' +end_date.getHours() + ":" + end_date.getMinutes() + ":" + end_date.getSeconds());

        let d = new Date(outdate);

        $(".day").html(' ( '+days[d.getDay()]+' ) ');



         let id = $(this).data('id');

         $(".vatlabel").html('');

         $.ajax({

                    url:'{{url("ratecontroll")}}',

                    type:'POST',

                    data: {_token:'{{ @csrf_token() }}', start_date:start_date, end_date:$('#checkoutdate_hidden').val(),id:id,nights:night, capacity:$("#capacity").val()},

                    

                    success:function(response) {



                        //console.log(response);

                        let data = $.parseJSON(response);

                        let monthly = $("#monthly_booking").data('id');



                        if(monthly == 'monthly') {



                        $("#dailytariff").val(data.normal_tariff).css('opacity', '0');

                        $("#weekendtariff").val(data.week_tariff).css('opacity', '0');

                        $("#weekenddays").val(data.weekday).css('opacity', '0');

                        $("#monthlytariff").val(Math.round(data.monthly * 30)).css('opacity', '1');



                        }

                        if(monthly == '') {



                        $("#dailytariff").val(data.normal_tariff).css('opacity', '1');

                        $("#weekendtariff").val(data.week_tariff).css('opacity', '1');

                        $("#weekenddays").val(data.weekday).css('opacity', '1');

                        $("#monthlytariff").val(data.monthly).css('opacity', '0');    



                        }

                       

                        

                        $("#vat").val(data.vat);

                        $("#total_amount").val(Math.round(data.total_tariff));

                        $("#balance").val(Math.round(data.totalpay));

                        // $(".vatlabel").append('Vat ' + data.vat_percent + '%');

                        $(".vatlabel").append('Vat ');

                        

                        

                    }



         });

         

        }



        });



        // $("#discount").on("change",function(){

        //         let discount = parseInt($(this).val());

        //         let totalAmount = parseInt($("#total_amount").val());

        //         let advAmount = parseInt($("#advance_paid").val());

        //         let discountAmount = totalAmount * discount / 100;

        //         let vatAmount = parseInt($("#vat").val());

        //         $("#discount_amount").val(discountAmount);



        //         if($(this).val() == 0 && advAmount == 0) {

        //             $("#balance").val(totalAmount + vatAmount);        

        //         }else{

        //         $("#balance").val(totalAmount + vatAmount - advAmount - discountAmount);

        //         }

        // });

        

        $("#advance_paid").on("change", function(){



            let totalAmount = parseInt($("#total_amount").val());

            let vatAmount = parseInt($("#vat").val());

            let adv = parseInt($(this).val());

            // let discountAmount = parseInt($("#discount_amount").val());

            let payAble = parseInt($("#balance").val());

                        

            if($(this).val() == 0) {



                // $("#balance").val(totalAmount + vatAmount - discountAmount);

                $("#balance").val(totalAmount + vatAmount);

                $("#payment_mode").prepend($('<option>', {

                            value:'unpaid',

                            text:'Unpaid'                        

                }));

            }else{



                // $("#balance").val(totalAmount + vatAmount - discountAmount - adv);

                $("#balance").val(totalAmount + vatAmount - adv);

                $("#payment_mode option[value='unpaid']").remove();

            }

             if($(this).val() > totalAmount) {
                if(!alert('Paid Amount Greater than Total Amount')) {
                    $(this).val('').focus();
                    $("#balance").val('');
                }
            }



        });



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

                $("#advance_paid").val('').prop('readOnly',false);

            }



            if(val == 'unpaid') {

                $(".payment_label").html(val);    

                $("#payment_done").val(val);

                $("#advance_paid").val(0).prop('readOnly',true);

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





        // extention for booking extended

        // $("#checkindate").datepicker({

        //     // showOn: "button",

        //     // buttonImage: "{{asset('images/calendar.gif')}}",

        //     // buttonImageOnly: true,

        //     // buttonText: "Select date",

        //     dateFormat: 'yy-mm-dd',

        //     changeMonth: true,

        //     changeYear:true,

        //     minDate: '<?php echo date("Y-m-d"); ?>',

        //     onSelect: function(datetext){

        //     var d = new Date(); // for now

        //     var h = d.getHours();

        // 		h = (h < 10) ? ("0" + h) : h ;



        // 		var m = d.getMinutes();

        //     m = (m < 10) ? ("0" + m) : m ;



        //     var s = d.getSeconds();

        //     s = (s < 10) ? ("0" + s) : s ;



        // 		datetext = datetext + " " + h + ":" + m + ":" + s;

        //         $('#checkindate').val(datetext);

        //     },  

            

        // });



        $("#daily_booking").on("click",function(){



            $("#nights").val('');

            $("#nights").prop('readOnly', false);

            $("#nights").focus();

            $("#nights").attr({

                "min":1,

            });

            $("#monthly_booking").data('id', '');

        });

        $("#monthly_booking").on("click",function(){



            $("#nights").val("30");

            $("#nights").focus();

            $("#nights").attr({

                "min":30,

                "max":30

            });



            $(this).data('id', 'monthly');

                        

        });



        // customerInfo



        $("#mobile").on("keydown",function(e){

        var code = e.keyCode || e.which;    

        let mobile = $(this).val();

        if(code === 13 || code === 9) {

            

                $.ajax({





                        url: '{{ url("customerInfo") }}',

                        type: 'POST',

                        data: {_token:'{{ csrf_token() }}',mobile:mobile},

                        success:function(response) {

                                // console.log(response);

                                if(response.status == 'zero') {

                                Toastify({

                                text: response.message,

                                duration: 3000,

                                close: true,

                                gravity: "top", // `top` or `bottom`

                                position: "right", // `left`, `center` or `right`

                                backgroundColor: "linear-gradient(to right, #1cab14, #10c61f)",

                                }).showToast();



                                $("#customername").val('');

                                $("#idno").val('');

                                // $('#nationality').val($('#nationality option:first').val());

                                $('#nationality').prop('selectedIndex', 0);

                                }else{

                                         

                                $("#customername").val(response.message.customer_name);

                                $("#idno").val(response.message.id_passport);

                                $('#nationality option[value="'+response.message.nationality+'"]').prop('selected', true); 



                                }



                        }





                });



        }    

        



        });



                

    });

</script>

@endsection

        