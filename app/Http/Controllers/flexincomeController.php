<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\flexincomeModel;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use DateTime;
class flexincomeController extends Controller
{
    public function index() {

        if(session()->get('role_id') == 1) {
        // $data = flexincomeModel::orderBy('id','desc')->get();
        $data = flexincomeModel::orderBy('id','desc')->where('brCode', Request('code'))->get();    
        }else if(session()->get('role_id') == 5) {
        $data = flexincomeModel::orderBy('id','desc')->where('brCode', Request('code'))->get();        
        }else{
        $data = flexincomeModel::orderBy('id','desc')->where('brCode', session()->get('br_code'))->get();
        }
        return view('flexible_income.flexibleincome',compact('data'));

    }

    public function store(Request $r) {

        $data = new flexincomeModel;
        $data->incomeDate       = $r->date;
        $data->refId            = $r->refId;
        $data->brCode           = $r->brCode;  
        $data->roomRef          = $r->roomRef;
        $data->roomNo           = $r->roomNo;
        $data->CustomerName     = $r->CustomerName;
        $data->CustomerMobile   = $r->CustomerMobile;
        $data->created_by       = session()->get('user_id');
        $data->updated_by       = session()->get('user_id');
        $data->created_at       = date('Y-m-d H:i:s');
        $data->updated_at       = date('Y-m-d H:i:s');
        $save = $data->save();
        if($save) {

            $count = count($r->description);
            for($i = 0; $i<$count; $i++) {
                    DB::table('flexible_income_child')->insert([
                            'refId' => $data->id,
                            'description' => $r->description[$i],
                            'amount' => $r->amount[$i],
                    ]);
    
            }

        return back()->with('success', 'Flexible Income Successfull added');    
        }else{
        return back()->with('fail', 'Something went wrong');      

        }
        
    }


    public function income_details($id) {

        $result = DB::table('flexible_income_child')->where('refId', $id)->get();
        if($result) {
        return response(['status'=>'success','data'=>$result]);    
        }else{
        return response(['status'=>'zero','data'=>'Data not Found']);        
        }

    }

    public function flexincomerpt() {

        return view('flexible_income.flexincomereports');
    }

    public function flexIncomeprint(Request $r) {

        
        $result = DB::select("SELECT STR_TO_DATE(incomeDate, '%Y-%m-%d') as date, brName, roomNo,CustomerName,CustomerMobile,description, amount FROM flexibleincomeview 
        WHERE brCode = '".$r->data[0]."' AND STR_TO_DATE(incomeDate,'%Y-%m-%d') >= '".$r->data[1]."' AND STR_TO_DATE(incomeDate,'%Y-%m-%d') <= '".$r->data[2]."'
        ORDER BY date asc");

        // $brname = DB::table('branchs')->where('id', $result[0]->brCode)->first();
        
        if($result) {
            // $brname = DB::table('branchs')->where('id', $result)
            return response(['status'=>'found','data'=>$result]);

        }else{
            
            return response(['status'=>'zero', 'data'=>'0 Record found']);

        }

    }


}
