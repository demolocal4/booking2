<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Carbon;
use App\Traits\Branches;
class RoomsController extends Controller
{
    use Branches;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->get('role_id') == 1) {
        $rooms = Room::orderBy('id', 'desc')->get();
        }else if(session()->get('role_id') == 5) {
        $rooms = Room::orderBy('id', 'desc')->whereIn('brCode', $this->get_branches())->get();
        }else{
        $rooms = Room::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->get();    
        }
        return view('rooms.home',compact('rooms'));
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
        $save = Room::create([

                'brCode' => $req->brCode,
                'floorRef'=> $req->floorRef,
                'floor' => $req->floor,
                'roomNo' => $req->roomNo,
                'roomStatus' => $req->roomStatus,
                'roomType' => $req->roomType,
                'no_beds' => $req->nobeds,
                'remarks' => $req->remarks,
                'created_by' => session()->get('user_id'),
                'updated_by' => session()->get('user_id'),
                'created_date' => Carbon::now()->toDateTimeString(),
                'updated_date' => Carbon::now()->toDateTimeString(),

        ]);
        
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
        return Room::find($id);
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

        
        $data = Room::find($r->id);
        $data->brCode = $r->updatebrCode;
        $data->floorRef = $r->floorRef_update;
        $data->floor = $r->updatefloor;
        $data->roomNo = $r->updateroomNo;
        $data->roomStatus = $r->updateroomStatus;
        $data->roomType = $r->updateroomType;
        $data->no_beds = $r->updatenobeds;
        $data->remarks = $r->updateremarks;
        $data->updated_by = session()->get('user_id');
        $data->updated_date = Carbon::now()->toDateTimeString();
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
        $del = Room::find($id);
        $del->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }

    public function status_check($id) {

        $res = Room::find($id);
        return response(["status"=>$res->roomStatus]);

    }

}
