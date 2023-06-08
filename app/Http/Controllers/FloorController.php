<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Floor;
use Illuminate\Support\Carbon;
class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->get('role_id') == 1) {
        $floors = Floor::orderBy('id', 'desc')->get();
        }else{
        $floors = Floor::orderBy('id', 'desc')->where('brCode', session()->get('br_code'))->get();    
        }
        return view('floors.home',compact('floors'));
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
        
       $data = new Floor;
       $data->floor = $req->floor;
       $data->series = $req->series;
       $data->brCode = $req->branch;
       $data->created_by = session()->get('user_id');
       $data->updated_by = session()->get('user_id');
       $data->created_date = Carbon::now()->toDateTimeString();
       $data->updated_date = Carbon::now()->toDateTimeString();
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
        return Floor::find($id);
        
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
      $msg = '';  
      $data = Floor::find($id);
      $data->floor = $req->post('data')[0];
      $data->series = $req->post('data')[1];
      $data->brCode = $req->post('data')[2];
      $data->updated_by = session()->get('user_id');
      $data->updated_date  = Carbon::now()->toDateTimeString();
      $save = $data->save();  

      if($save) {
        $msg = array('status'=>'success', 'message'=>'Successfully updated');
      }else{
        $msg = array('status'=>'fail', 'message'=>'Something went wrong try again later');
      } 

      echo json_encode($msg);
      
    //   return  $req->post('data')[0];
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Floor::find($id);
        $del->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}
