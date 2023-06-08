<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;

use App\Models\PaymentReceipt;

use App\Exports\CashReport;

use App\Exports\DailyReport;

use App\Exports\CollectionReport;

use Excel;

use DateTime;

use PDF;

use Mail;



class Reports extends Controller

{

    

    public function index() {



            return view('reports.reports');

    }



    public function ledger(Request $r) {

    	// AND booking_com_ref = 0
    	// where (`t1`.`booking_com_ref` = 0) for daily report view

        $result = DB::select("SELECT id, brCode, refId, STR_TO_DATE(created_at,'%Y-%m-%d') as date, description, creditAmount, debitAmount,

        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance

        FROM payment_receipt

        JOIN (SELECT @Balance :=0) as tmp

        WHERE brCode = '".$r->data[0]."' AND description != 'Balance Amount' AND STR_TO_DATE(created_at,'%Y-%m-%d') >= '".$r->data[1]."' AND STR_TO_DATE(created_at,'%Y-%m-%d') <= '".$r->data[2]."' AND booking_com_ref = 0 

        ORDER BY date asc, id asc");



        // $brname = DB::table('branchs')->where('id', $result[0]->brCode)->first();

        

        if($result) {

            // $brname = DB::table('branchs')->where('id', $result)

            return response(['status'=>'found','data'=>$result]);



        }else{

            

            return response(['status'=>'zero', 'data'=>'0 Record found']);



        }



    }



    public static function export_payment() {

        // // return PaymentReceipt::select('id','brCode','refId','description','creditAmount','debitAmount')->get();



        // return DB::select("SELECT STR_TO_DATE(created_at,'%Y-%m-%d') as date, creditAmount, debitAmount, 

        // cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance

        // FROM payment_receipt

        // JOIN (SELECT @Balance :=0) as tmp

        // WHERE description != 'Balance Amount'

        // ORDER BY date asc, id asc");

   



    }

    

    public function cash_export() {

        

        return Excel::download(new CashReport(Request('id'),Request('fdate'),Request('tdate')), 'cashreport.xlsx');





    }



    public function cash_report_mail() {



        $data = DB::select("SELECT STR_TO_DATE(created_at,'%Y-%m-%d') as date, creditAmount, debitAmount, 

        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance

        FROM payment_receipt

        JOIN (SELECT @Balance :=0) as tmp

        WHERE description != 'Balance Amount' AND brCode = '".Request('id')."' AND STR_TO_DATE(created_at,'%Y-%m-%d') >= '".Request('fdate')."' AND STR_TO_DATE(created_at,'%Y-%m-%d') <= '".Request('tdate')."' AND booking_com_ref = 0

        ORDER BY date asc, id asc");  

        // return view('mail.cash_report')->with(['data'=>$data, 'brname'=>Request('brname')]);

        $data = ['brname'=>Request('brname'), 'data'=>$data];

        $user['to']='dev6@fajri.com';

        Mail::send('mail.cash_report', $data,function($message) use($user){



                $message->to($user['to']);

                $message->subject('Cash Report '.date('Y-m-d h:i:s'));



        });



        return back()->with('success', 'Mail Sent Successfully');



    }



    public function daily_report() {



        return view('reports.daily_reports');

    }



    public function daily_report_ledger(Request $r) {

        // AND Date <= '".$r->data[2]."'

        $result = DB::select("SELECT id, customer_name,room_no,brCode,refId,Date,creditAmount, debitAmount,

        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance

        FROM daily_report

        JOIN (SELECT @Balance :=0) as tmp

        WHERE brCode = '".$r->data[0]."' AND description != 'Balance Amount' AND Date = '".$r->data[1]."' AND booking_com_ref = 0 

        ORDER BY date asc, refId asc");



        // $brname = DB::table('branchs')->where('id', $result[0]->brCode)->first();

        

        if($result) {

            // $brname = DB::table('branchs')->where('id', $result)

            return response(['status'=>'found','data'=>$result]);



        }else{

            

            return response(['status'=>'zero', 'data'=>'0 Record found']);



        }

    }



    public function daily_report_export() {

        

        // return Excel::download(new DailyReport(Request('id'),Request('fdate'),Request('tdate')), 'Daily Cash Report.xlsx');

        return Excel::download(new DailyReport(Request('id'),Request('fdate')), 'Daily Cash Report.xlsx');



    }



    public function daily_report_mail() {
        $data = DB::select("SELECT Date,customer_name,room_no,brCode,refId,creditAmount, debitAmount,
        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance
        FROM daily_report
        JOIN (SELECT @Balance :=0) as tmp
        WHERE brCode = '".Request('id')."' AND description != 'Balance Amount' AND Date = '".Request('fdate')."' AND booking_com_ref = 0
        ORDER BY date asc, id asc");  
        // return view('mail.cash_report')->with(['data'=>$data, 'brname'=>Request('brname')]);
        $data = ['brname'=>Request('brname'), 'data'=>$data];
        $user['to']='dev6@fajri.com';
        Mail::send('mail.daily_cash_report', $data,function($message) use($user){
                $message->to($user['to']);
                $message->subject('Daily Cash Report '.date('Y-m-d h:i:s'));
        });
        return back()->with('success', 'Mail Sent Successfully');
    }


     public function collection_reports() {
        return view('reports.collection_reports');
    }

    // AND booking_com_ref = 0
    public function collection_ledger(Request $request) {
       $result = DB::select("SELECT id, brCode, refId, STR_TO_DATE(created_at,'%Y-%m-%d') as date, description, creditAmount, debitAmount,
        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance
        FROM payment_receipt
        JOIN (SELECT @Balance :=0) as tmp
        WHERE brCode = '".$request->data[0]."' AND description != 'Balance Amount' AND STR_TO_DATE(created_at,'%Y-%m-%d') >= '".$request->data[1]."' AND STR_TO_DATE(created_at,'%Y-%m-%d') <= '".$request->data[2]."' 
        ORDER BY date asc, id asc");
        // $brname = DB::table('branchs')->where('id', $result[0]->brCode)->first();
        if($result) {
            // $brname = DB::table('branchs')->where('id', $result)
            return response(['status'=>'found','data'=>$result]);
        }else{
            return response(['status'=>'zero', 'data'=>'0 Record found']);
        }
    }

     public function collection_export() {
        return Excel::download(new CollectionReport(Request('id'),Request('fdate'),Request('tdate')), 'collection_report.xlsx');
    }

     public function collection_mail() {
        $data = DB::select("SELECT STR_TO_DATE(created_at,'%Y-%m-%d') as date, creditAmount, debitAmount, 
        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance
        FROM payment_receipt
        JOIN (SELECT @Balance :=0) as tmp
        WHERE description != 'Balance Amount' AND brCode = '".Request('id')."' AND STR_TO_DATE(created_at,'%Y-%m-%d') >= '".Request('fdate')."' AND STR_TO_DATE(created_at,'%Y-%m-%d') <= '".Request('tdate')."'
        ORDER BY date asc, id asc");  
        // return view('mail.cash_report')->with(['data'=>$data, 'brname'=>Request('brname')]);
        $data = ['brname'=>Request('brname'), 'data'=>$data];
        $user['to']='dev6@fajri.com';
        Mail::send('mail.cash_report', $data,function($message) use($user){

                $message->to($user['to']);
                $message->subject('Cash Report '.date('Y-m-d h:i:s'));

        });

        return back()->with('success', 'Mail Sent Successfully');

    }
    

}

