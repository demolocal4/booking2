<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Traits\Branches;
class MainController extends Controller
{
    use Branches;

    public function application() {
        //$userinfo = User::find(session()->has('user_id'));
        $userinfo = DB::table('users')->where('id', session()->get('user_id'))->get();
        $roles = DB::table('userlevels')->where('id', $userinfo[0]->role)->get();
       
       return view('admin.home', ['userinfo'=>$userinfo,'role'=>$roles]);
    }

    public function login() {
        if(session()->has('user_id')) {

            return back();

        }else{
        
            return view('auth.login');  
            
        }        
        
    }

    public function register() {

        if(session()->has('user_id')) {

            return back();

        }else{
        
            return view('auth.register');
            
        }      
        
    }
    public function forgotpassword() {
        return view('auth.forgotPassword');
    }
    public function signup(Request $r) {
        $r->validate([

                'name'=>'required',
                'email'=>'required|email|unique:users',
                'mobile'=>'required|min:10|max:15',
                'password'=>'required|min:5|max:12',
        ]);

        $data  = new User;
        $data->name = $r->post('name');
        $data->email = $r->post('email');
        $data->mobile = $r->post('mobile');
        $data->fullname = 'pending';
        $data->role = 0;
        $data->password = Hash::make($r->password);
        $data->brCode = 'pending';
        $data->status = 0;
        $data->profile_pic = 'profile-pic.jpg';
        $data->created_date = date('Y-m-d h:i:s');
        $data->updated_date = date('Y-m-d h:i:s');
        $save = $data->save();

        if($save) {
            return back()->with('success', 'Successfully registered');                
        }else{
            return back()->with('fail', 'Something went wrong try again later');
        }
        
        

    }

    public function auth_check(Request $r) {

        $r->validate([

            'name'=>'required',
            'password'=>'required|min:5|max:12',

        ]);

        $userinfo = User::where('name',$r->post('name'))
                          ->orWhere('email',$r->post('name'))
                          ->get();
        $brInfo = DB::table('branchs')->where('brCode', $userinfo[0]->brCode ?? 0)->get();
        
        if($userinfo->isEmpty()) {
            
            return back()->with('fail', 'Username or Email does not exist');

        }else{

            if(Hash::check($r->password, $userinfo[0]->password)) {
                
                if($userinfo[0]->status == 0) {
                    
                return back()->with('fail', 'Account not yet activated');    
                
                }else{
                    
                    $r->session()->put('user_id', $userinfo[0]->id);
                    $r->session()->put('role_id', $userinfo[0]->role);
                    $r->session()->put('br_code', $userinfo[0]->brCode);
                    $r->session()->put('br_code1', $brInfo[0]->brCode1);

                    DB::table('usertrackere')->insert([
                        'loginDate' => date('Y-m-d H:i:s'),
                        //'loginTime' => session()->get('role_id') == 3 ? date('H:i:s') : '00',
                        
                        'loginTime' =>  date('H:i:s'), // add new code by tayyab @ 06-08-2023

                        'logoutTime' => '00',
                        //'userId' => session()->get('role_id') == 3 ? session()->get('user_id') : 0,
                        'userId' =>  session()->get('user_id'), // add new code by tayyab @ 06-08-2023
                        'userName' => $userinfo[0]->name, // add new code by tayyab @ 06-08-2023
                        'status' => 0,
                        'brCode' => $brInfo[0]->brCode, // add new code by tayyab @ 06-08-2023
                        'brName' => $brInfo[0]->brName, // add new code by tayyab @ 06-08-2023
                        'iP'     => $_SERVER['REMOTE_ADDR'], // add new code by tayyab @ 06-08-2023
                    ]);
                    return redirect()->route('application')->with('userinfo', $userinfo);
                }
            }else{
                return back()->with('fail', 'Incorrect Password');
            }

        }

    }

    public static function menu() {
        $menu = DB::table('menus')->orderBy('m_order', 'asc')->where('access', session()->get('role_id'))
                                  ->whereIn('id', [3,4,5,7,9,10,12,16,21,22,23,24,25])  
                                  ->get();
        return $menu;

    }

    public function profile() {
        $users = DB::table('users')->where('id', session()->get('user_id'))->get();
        $roles = DB::table('userlevels')->where('id', $users[0]->role)->get();
        return view('admin.profile', compact('users','roles'));
        
    }

    public function profile_update(Request $r) {

            $r->validate([
                'username'  =>  'required',
                'mobile'    =>  'required',
                'email'     =>  'required|email',
                'fullname'  =>  'required',    

            ]);

           $data = User::find($r->post('id'));
           $data->name = $r->post('username');
           $data->mobile = $r->post('mobile');
           $data->email = $r->post('email');
           $data->fullname = $r->post('fullname');
           $data->updated_date = date('Y-m-d h:i:s');
           if($data->save()) {
                return back()->with('success', 'Successfully updated');
           }else{
                return back()->with('fail', 'Something went wrong');
           }
  
    }

    public function reset_password(Request $r) {

        $r->validate([
            'password'  =>  'required|min:5|max:15',
        ]);

        $data = User::find($r->id);
        $data->password = Hash::make($r->password);
        $data->updated_date = date('Y-m-d h:i:s');
        if($data->save()) {
            return back()->with('success', 'Successfully updated'); 
        }else{
            return back()->with('fail', 'Something went wrong');
        }

    }

    public function profile_pic(Request $r) {

            
            //$path = 'public/uploads/';
            //$img_name = $r->file('photo')->getClientOriginalName();
            //$data = $r->file('photo')->storeAs($path, $img_name);
            // $filename = $r->file('photo')->store('public/uploads/');
            // $getFile = explode('/', $filename);

            if($r->hasFile('photo')) {
        
                $profilePhoto = request()->file('photo');
                $ext = $profilePhoto->getClientOriginalExtension();
                $path = 'public/uploads/';
                $filename = date('m-d-Y').'-'.uniqid().'.' . $ext;
                $profilePhoto->move($path, $filename);
        
            }  
            
           if($filename) {
                
           $data = User::find($r->post('id'));
           $data->profile_pic = $filename;
           $data->updated_date = date('Y-m-d h:i:s');
           if($data->save()) {
                return back()->with('success', 'Successfully updated');
           }else{
                return back()->with('fail', 'Something went wrong');
           }

            }
            
            // $data = $r->file('photo')->store('uploads/');

            // if($data) {
            //     return $r->file('photo')->getClientOriginalName();
            // }

    }

    public function temporary() {

            $data = DB::table('usertrackere')->insert([
                'loginDate' => date('Y-m-d H:i:s'),
                'loginTime' => '00',
                'logoutTime' => date('H:i:s'),
                'userId' => Request('user_id'),
                'status' => Request('status'),
            ]);
            if($data) {
                return response(['status'=>'success', 'message'=>'Successfully Temporary Logout']);                
            }else{
                return response(['status'=>'fail', 'message'=>'Something went wrong']);                
            }

    }

    public function shift_change($id){

            $result = DB::select("SELECT id, customer_name,room_no,brCode,refId,Date,Time,creditAmount, debitAmount,
            cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance
            FROM daily_report
            JOIN (SELECT @Balance :=0) as tmp
            WHERE brCode = '".session()->get('br_code')."' AND description != 'Balance Amount' AND Date = '".date('Y-m-d')."' AND created_by = '".$id."' 
            ORDER BY date asc, id asc");

            return view('handover.handover_rpt',compact('result'));
    }

    public static function ownersBr() {

        return self::get_branches();

    }

}
