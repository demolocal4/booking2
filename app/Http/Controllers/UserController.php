<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::orderBy('id', 'desc')->where('status', '!=', 0)->get();
        $users = User::orderBy('id', 'desc')->paginate(10);
        $isAdmin = User::where('id', session()->get('user_id'))->first();
        if($isAdmin->role != 1) {
            abort(403);
        }
        return view('application.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('application.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $page = implode(',', $r->pages);

        $r->validate([

            'fullname'=>'required',
            'name'=>'required|unique:users',
            // 'email'=>'required|email|unique:users',
            'mobile'=>'required|min:10|max:15|unique:users',
            'password'=>'required|min:5|max:15',
            'role'=>'required',
            'status'=>'required',
            'branch'=>'required',
        ]);

        $data  = new User;
        $data->name = strtolower($r->post('name'));
        $data->mobile = $r->post('mobile');
        $data->email = $r->post('email');
        $data->fullname = $r->post('fullname');
        $data->role = $r->post('role');
        $data->password = Hash::make($r->password);
        $data->brCode = $r->post('branch');
        $data->status = $r->post('status');
        $data->pages = $page;
        $data->profile_pic = 'profile-pic.jpg';
        $data->created_date = date('Y-m-d h:i:s');
        $data->updated_date = date('Y-m-d h:i:s');
        $save = $data->save();

        if($save) {
            return back()->with('success', 'Successfully added');                
        }else{
            return back()->with('fail', 'Something went wrong try again later');
        }
    }

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
        $userslist = User::find($id);
        return view('application.edit',compact('userslist'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, $id)
    {
        $r->validate([

            'fullname'=>'required',
            'name'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|min:10|max:15',
            'role'=>'required',
            'status'=>'required',
            'branch'=>'required',
        ]);

        $page = implode(',', $r->pages);

        $data  = User::find($id);
        $data->name = strtolower($r->post('name'));
        $data->mobile = $r->post('mobile');
        $data->email = $r->post('email');
        $data->fullname = $r->post('fullname');
        $data->role = $r->post('role');
        $data->brCode = $r->post('branch');
        $data->status = $r->post('status');
        $data->pages = $page;
        $data->updated_date = date('Y-m-d h:i:s');
        $save = $data->save();

        if($save) {
            return back()->with('success', 'Successfully Updated');                
        }else{
            return back()->with('fail', 'Something went wrong try again later');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = User::find($id);
        $del->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}
