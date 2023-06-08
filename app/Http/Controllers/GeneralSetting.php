<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Setting;

use App\Models\User;

use Illuminate\Support\Carbon;



class GeneralSetting extends Controller

{

    function general_setting() {

        $data = Setting::where('setting_name', 'vat')->first();

        $isAdmin = User::where('id', session()->get('user_id'))->first();

        if($isAdmin->role != 1) {

            abort(403);

        }  

        return view('settings.home', compact('data'));

    }



    function vatSetting(Request $r) {

            $msg = '';

            if($r->action == 1) {

                

                $res = Setting::where('setting_name', 'vat')->get();

                if($res->isEmpty()) {

                    $data = Setting::create([

                            'setting_name' => 'vat',

                            'setting_val' => $r->action,

                            'created_by' => session()->get('user_id'),

                            'updated_by' => session()->get('user_id'),

                            'created_at' => Carbon::now()->toDateTimeString(),

                            'updated_at' => Carbon::now()->toDateTimeString(),



                    ]);



                    if($data) {

                        return response(['status'=>'success', 'message'=>'Vat successfully added']);

                    }else{

                        return response(['status'=>'fail', 'message'=>'something went wrong']);    

                    }





                }else{

                    

                    $update = Setting::where('setting_name', 'vat')->first();

                    $update->setting_val = $r->action;

                    $update->updated_by = session()->get('user_id');

                    $update->updated_at = Carbon::now()->toDateTimeString();

                    $save = $update->save();

                    if($save) {



                        return response(['status'=>'success', 'message'=>'Vat successfully added']);

                    

                    // $msg = array('status'=>'success', 'message'=>'Vat Successully enable');



                    }else{



                        return response(['status'=>'fail', 'message'=>'something went wrong']);    

                    

                    // $msg = array('status'=>'fail', 'message'=>'Something went wrong');    



                    }



                    // echo json_encode($msg);

                }







            }



            if($r->action == 0) {

                   

                    $disabled = Setting::where('setting_name', 'vat')->first();

                    $disabled->setting_val = $r->action;

                    $disabled->updated_by = session()->get('user_id');

                    $disabled->updated_at = Carbon::now()->toDateTimeString();

                    $disable = $disabled->save();

                    if($disable) {

                    

                        return response(['status'=>'success', 'message'=>'Vat successfully disabled']);



                    }else{

                    

                        return response(['status'=>'fail', 'message'=>'something went wrong']);    

                    }



                    



            }

            



    }



    function time_duration(Request $r) {



        $res = Setting::where('setting_name', 'time_duration')->get();

                if($res->isEmpty()) {

                    $data = Setting::create([

                            'setting_name' => 'time_duration',

                            'setting_val' => $r->duration,

                            'created_by' => session()->get('user_id'),

                            'updated_by' => session()->get('user_id'),

                            'created_at' => Carbon::now()->toDateTimeString(),

                            'updated_at' => Carbon::now()->toDateTimeString(),



                    ]);



                    if($data) {

                        return response(['status'=>'success', 'message'=>'Cancellation duration  added']);

                    }else{

                        return response(['status'=>'fail', 'message'=>'something went wrong']);    

                    }





                }else{

                    

                    $update = Setting::where('setting_name', 'time_duration')->first();

                    $update->setting_val = $r->duration;

                    $update->updated_by = session()->get('user_id');

                    $update->updated_at = Carbon::now()->toDateTimeString();

                    $save = $update->save();

                    if($save) {



                    return response(['status'=>'success', 'message'=>'Cancellation duration  updated']);

                    

                    // $msg = array('status'=>'success', 'message'=>'Vat Successully enable');



                    }else{



                    return response(['status'=>'fail', 'message'=>'something went wrong']);    

                    

                    // $msg = array('status'=>'fail', 'message'=>'Something went wrong');    



                    }



                    // echo json_encode($msg);

                }



    }



    function add_access(Request $r) {



        $res = DB::table('role_access')->insert([



                'levelType'     =>  $r->role,

                'canAdd'        =>  0,

                'canEdit'       =>  0,

                'canDelete'     =>  0,

                'canView'       =>  0,

                'created_by'    =>  session()->get('role_id'),

                'updated_by'    =>  session()->get('role_id'),

                'created_at'    =>   Carbon::now()->toDateTimeString(),

                'updated_at'    =>   Carbon::now()->toDateTimeString()



        ]);





        if($res) {

            return response(['status'=>'success', 'message'=>'New Access Successfully added']); 

        }else{

            return response(['status'=>'fail', 'message'=>'something went wrong']);

        }



    }



    function assign_access(Request $r) {



            if($r->action == 'canAdd') {



                $res = DB::table('role_access')->where('id',$r->id)

                                               ->update([



                                                        'canAdd'        =>  $r->access,

                                                        'updated_by'    =>  session()->get('role_id'),

                                                        'updated_at'    =>  Carbon::now()->toDateTimeString()



                                               ]); 

                

            }



            if($r->action == 'canEdit') {



                $res = DB::table('role_access')->where('id',$r->id)

                                               ->update([



                                                        'canEdit'       =>  $r->access,

                                                        'updated_by'    =>  session()->get('role_id'),

                                                        'updated_at'    =>  Carbon::now()->toDateTimeString()



                                               ]); 

                

            }



            if($r->action == 'canDelete') {



                $res = DB::table('role_access')->where('id',$r->id)

                                               ->update([



                                                        'canDelete'     =>  $r->access,

                                                        'updated_by'    =>  session()->get('role_id'),

                                                        'updated_at'    =>  Carbon::now()->toDateTimeString()



                                               ]); 

                

            }



            if($r->action == 'canView') {



                $res = DB::table('role_access')->where('id',$r->id)

                                               ->update([



                                                        'canView'       =>  $r->access,

                                                        'updated_by'    =>  session()->get('role_id'),

                                                        'updated_at'    =>  Carbon::now()->toDateTimeString()



                                               ]); 

                

            }



            if($res) {

                if($r->access == 1) {

                return response(['status'=>'success', 'message'=>'Successfully enabled']);                 

                }else{

                return response(['status'=>'success', 'message'=>'Successfully disabled']);                     

                }    



            }else{

            return response(['status'=>'fail', 'message'=>'something went wrong']);    

            }



            



    }



    function role_delete($id) {



        $res = DB::table('role_access')->where('id', $id)->delete();

        

        if($res) {

        return back()->with('success', 'Successfully deleted');    

        }else{

        return back()->with('fail', 'Something went wrong');    

        }



    }



    function role_add(Request $r) {



            $res = DB::table('userlevels')->where('levelType', $r->role)->get();

            if($res->count() > 0) {

            return response(['status'=>'exist', 'message'=>'Role already exist']);                 

            }else{



            $add = DB::table('userlevels')->insert(['levelType'=>$r->role]);

            

            if($add) {

            return response(['status'=>'success', 'message'=>'Successfully Added']);    

            }else{

            return response(['status'=>'fail', 'message'=>'something went wrong']);    

            }



            }

            

    }



    function levelType_delete($id) {



        $res = DB::table('userlevels')->where('id', $id)->delete();

        

        if($res) {

        return back()->with('success', 'Successfully deleted');    

        }else{

        return back()->with('fail', 'Something went wrong');    

        }



    }



    public function additionalCharges(Request $request) {

        $data = $request->all();

        $data = $request->except('_token');

        $res = DB::table('add_charges')->updateOrInsert(['id' => $request->id], $data);

        if($res) {

        if($request->filled('id')) {

        return back()->with('success1', 'Charges Successfully updated');    

        }else{

        return back()->with('success1', 'Charges Successfully added');

        }    

        }else{

        return back()->with('fail1', 'Something went wrong');    

        }



    }



    public function cancelTimeDuration(Request $request) {

        $res = DB::table('settings')->where('id', 9)->update(['setting_val' => $request->booking_com_duration]);

        if($res) {

        return back()->with('success1', 'Cancel Time Duration updated');

        }else{

        return back()->with('fail1', 'Something went wrong');    

        }

    }

    public function newRoomType() {
        return view('settings.room_types');
    }

    // add new code by tayyab @ 06-08-2023
    
    public function user_tracking() {
        $data = DB::table('usertrackere')->orderBy('id','desc')->paginate(15);
        return view('settings.users_tracking',compact('data'));
    }
    public function user_tracking_filter(Request $request) {
        if ($request->filter_by == 'userId') {
            $data = DB::table('usertrackere')->where($request->filter_by, $request->find_what)->paginate(15)->withQueryString();
        }
        if ($request->filter_by == 'userName') {
            $data = DB::table('usertrackere')->where($request->filter_by, $request->find_what)->paginate(15)->withQueryString();
        }
        if ($request->filter_by == 'brCode') {
            $data = DB::table('usertrackere')->where($request->filter_by, $request->find_what)->paginate(15)->withQueryString();
        }
        if ($request->filter_by == 'loginDate') {
            $dateFilter = Carbon::parse($request->find_what)->toDateString();
            $data = DB::table('usertrackere')->whereDate($request->filter_by, $dateFilter)->paginate(15)->withQueryString();
        }

        return view('settings.users_tracking',compact('data'));

    }

}

