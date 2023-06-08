<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomStatus;
class RoomStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roomStatus = RoomStatus::orderBy('id','desc')->get();
        return view('room_status.home', compact('roomStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $req->validate([
              
            'roomStatus'=>'required|unique:roomsstatus',

        ]);

        $data = new RoomStatus;
        $data->roomStatus = $req->roomStatus;
        $data->created_by = session()->get('user_id');
        $data->updated_by = session()->get('user_id');
        $data->created_date = date('Y-m-d h:i:s');
        $data->updated_date = date('Y-m-d h:i:s');
        $save = $data->save();
        if($save) {
            return back()->with('success', 'Successfully Added');                
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = RoomStatus::find($id);
        $del->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}
