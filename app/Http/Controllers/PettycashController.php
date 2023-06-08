<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PettyCash;

use Illuminate\Support\Carbon;

use Carbon\CarbonPeriod;

use Illuminate\Support\Facades\DB;

use DateTime;

use App\Traits\Branches;

class PettycashController extends Controller

{

    use Branches;

    public function index() {

    	if(session()->get('role_id') == 1) {

    	// $data = PettyCash::all();    	

    	$data = PettyCash::where('brCode', Request('code'))->get();	

        }else if(session()->get('role_id') == 5) {

        $data = PettyCash::where('brCode', Request('code'))->get();     

    	}else{	

        $data = PettyCash::where('brCode', session()->get('br_code'))->get();

    	}

        return view('petty_cash.petty_cash',compact('data'));

    } 





    public function store(Request $r) {

        

        if($r->hasFile('inv_copy')) {

            $docCopy = request()->file('inv_copy');

            $ext = $docCopy->getClientOriginalExtension();

            $path = 'public/uploads/';

            $docName = date('m-d-Y').'-'.uniqid().'.' . $ext;

            $docCopy->move($path, $docName);

        }



        $data = new PettyCash;

        $data->cashDate = $r->date;

        $data->brCode = session()->get('br_code');

        $data->invNumber = $r->inv_number;

        $data->vendorName = $r->vendor_name;

        $data->invPhoto = $docName;

        $data->cashIn = 0;

        $data->cashOut = array_sum($r->amount);

        $data->remarks = 'PettyCash Out';

        $data->created_by = session()->get('user_id');

        $data->updated_by = session()->get('user_id');

        $data->created_at = date('Y-m-d H:i:s');

        $data->updated_at = date('Y-m-d H:i:s');

        $save = $data->save();

        if($save) {



            $count = count($r->description);

            for($i = 0; $i<$count; $i++) {

                    DB::table('petty_cash_child')->insert([

                            'refId' => $data->id,

                            'description' => $r->description[$i],

                            'amount' => $r->amount[$i],

                    ]);

    

            }



        return back()->with('success', 'PettyCash Successfull added');    

        }else{

        return back()->with('fail', 'Something went wrong');    

        }



        

    }



    public function pettyCashIn(Request $r) {



        $data = new PettyCash;

        $data->cashDate = $r->cashin_date;

        $data->brCode = session()->get('br_code');

        $data->invNumber = $r->vc_number;

        $data->vendorName = 'none';

        $data->invPhoto = 'none';

        $data->cashIn = $r->amount_in;

        $data->cashOut = 0;

        $data->remarks = $r->remarks;

        $data->created_by = session()->get('user_id');

        $data->updated_by = session()->get('user_id');

        $data->created_at = date('Y-m-d H:i:s');

        $data->updated_at = date('Y-m-d H:i:s');

        $save = $data->save();



        if($save) {



        return back()->with('success', 'PettyCash Successfully In');    

        }else{

        return back()->with('fail', 'Something went wrong');    

        }



    }



    public function cashDetails($id) {



        $result = DB::table('petty_cash_child')->where('refId', $id)->get();

        if($result) {

        return response(['status'=>'success','data'=>$result]);    

        }else{

        return response(['status'=>'zero','data'=>'Data not Found']);        

        }



    }



    public function pettyCashedit($id) {



            $res = PettyCash::find($id);

            $child = DB::table('petty_cash_child')->where('refId', $id)->get();

            if($res) {

            return response(['status'=>'success','parent'=>$res, 'child'=>$child]);    

            }else{

            return response(['status'=>'zero','data'=>'Data not Found']);            

            }



    }



    public function update_pettycash(Request $r) {



       



        if($r->hasFile('inv_copy')) {

            $docCopy = request()->file('inv_copy');

            $ext = $docCopy->getClientOriginalExtension();

            $path = 'public/uploads/';

            $docName = date('m-d-Y').'-'.uniqid().'.' . $ext;

            $docCopy->move($path, $docName);

        }else{

            $docName = $r->hinv_copy;            

        }        

        

        $data = PettyCash::find($r->row_id);

        $data->invNumber = $r->inv_number;

        $data->vendorName = $r->vendor_name;

        $data->invPhoto = $docName;

        $data->cashIn = 0;

        $data->cashOut = array_sum($r->amount);

        $data->remarks = 'PettyCash Out';

        $data->updated_by = session()->get('user_id');

        $data->updated_at = date('Y-m-d H:i:s');

        $update = $data->save();

        

        if($update) {

            $count = count($r->child_id);

            for($i = 0; $i<$count; $i++) {



                        if($r->child_id[$i] == null) {



                        DB::table('petty_cash_child')->insert([

                                'refId' => $r->row_id,

                                'description' => $r->description[$i],

                                'amount' => $r->amount[$i],

                        ]);

                        

                        }else{

                        DB::table('petty_cash_child')

                                ->where('id', $r->child_id[$i])                    

                                ->update([

                                'description' => $r->description[$i],

                                'amount' => $r->amount[$i],

                        ]);



                    }

                    

                    

    

            }

        return back()->with('success', 'PettyCash Successfull Updated');    

        }else{

        return back()->with('fail', 'Something went wrong');    

        }



        

        // $count = count($r->child_id);

        // for($i = 0; $i<$count; $i++) {



        //         if($r->child_id[$i] == null) {

        //             echo 'null value aslo'."<br/>";

        //         }else{

        //             echo $r->description[$i]."<br/>";

        //         }



        // }

        



    }



    public function deleteCash($id) {



        $res = DB::table('petty_cash_child')->where('id', $id)->delete();

        if($res) {

        return response(['status'=>'success','message'=>'Successfully deleted,update new changes']);        

        }else{

        return response(['status'=>'fail','message'=>'Something went wrong']);    

        }



    }



    public function pettyCashrpt() {



            return view('petty_cash.pettyCashreports');

    }



    public function pettyCashprint(Request $r) {



        $cashin = DB::table('petty_cash')->whereRaw('brCode="'.$r->data[0].'" AND STR_TO_DATE(cashDate,"%Y-%m-%d") < "'.$r->data[1].'"')->sum('cashIn');

        $cashout = DB::table('petty_cash')->whereRaw('brCode="'.$r->data[0].'" AND STR_TO_DATE(cashDate,"%Y-%m-%d") < "'.$r->data[1].'"')->sum('cashOut');

        $open_bal = $cashin - $cashout;



        $result = DB::select("SELECT id, brCode, invNumber, vendorName, STR_TO_DATE(cashDate,'%Y-%m-%d') as date, cashIn, cashOut,

        cast((@Balance := @Balance + cashIn - cashOut) as DECIMAL(16,2)) as Balance

        FROM petty_cash

        JOIN (SELECT @Balance := $open_bal) as tmp

        WHERE brCode = '".$r->data[0]."' AND STR_TO_DATE(cashDate,'%Y-%m-%d') >= '".$r->data[1]."' AND STR_TO_DATE(cashDate,'%Y-%m-%d') <= '".$r->data[2]."'

        ORDER BY date asc, id asc");



        // $brname = DB::table('branchs')->where('id', $result[0]->brCode)->first();

        

        if($result) {

            // $brname = DB::table('branchs')->where('id', $result)

            return response(['status'=>'found','data'=>$result]);



        }else{

            

            return response(['status'=>'zero', 'data'=>'0 Record found']);



        }



    }

    // PettyCash update module added on 04-04-2023
    public function pettyCashUpdate(Request $request) {
        $res = PettyCash::where('id', $request->id)->update([
               'cashDate'      =>   $request->cashupdate_date, 
               'invNumber'     =>   $request->vc_number_update, 
               'cashIn'        =>   $request->amount_update, 
               'remarks'       =>   $request->remarks_update, 
               'updated_by'    =>   session()->get('user_id'), 
               'updated_at'    =>   date('Y-m-d H:i:s'), 

        ]);
        if($res) {
            return back()->with('success', 'Cash Successfully updated');
        }else{
            return back()->with('fail', 'Something went wrong');
        }

    }

}