<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Booking;

use Illuminate\Support\Carbon;

use Carbon\CarbonPeriod;

use Illuminate\Support\Facades\DB;

use App\Models\RoomTypes;

use App\Models\Room;

use App\Models\Setting;

use DateTime;

// use PDF;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\BookingStatement;

use Maatwebsite\Excel\Excel;

use App\Traits\Branches;

class BookingController extends Controller

{

    use Branches;

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */



    

    public function index()

    {

        // $floors = Floor::orderBy('id', 'desc')->get();

        // return view('floors.home',compact('floors'));



        if(session()->has('received_amount')) {

            session()->forget('received_amount');

        }



        return view('booking.home');

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

            

        

    }



    public function ratecontroll(Request $r) {

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $r->start_date)->format('Y-m-d');

        $end = Carbon::createFromFormat('Y-m-d', $r->end_date)->format('Y-m-d');
        if($start != $end) {
        	$end =  Carbon::parse($end)->subDay()->format('Y-m-d');
    	}
        $period = CarbonPeriod::create($start, $end);

        $nights = $r->nights;

        

        

        $roomtype = RoomTypes::orderBy('id')->where('roomtype_id', $r->id)

        ->whereDate('date', '>=', $start)

        ->whereDate('date', '<=', $end)

        // ->whereBetween('date', [$start, $end])

        ->get();

        // working code @ 14-03-2023----------------------------------------------



        $start_date = new DateTime($r->start_date);

        $end_date = new DateTime($r->end_date);



        $week_day = array('Monday' => 0,

        'Tuesday' => 0,

        'Wednesday' => 0,

        'Thursday' => 0,

        'Friday' => 0,

        'Saturday' => 0,

        'Sunday' => 0);

        

        $start = new DateTime($r->start_date);

        $end = new DateTime($r->end_date);

        while($start <= $end )

        {

            $time_stamp = strtotime($start->format('Y-m-d'));

            $week = date('l', $time_stamp);

            $week_day[$week] = $week_day[$week] + 1;

            $start->modify('+1 day');

        }

        

        $weekday = $week_day['Friday'] + $week_day['Saturday'];

        $arr = array();

        if($r->type == 'monthly_booking') {

            $arr[] = $roomtype[0]->monthly;

        }

        if($r->type == 'booking_com') {
            // foreach($roomtype as $rates) {
            //     $arr[] = $rates->regular;
            // }

            foreach($period as $key => $wday) {
                if($wday->format('l') == 'Friday' || $wday->format('l') == 'Saturday') {
                $arr[] = $roomtype[$key]->weekly;
                }else{
                $arr[] = $roomtype[$key]->regular;
                }
            }

        }



        if($r->type == 'daily_booking') {

            if($nights >= 7 && $nights <= 30) {

                foreach($roomtype as $rates) {

                    $arr[] = $rates->regular;

                }

            }else{

                foreach($period as $key => $wday) {

                    if($wday->format('l') == 'Friday' || $wday->format('l') == 'Saturday') {

                    $arr[] = $roomtype[0]->weekly;

                    }else{
                    //$reg = RoomTypes::whereDate('date', $wday->format('Y-m-d'))->get();	
                    //$arr[] = $reg[$key]->regular;
                    $arr[] = $roomtype[$key]->regular;

                    }

                }

            }

            

        }

                        

        $vat_val = Setting::where('setting_name','vat')->first();

        // $arr = array();

        $totalTariff = array_sum($arr);

        if($vat_val->setting_val == 1) {

        $vat = $totalTariff * $roomtype[0]->vat / 100;

        }else{

        $vat = 0;    

        }

        $totalPay = array_sum($arr) + $vat;

        $result = array(

            'normal_tariff'=>$roomtype[0]->regular, 

            'week_tariff'=>$roomtype[0]->weekly, 

            'monthly'=>$roomtype[0]->monthly,

            'total_nights'=>$nights,

            'weekday'=>$weekday,

            'total_tariff'=>$totalTariff,

            'totalpay'=>$totalPay,

            'vat'=>$vat,
            
            'vat_percent'=>$roomtype[0]->vat
            );

           

        echo json_encode($result);

        // working code @ 14-03-2023----------------------------------------------



    }







//     public function ratecontroll(Request $r) {

//         // $roomtype = RoomTypes::find($r->id);

        

//         $roomtype = RoomTypes::orderBy('id','desc')->where('roomtype_id', $r->id)

//         ->whereDate('date', Carbon::now()->toDateString())

//         ->first();

//         if(!$roomtype) {

//             $roomtype = RoomTypes::orderBy('id','desc')->where('roomtype_id', $r->id)->first();    

//         }



        

//         // $start = Carbon::createFromFormat('Y-m-d H:i:s', $r->start_date)->format('Y-m-d');

//         // $end = Carbon::createFromFormat('Y-m-d', $r->end_date)->format('Y-m-d');

//         // $roomRates = RoomTypes::orderBy('id', 'desc')->where('roomtype_id', $r->id)

//         // ->whereDate('date', '>=', $start)

//         // ->whereDate('date', '<=', $end)

//         // ->get();



        



//         // if($r->type == 'booking_com') {

//         // $roomtype = RoomTypes::orderBy('id','desc')

//         // ->whereDate('date', Carbon::now()->toDateString())

//         // ->where('roomtype_id', $r->id)

//         // ->first();    

            

//         // }else{

//         // $roomtype = RoomTypes::orderBy('id','desc')

//         // ->whereDate('date', Carbon::now()->toDateString())

//         // ->where('roomtype_id', $r->id)->first();

//         // }    







//         // normal days tariff

//         // if($r->capacity == 1) { $roomTariff = $roomtype->rSingle; }

//         // if($r->capacity == 2) { $roomTariff = $roomtype->rDouble; }

//         // if($r->capacity == 3) { $roomTariff = $roomtype->rTriple; }

//         // // normal week days tariff

//         // if($r->capacity == 1) { $wTariff = $roomtype->wdSingle; }

//         // if($r->capacity == 2) { $wTariff = $roomtype->wdDouble; }

//         // if($r->capacity == 3) { $wTariff = $roomtype->wdTriple; }

//         // // Monthly tariff

//         // if($r->capacity == 1) { $mTariff = $roomtype->mSingle; }

//         // if($r->capacity == 2) { $mTariff = $roomtype->mDouble; }

//         // if($r->capacity == 3) { $mTariff = $roomtype->mTriple; }



//         //new rate system code at 21-02-2023---------------------------



//         // if($r->capacity == 1) {  }

//         // if($r->capacity == 2) { $roomTariff = $roomtype->regular; }

//         // if($r->capacity == 3) { $roomTariff = $roomtype->regular; }

//         // // normal week days tariff

//         // if($r->capacity == 1) {  }

//         // if($r->capacity == 2) { $wTariff = $roomtype->weekly; }

//         // if($r->capacity == 3) { $wTariff = $roomtype->weekly; }

//         // // Monthly tariff

//         // if($r->capacity == 1) {  }

//         // if($r->capacity == 2) { $mTariff = $roomtype->monthly; }

//         // if($r->capacity == 3) { $mTariff = $roomtype->monthly; }



//         $roomTariff = $roomtype->regular;

//         $wTariff = $roomtype->weekly;

//         $mTariff = $roomtype->monthly;

                

//         $start_date = new DateTime($r->start_date);

//         $end_date = new DateTime($r->end_date);

//         $nights = $r->nights;

//         $days = $start_date->diff($end_date, true)->days;

        

//         // $sunday = intval($days / 7) + ($start_date->format('N') + $days % 7 >=7);

//         // $saturday = intval($days / 6) + ($start_date->format('N') + $days % 6 >=6);

//         $friday = intval($days / 5) + ($start_date->format('N') + $days % 5 >=5);

//         $saturday = intval($days / 6) + ($start_date->format('N') + $days % 6 >=6);



//         //$weekday = $friday + $saturday;

        

//         // new code for get count weekdays between dates



//         $week_day = array('Monday' => 0,

//             'Tuesday' => 0,

//             'Wednesday' => 0,

//             'Thursday' => 0,

//             'Friday' => 0,

//             'Saturday' => 0,

//             'Sunday' => 0);

            

//             $start = new DateTime($r->start_date);

//             $end = new DateTime($r->end_date);

//             while($start <= $end )

//             {

//                 $time_stamp = strtotime($start->format('Y-m-d'));

//                 $week = date('l', $time_stamp);

//                 $week_day[$week] = $week_day[$week] + 1;

//                 $start->modify('+1 day');

//             }

//         $weekday = $week_day['Friday'] + $week_day['Saturday'];

//         // get weekdays from two dates

//         // $finalnights = $interval->format('%a');

//         $arr = array();

//         for($i = $start_date; $i <= $end_date; $i->modify('+1 day')){

//             if($r->type == 'booking_com') {

//                 $arr[] = $roomTariff;

//             }else{



//             if($nights >= 30) {

//                 $arr[] = $mTariff;

//             }elseif($nights >= 7 && $nights <= 30) {

//                 $arr[] = $roomTariff;

//             }else{

//                 if($i->format('l') == 'Friday' || $i->format('l') == 'Saturday') {

//                     $arr[] = $wTariff;   

//                 }else{

//                     $arr[] = $roomTariff;

//                 }    

//             }    



//             }

//         }    



//         $vat_val = Setting::where('setting_name','vat')->first();

//         $totalTariff = array_sum($arr);

//         if($vat_val->setting_val == 1) {

//         $vat = $totalTariff * $roomtype->vat / 100;

//         }else{

//         $vat = 0;    

//         }

//         $totalPay = $totalTariff + $vat;

        

//         $result = array('normal_tariff'=>$roomTariff, 'week_tariff'=>$wTariff, 'total_tariff'=>$totalTariff, 'vat'=>$vat, 'totalpay'=>$totalPay,'total_nights'=>$nights,'weekday'=>$weekday,'monthly'=>$mTariff,'vat_percent'=>$roomtype->vat);

//         echo json_encode($result);



// }









    public function available(Request $request){
        // $id
        $id =   $request->room_id;
        $res = Booking::where('roomRef', $id)->where('checkout_by', 'occupied')->first();
        // query replace above 04-07-2022-----------------------------------
    	// $res = Booking::where('brCode',Request('brcode'))

        //                 ->where('roomRef', $id)

        //                 ->where('checkout_by', 'occupied')

        //                 ->first();
		if(!$res) {	                        
        return view('booking.booking', ['action'=>null, 'name'=>'Available', 'id'=>$id]);
    	}else{
    	return redirect('manage_booking');
    	}
    }


    public function reservation(){
        return view('booking.reservation', ['action'=>'reservation', 'name'=>'Reservation']);
    }



    public function occupied($id){
        return view('booking.occupied', ['action'=>'occupied', 'name'=>'Occupied', 'id'=>$id]);
    }



    public function unclean($id) {
        return view('booking.unclean', ['action'=>'unclean', 'name'=>'Un Cleaned', 'id'=>$id]);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $r)

    {   

        $setting = DB::table('add_charges')->first();

        $status = Booking::where([['brCode', $r->branch], ['roomRef', $r->roomRef], ['checkout_by', 'occupied']])->get();

        if($status->count()) {

            return redirect('manage_booking')->with('fail', 'This room already Occupied,Check another room');

        }else{

        

        $r->validate([

                    

                    'mobile' => 'required|numeric',

                    'customername' => 'required',

                    'nationality' => 'required',

                    'idno' => 'required',

                    'payment_mode' => 'required',

                    'doc' => 'required|mimes:jpeg,png,jpg,gif,pdf',

                    'cheque' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',

                    'checkoutdate' => 'required',



        ]);



        

        if($r->hasFile('doc')) {

            $docCopy = request()->file('doc');

            $ext = $docCopy->getClientOriginalExtension();

            $path = 'public/uploads/';

            $docName = date('m-d-Y').'-'.uniqid().'.' . $ext;

            $docCopy->move($path, $docName);

        }    



        if($r->hasFile('cheque')) {

            $checkCopy = request()->file('cheque');

            $extension = $checkCopy->getClientOriginalExtension();

            $destination = 'public/uploads/';

            $filename = date('m-d-Y').'-'.uniqid().'.' . $extension;

            $checkCopy->move($destination, $filename);

            // $checkCopy = $r->file('cheque');

            // $r->file('cheque')->store('public/uploads/');

            // $copy = $checkCopy->getClientOriginalName();

        }else{

            $filename = 'Other mode';

        }

        if($docCopy) {

            

            $data = new Booking;

            $data->roomRef = $r->roomRef;

            $data->brCode = $r->branch;

            $data->room_no = $r->roomno;

            $data->checkin_date = $r->checkindate;

            $data->room_type = $r->roomtype;

            $data->capacity = $r->capacity;

            $data->no_nights = $r->nights;

            $data->checkout_date = $r->checkoutdate;

            $data->mobile_no = $r->mobile;

            $data->customer_name = $r->customername;

            $data->nationality = $r->nationality;

            $data->id_passport = $r->idno;

            $data->no_persons = $r->nopersons;

            $data->room_tariff = $r->dailytariff;

            $data->monthly_tariff = $r->monthlytariff;

            $data->vat = $r->vat;

            $data->total_amount = $r->total_amount;

            $data->advance_paid = $r->advance_paid;

            $data->balance = $r->balance;

            $data->totalPayAmount = $r->total_amount + $r->vat;

            $data->payment_mode = $r->payment_mode;

            $data->payment_done = $r->payment_done;

            $data->card_code = $r->cardcode;

            $data->card_expiry = $r->cardexpiry;

            $data->cheque_photo = $filename;

            $data->doc_photo = $docName;

            $data->created_by = session()->get('user_id');

            $data->updated_by = session()->get('user_id');

            $data->created_at = Carbon::now()->toDateTimeString();

            $data->updated_at = Carbon::now()->toDateTimeString();

            $data->checkout_by = 'occupied';

            $data->date_checkout = 'occupied';

            $data->reputation = 1;

            $data->booking_com_ref = $r->booking_com_ref ?? 0;

           

            $data->commission       = $r->total_amount * $setting->commission / 100;

            $data->payment_charge   = $r->total_amount * $setting->payment / 100;

            $data->vat_online       = $r->total_amount * $setting->vat_online / 100;



            if($data->save()) {

                $r->session()->put('received_amount', $r->advance_paid);

                DB::table('rooms')->where('id', $r->roomRef)

                                  ->update(array(

                                         'roomStatus' => 8,

                                         'updated_by' => session()->get('user_id'),

                                         'updated_date' => Carbon::now()->toDateTimeString(),

                                        ));

                

                // DB::table('rooms')->where('brCode', $r->branch)

                //                             ->where('roomNo', $r->roomno)

                //                             ->update(array(

                //                                 'roomStatus' => 8,

                //                                 'updated_by' => session()->get('user_id'),

                //                                 'updated_date' => Carbon::now()->toDateTimeString(),

                //                             ));





                // $crNo = DB::table('payment_receipt')->orderBy('id', 'desc')->where('refId', $data->id)->get();



                // $crNo = DB::table('payment_receipt')->orderBy('id', 'desc')->first();

                // $numberStart = $crNo->cr_no + 1;



                // if($crNo->cr_no == '') {

                //     $numberStart = 101;

                // }else{

                //     $numberStart = $crNo->cr_no + 1;

                // }   



                $crNo = DB::table('rec_no')->orderBy('id', 'desc')->where('brCode',session()->get('br_code'))->first();

                if($crNo) {

                    $numberStart = $crNo->cr_no + 1;

                }else{

                    $numberStart = 100;

                }

                DB::table('rec_no')->insert(['brCode'=>session()->get('br_code'), 'cr_no'=>$numberStart]);                           



                DB::table('payment_receipt')->insert([

                          'refId'           => $data->id,

                          'brCode'          => $r->branch,

                          'description'     => 'Balance Amount',

                          'cr_no'           => session()->get('br_code1').'-'.$numberStart,

                          'creditAmount'    => $r->balance,

                          'debitAmount'     => 0,

                          'payment_mode'    => $r->payment_mode,

                          'payment_done'    => $r->payment_done,

                          'card_code'       => $r->cardcode,

                          'card_expiry'     => $r->cardexpiry,

                          'cheque_photo'    => $filename,

                          'created_by'      => session()->get('user_id'),

                          'updated_by'      => session()->get('user_id'),

                          'created_at'      => Carbon::now()->toDateTimeString(),

                          'updated_at'      => Carbon::now()->toDateTimeString(),

                          'booking_com_ref' => $r->booking_com_ref ?? 0,



                ]);

                // tomarrow will be start if advance pay then else return to manage booking 

                if($r->advance_paid != 0) {

                    DB::table('payment_receipt')->insert([

                        'refId'           => $data->id,

                        'brCode'          => $r->branch,

                        'description'     => 'Partial Amount',

                        'cr_no'           => session()->get('br_code1').'-'.$numberStart,

                        'creditAmount'    => $r->advance_paid,

                        'debitAmount'     => 0,

                        'payment_mode'    => $r->payment_mode,

                        'payment_done'    => $r->payment_done,

                        'card_code'       => $r->cardcode,

                        'card_expiry'     => $r->cardexpiry,

                        'cheque_photo'    => $filename,

                        'created_by'      => session()->get('user_id'),

                        'updated_by'      => session()->get('user_id'),

                        'created_at'      => Carbon::now()->toDateTimeString(),

                        'updated_at'      => Carbon::now()->toDateTimeString(),

                        'booking_com_ref' => $r->booking_com_ref ?? 0,   



              ]);    



                $receipt = Booking::find($data->id);

                return view('print.print_receipt',compact('receipt'));



                }else{

                    $receipt = Booking::find($data->id);

                    return view('print.print_receipt',compact('receipt'));

                    // return redirect('manage_booking')->with('success', 'Booking Successfull');    

                }



                



           }else{

                return redirect('manage_booking')->with('fail', 'Something went wrong');

           }



        }    



        

        }//room status condition else close

                

    } //class close here



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $re, $id)

    {



        $re->validate([

           

            'cheque' => 'nullable|mimes:jpeg,png,jpg,gif,pdf',

           

            ]);

        

        if($re->hasFile('cheque')) {

            $checkCopy = request()->file('cheque');

            $extension = $checkCopy->getClientOriginalExtension();

            $destination = 'public/uploads/';

            $filename = date('m-d-Y').'-'.uniqid().'.' . $extension;

            $checkCopy->move($destination, $filename);

            // $checkCopy = $r->file('cheque');

            // $r->file('cheque')->store('public/uploads/');

            // $copy = $checkCopy->getClientOriginalName();

        }else{

            $filename = 'Other mode';

        }



        $data = Booking::find($id);

        $data->balance = $re->balanceamt;

        $data->updated_by = session()->get('user_id');

        $data->updated_at = Carbon::now()->toDateTimeString();

        

        if($data->save()) {

           $re->session()->put('received_amount', $re->advance_paid);

            

            // $crNo = DB::table('payment_receipt')->orderBy('id', 'desc')->where('refId', $id)->get();

            

            // $crNo = DB::table('payment_receipt')->orderBy('id', 'desc')->first();

            // $numberAdd = $crNo->cr_no + 1;



            $crNo = DB::table('rec_no')->orderBy('id', 'desc')->where('brCode',session()->get('br_code'))->first();

            $numberAdd = $crNo->cr_no + 1;

            DB::table('rec_no')->insert(['brCode'=>session()->get('br_code'), 'cr_no'=>$numberAdd]);

            

            DB::table('payment_receipt')->insert([



                'refId'         => $id,

                'brCode'        => $data->brCode,

                'description'   => 'Partial Amount',

                'cr_no'         => session()->get('br_code1').'-'.$numberAdd,

                'creditAmount'  => $re->advance_paid,

                'debitAmount'   => 0,

                'payment_mode'  => $re->payment_mode,

                'payment_done'  => $re->payment_done,

                'card_code'     => $re->cardcode,

                'card_expiry'   => $re->cardexpiry,

                'cheque_photo'  => $filename,

                'created_by'    => session()->get('user_id'),

                'updated_by'    => session()->get('user_id'),

                'created_at'    => Carbon::now()->toDateTimeString(),

                'updated_at'    => Carbon::now()->toDateTimeString(),

                'booking_com_ref' => $re->booking_com_ref ?? 0,



        ]);

            if($re->balanceamt != 0) {

            $receipt = Booking::find($id);

            return view('print.print_receipt',compact('receipt'));

            }else{

            return back()->with('success', 'Successfully updated');

            }



        }else{

             return back()->with('fail', 'Something went wrong');

        }   



    }



    public function checkout(Request $request, $id) {



        if($request->has('reputation')) {



        $data = Booking::find($id);                

        $data->reputation = $request->reputation;

        if($data->save()){

        return back()->with('success', 'Reputation has been added');

        }else{

        return back()->with('fail', 'Something went wrong!');    

        }    



        }else{



        $data = Booking::find($id);

        $data->updated_by = session()->get('user_id');

        $data->updated_at = Carbon::now()->toDateTimeString();

        $data->checkout_by = 'Checkout'; //session()->get('user_id')

        $data->date_checkout = Carbon::now()->toDateTimeString();



        if($data->save()) {



        DB::table('rooms')->where('id', $data->roomRef)

                                ->update(array(

                                    'roomStatus' => 7,

                                    'updated_by' => session()->get('user_id'),

                                    'updated_date' => Carbon::now()->toDateTimeString(),

        ));



           return view('invoices.invoice_checkout',compact('data')); 



           }else{

                return redirect('manage_booking')->with('fail', 'Something went wrong');

           }                         



        }



            // below query replace with above on 05-07-2022----------------------------------    

            

            

            // DB::table('rooms')->where('brCode', $data->brCode)

            //                   ->where('roomNo', $data->room_no)

            //                   ->update(array(

            //                         'roomStatus' => 7,

            //                         'updated_by' => session()->get('user_id'),

            //                         'updated_date' => Carbon::now()->toDateTimeString(),

            // ));

                

        //    return redirect('manage_booking')->with('success', 'Successfully Check Out');

           

        

    }



    public function receipt_pdf($id) {



        // $receipt = Booking::find($id);

        // $pdf = PDF::loadView('print.print_receipt',compact('receipt'));

        // $pdf->setOptions(['defaultFont' => 'sans-serif']);

        // return $pdf->download(date('d-m-Y').time().".pdf");

       

        //return view('pdf.receipt_pdf');

        

        $receipt = Booking::find($id);

        $pdf = PDF::loadView('pdf.receipt_pdf',compact('receipt'));

        $pdf->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download(date('d-m-Y').time().'.pdf');

        

        

    }





    public function payment_receipt_pdf($id) {



        // $receipt = Booking::find($id);

        // $pdf = PDF::loadView('print.print_receipt',compact('receipt'));

        // $pdf->setOptions(['defaultFont' => 'sans-serif']);

        // return $pdf->download(date('d-m-Y').time().".pdf");

       

        //return view('pdf.receipt_pdf');

        

        $receipt = Booking::find($id);

        $pdf = PDF::loadView('pdf.payment_receipt_pdf',compact('receipt'));

        $pdf->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download(date('d-m-Y').time().'.pdf');

        

        

    }



    

    public function invoice_pdf($id) {



        // $receipt = Booking::find($id);

        // $pdf = PDF::loadView('print.print_receipt',compact('receipt'));

        // $pdf->setOptions(['defaultFont' => 'sans-serif']);

        // return $pdf->download(date('d-m-Y').time().".pdf");

       

        //return view('pdf.receipt_pdf');

        

        $invoice = Booking::find($id);

        $pdf = PDF::loadView('pdf.invoice_checkout',compact('invoice'));

        $pdf->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download(date('d-m-Y').time().'.pdf');

        

        

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        //

    }



    public function allbookings() {



        if(session()->has('received_amount')) {

            session()->forget('received_amount');

        }

        

        if(session()->get('role_id') == 1) {

        $allbookings = Booking::orderBy('id', 'desc')->where('booking_com_ref', 0)->paginate(15);

        }else if(session()->get('role_id') == 5) {

        $allbookings = Booking::orderBy('id', 'desc')->where('booking_com_ref', 0)->whereIn('brCode', $this->get_branches())->paginate(15); 

        }else{

        $allbookings = Booking::orderBy('id', 'desc')->where('booking_com_ref', 0)->where('brCode', session()->get('br_code'))->paginate(15);

        }

        //$refund_amt = DB::table('payment_receipt')->where([['refId', $allbookings[0]->id], ['description', '!=', 'Balance Amount']])->sum('creditAmount');

        return view('booking.allbookings', compact('allbookings'));



    }

    

    public function booking_com() {



        if(session()->has('received_amount')) {

            session()->forget('received_amount');

        }

        

        if(session()->get('role_id') == 1) {

        $allbookings = Booking::orderBy('id', 'desc')->where('booking_com_ref', '!=', 0)->paginate(15);

        }else if(session()->get('role_id') == 5) {

        $allbookings = Booking::orderBy('id', 'desc')->where('booking_com_ref', '!=' , 0)->whereIn('brCode', $this->get_branches())->paginate(15); 

        }else{

        $allbookings = Booking::orderBy('id', 'desc')->where('booking_com_ref', '!=' , 0)->where('brCode', session()->get('br_code'))->paginate(15);

        }

        //$refund_amt = DB::table('payment_receipt')->where([['refId', $allbookings[0]->id], ['description', '!=', 'Balance Amount']])->sum('creditAmount');

        return view('booking.booking_com', compact('allbookings'));



    }









    public function filter_booking(Request $request) {

        // $allbookings = Booking::where('customer_name','like','%'.$request->search.'%')

        //                       ->orWhere('mobile_no', $request->search)

        //                       ->orWhere('id', $request->search)

        //                       ->paginate(15);  

        

        // return view('booking.allbookings', compact('allbookings'))->with('name', $request->search);  	

        if(session()->get('role_id') == 1) {

            $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')
            ->where('booking_com_ref', 0)
            ->paginate(15);

        }else if(session()->get('role_id') == 5) {    

        $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')

                                    ->whereIn('brCode', $this->get_branches())

                                    ->where('booking_com_ref', 0)

                                    ->paginate(15); 

        }else{

            $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')

            						->where('booking_com_ref', 0)

                                    ->where('brCode', session()->get('br_code'))

                                    ->paginate(15);

        }

        $data=[ 'name' => $request->search, 'field_name' => $request->filter_field ];

        // $allbookings = Booking::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->paginate(15);

        

        return view('booking.allbookings', compact(['allbookings', 'data']));  



    }



    public function filter_booking_com(Request $request) {

        

        if(session()->get('role_id') == 1) {

            $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')

                                    ->where('booking_com_ref', '!=', 0)

                                    ->paginate(15);

        }else if(session()->get('role_id') == 5) {    

        $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')

                                    ->whereIn('brCode', $this->get_branches())

                                    ->where('booking_com_ref', '!=', 0)

                                    ->paginate(15); 

        }else{

            $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')

                                    ->where('brCode', session()->get('br_code'))

                                    ->where('booking_com_ref', '!=', 0)

                                    ->paginate(15);

        }

        $data=[ 'name' => $request->search, 'field_name' => $request->filter_field ];

        // $allbookings = Booking::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->paginate(15);

        return view('booking.booking_com', compact(['allbookings', 'data']));  



    }



    public function booking_cancel(Request $r) {



        $res = DB::table('booking_cancel')->insert([

               'refId'              => $r->refId,

               'remarks'            => $r->remarks,

               'created_by'         => session()->get('user_id'),

               'updated_by'         => session()->get('user_id'),

               'created_at'         => Carbon::now()->toDateTimeString(),

               'updated_at'         => Carbon::now()->toDateTimeString(),

               'booking_com_ref'    => $r->booking_com_ref ?? 0,



        ]);



        if($res) {

         //    $prNo = DB::table('payment_receipt')->orderBy('id', 'desc')->first();

         //    $numberStart = $prNo->cr_no + 1;



            $prNo = DB::table('rec_no')->orderBy('id', 'desc')->where('brCode',session()->get('br_code'))->first();

            $numberStart = $prNo->cr_no + 1;

            DB::table('rec_no')->insert(['brCode'=>session()->get('br_code'), 'cr_no'=>$numberStart]);

            // $prNo = DB::table('payment_receipt')->orderBy('id', 'desc')->first();

            // if($prNo->pr_no == 0) {

            //     $numberStart = 101;

            // }else{

            //     $numberStart = $prNo->pr_no + 1;

            // }   



            // if($r->filled('booking_com_ref')) {

            // DB::table('payment_receipt')->where('booking_com_ref', $r->booking_com_ref)->update(['creditAmount' => $r->perday_tariff]);

            // }



            DB::table('payment_receipt')->insert([

                    'refId'             =>  $r->refId,

                    'brCode'            =>  $r->brcode,

                    'description'       =>  'Booking cancelled',

                    'cr_no'             =>  session()->get('br_code1').'-'.$numberStart,

                    'creditAmount'      =>  0,

                    'debitAmount'       =>  $r->refundedAmt,

                    'payment_mode'      =>  'cash',

                    'payment_done'      =>  'cash',

                    'card_code'         =>  null,

                    'card_expiry'       =>  null,

                    'cheque_photo'      =>  null,

                    'created_by'        => session()->get('user_id'),

                    'updated_by'        => session()->get('user_id'),

                    'created_at'        => Carbon::now()->toDateTimeString(),

                    'updated_at'        => Carbon::now()->toDateTimeString(),

                    'booking_com_ref'   => $r->booking_com_ref ?? 0,

            ]);



            DB::table('booking')->where('id', $r->refId)->update([



                'updated_by'    => session()->get('user_id'),

                'updated_at'    => Carbon::now()->toDateTimeString(),    

                'checkout_by'   => 'Cancelled',

                'date_checkout'    => Carbon::now()->toDateTimeString(),    

            ]);



            $roomStatus = DB::table('booking')->where('id', $r->refId)->get();

            DB::table('rooms')->where('id', $roomStatus[0]->roomRef)

                              ->update(array(

                                    'roomStatus' => 7,

                                    'updated_by' => session()->get('user_id'),

                                    'updated_date' => Carbon::now()->toDateTimeString(),

            ));



			//below query replace to above at 05-07-2022------------------------------------

            

            // DB::table('rooms')->where('brCode', $roomStatus[0]->brCode)

            //                   ->where('roomNo', $roomStatus[0]->room_no)

            //                   ->update(array(

            //                         'roomStatus' => 7,

            //                         'updated_by' => session()->get('user_id'),

            //                         'updated_date' => Carbon::now()->toDateTimeString(),

            // ));



            // return back()->with('success', 'Booking Cancelled');

            $receipt = Booking::find($r->refId);

            return view('print.payment_receipt',compact('receipt'));



        }else{



            return back()->with('fail', 'Something went wrong');

        }



    }



    public function cleantoAvailable($id) {

        // $room_no,$br_code



        $data = DB::table('rooms')->where('id', $id)

                                  ->update(array(

                                    'roomStatus' => 5,

                                    'updated_by' => session()->get('user_id'),

                                    'updated_date' => Carbon::now()->toDateTimeString(),

                                ));

        //below query replace with up on 04-07-2022  -------------------------------------



        // $data = DB::table('rooms')->where('brCode', $br_code)

        //                       ->where('id', $room_no)

        //                       ->update(array(

        //                             'roomStatus' => 5,

        //                             'updated_by' => session()->get('user_id'),

        //                             'updated_date' => Carbon::now()->toDateTimeString(),

        //                         ));



        if($data) {

            return back()->with('success', 'Room Now Available');

        }else{

            return back()->with('fail', 'Something went wrong');

        }                        

    }



    public function customerInfo(Request $r) {



            $customerInfo = DB::table('booking')->orderBy('id', 'desc')->where('mobile_no',$r->mobile)->first();

            if($customerInfo) {

                return response(['status'=>'success', 'message'=>$customerInfo]);

            }else{

                return response(['status'=>'zero', 'message'=>'New Customer']);

            }

    }



    public function partial_amount() {

            

        return view('partial_amount.partial_amount');



    }



    public function booking_details() {



        $data  = Booking::find(Request('id'));

        $users = DB::table('users')->where('id',$data->created_by)->first();

        $branch = DB::table('branchs')->where('brCode',$data->brCode)->first();

        return response(['status'=>'success','data'=>$data,'users'=>$users,'branch'=>$branch]);

    }



    public function reprint_cash_receipt($id){



        // $receipt = Booking::find($id);

        // return view('print.print_receipt',compact('receipt'));

        

        $receipts = DB::table('payment_receipt')->where([['refId', $id], ['description', '!=', 'Balance Amount']])->get();

        return response(['status'=>true, 'data'=>$receipts]);

        



    }



    public function reprint_receipt(){

        

        $receipt = Booking::find(Request('id'));

        $crno = Request('cr_no');

        $cancelled = DB::table('payment_receipt')->where([['brCode', $receipt->brCode], ['cr_no', $crno], ['description', '!=', 'Balance Amount']])->first();

                

        if($cancelled->description == 'Partial Amount') {

            return view('print.print_receipt',compact(['receipt','crno']));

        }

        if($cancelled->description == 'Booking cancelled') {

            return view('print.payment_receipt',compact(['receipt','crno']));

        }

       

    }



    public function invoice_reprint($id) {



        $data = Booking::find($id);

        return view('invoices.invoice_checkout',compact('data')); 

        

    }





    //code added on 06-07-2022

    public function recent_bookings() {



        if(session()->has('received_amount')) {

            session()->forget('received_amount');

        }

        

        $month = Carbon::now();



        if(session()->get('role_id') == 1) {

        $allbookings = Booking::orderBy('id', 'desc')->where('created_at', '>', $month->subDays(31)->endOfDay())->paginate(50);

        }else{

        $allbookings = Booking::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))

                                                     ->where('created_at', '>', $month->subDays(31)->endOfDay())

                                                     ->paginate(50);

        }

        

        //$refund_amt = DB::table('payment_receipt')->where([['refId', $allbookings[0]->id], ['description', '!=', 'Balance Amount']])->sum('creditAmount');



        return view('booking.allbookings', compact('allbookings'));



        // return $month->startOfMonth()->subMonth(3)->format('m');

        

        // return $allbookings->count();



    }





    public function roomTransfer(Request $request) {

    $booking = Booking::where('id',$request->booking_id)

                             ->select('roomRef', 'room_no')

                             ->first();

    $new_room = Room::where('id', $request->new_room)->select('id','roomNo')->first();



    $roomUpdate = Room::where('id',$booking->roomRef)->update([

                'roomStatus'    => 7,

                'remarks'       => 'Room Transfer to '. $new_room->roomNo,

                'updated_by'    => session()->get('user_id'),

                'updated_date'  => Carbon::now()->toDateTimeString()

                ]);

    $bookingUpdate = Booking::where('id', $request->booking_id)->update([

                'roomRef'       =>  $request->new_room,

                'room_no'       =>  $new_room->roomNo,

                'updated_by'    =>  session()->get('user_id'),

                'updated_at'    =>  Carbon::now()->toDateTimeString()

    ]);

    $roomUpdate = Room::where('id', $new_room->id)->update([

                    'roomStatus'    => 8,

                    'remarks'       => 'Room Transfer from '. $booking->room_no,

                    'updated_by'    => session()->get('user_id'),

                    'updated_date'  => Carbon::now()->toDateTimeString()

    ]);



    if($roomUpdate && $bookingUpdate &&  $roomUpdate) {

    return back()->with('success', 'Room Transfer Successfully Room No# '.$new_room->roomNo);

    }else{

    return back()->with('fail', 'Something went wrong!');

    }



    }


    public function statement() {
        if(session()->get('role_id') == 1) {
        $allbookings = Booking::with('branch')->orderBy('id', 'desc')->where('booking_com_ref', '!=', 0)->paginate(15);
        }else if(session()->get('role_id') == 5) {
        $allbookings = Booking::with('branch')->orderBy('id', 'desc')->where('booking_com_ref', '!=', 0)->whereIn('brCode', $this->get_branches())->paginate(15); 
        }else{
        $allbookings = Booking::with('branch')->orderBy('id', 'desc')->where('booking_com_ref', '!=', 0)->where('brCode', session()->get('br_code'))->paginate(15);
        }
        return view('booking.statement',compact('allbookings'));
    }

    

    public function payout(Request $request) {
    foreach($request->payCheck as $i => $val) {
        $res = Booking::where('id', $val)->update([
            'payout_id'     =>  $request->payout_id,
            'payout_date'   =>  $request->payout_date,
        ]);

    }
    if($res) {
        return back()->with('success', 'Payout Id Successfully updated');
    }else{
        return back()->with('fail', 'Something went Wrong');
    }

    }



    public function receivable() {

        if(session()->get('role_id') == 1) {

        $allbookings = Booking::with('branch')->orderBy('id', 'desc')

        ->where('booking_com_ref', '!=', 0)->whereNull('payout_id')->paginate(15);

        }else if(session()->get('role_id') == 5) {

        $allbookings = Booking::with('branch')->orderBy('id', 'desc')

        ->where('booking_com_ref', '!=', 0)->whereNull('payout_id')->whereIn('brCode', $this->get_branches())->paginate(15); 

        }else{

        $allbookings = Booking::with('branch')->orderBy('id', 'desc')

        ->where('booking_com_ref','!=', 0)->whereNull('payout_id')->where('brCode', session()->get('br_code'))->paginate(15);

        }

        

        return view('booking.receivable',compact('allbookings'));



    }

    public function receivableFilter() {
        if(session()->get('role_id') == 1) {
            $allbookings = Booking::where('booking_com_ref','!=', 0)
                                    ->whereNull('payout_id')
                                    ->paginate(15);
            
            }else if(session()->get('role_id') == 5) {    
            $allbookings = Booking::whereIn('brCode', $this->get_branches())
                                        ->where('booking_com_ref','!=', 0)
                                        ->whereNull('payout_id')    
                                        ->paginate(15); 
            }else{
            $allbookings = Booking::where('brCode', session()->get('br_code'))
                                    ->where('booking_com_ref','!=', 0)
                                    ->whereNull('payout_id')
                                    ->paginate(15);
            }
            $data=['field_name' => 'receiveable'];
            // $allbookings = Booking::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->paginate(15);
            return view('booking.statement', compact('allbookings','data'));  
    }

    public function filter_statement(Request $request) {
        if($request->filter_field == 'payout_date') {
            $q = Carbon::createFromFormat('d-m-Y', $request->search)->format('Y-m-d');
        }else{
            $q = $request->search;
        }
        if(session()->get('role_id') == 1) {
            $allbookings = Booking::where($request->filter_field,'like','%'.$q.'%')
                                    ->where('booking_com_ref','!=', 0)
                                    ->paginate(15);
        }else if(session()->get('role_id') == 5) {    
        $allbookings = Booking::where($request->filter_field,'like','%'.$q.'%')
                                    ->whereIn('brCode', $this->get_branches())
                                    ->where('booking_com_ref', '!=', 0)
                                    ->paginate(15); 
        }else{
            $allbookings = Booking::where($request->filter_field,'like','%'.$q.'%')
                                    ->where('brCode', session()->get('br_code'))
                                    ->where('booking_com_ref', '!=', 0)
                                    ->paginate(15);
        }
        $data=[ 'name' => $request->search, 'field_name' => $request->filter_field ];
        // $allbookings = Booking::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->paginate(15);
        return view('booking.statement', compact(['allbookings', 'data']));  

    }


    public function filter_receivable(Request $request) {
        if(session()->get('role_id') == 1) {
            $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')
                                    ->where('booking_com_ref','!=', 0)
                                    ->paginate(15);
        }else if(session()->get('role_id') == 5) {    
        $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')
                                    ->whereIn('brCode', $this->get_branches())
                                    ->where('booking_com_ref','!=', 0)
                                    ->paginate(15); 
        }else{
            $allbookings = Booking::where($request->filter_field,'like','%'.$request->search.'%')
                                    ->where('brCode', session()->get('br_code'))
                                    ->where('booking_com_ref','!=', 0)
                                    ->paginate(15);
        }
        $data=[ 'name' => $request->search, 'field_name' => $request->filter_field ];
        // $allbookings = Booking::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->paginate(15);
        return view('booking.receivable', compact(['allbookings', 'data']));  

    }



    public function statement_csv(Excel $excel){

        // return Excel::download(new BookingStatement,'statement.xlsx');

        //return $excel->download(new BookingStatement, 'statement.xlsx');

        //return new BookingStatement;

        //return $excel->download(new BookingStatement, 'statement.pdf',Excel::DOMPDF);

        //return $excel->download(new BookingStatement, 'statement.csv',Excel::CSV);

        //return $excel->download(new BookingStatement, 'statement.xlsx');

        // return Booking::whereNotNull('booking_com_ref')

        // >select('id','booking_com_ref','total_amount','commission','payment_charge','vat_online','payout_id')

        // ->get();

        return $excel->download(new BookingStatement, now()->format('d-m-Y').uniqid().'.xlsx', Excel::XLSX);

               

    }



}