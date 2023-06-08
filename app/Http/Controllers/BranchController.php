<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Branch;
class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	if(session()->get('role_id') == 1) {
        	$branches = Branch::orderBy('id', 'desc')->where('status', 1)->get();
        }else if(session()->get('role_id') == 5) {    
            $branches = Branch::orderBy('id', 'desc')
                                                ->whereRaw('FIND_IN_SET(?, owners)', session()->get('user_id'))
                                                ->where('status', 1)->get(); 
		}else{
			$branches = Branch::orderBy('id', 'desc')->where([['status', 1], ['brCode', session()->get('br_code')]])->get();    		
    	}
        return view('branches.home',compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $r->validate([
            'brName'=>'required',
            'owners'=>'required',
            'brLocation'=>'required',
            'brManager'=>'required',
            'brFloors'=>'required|min:1',
            'brRooms'=>'required|min:1',
            'brContact'=>'required|min:10|max:15'
        ],
        [
            'owners.required' =>   'The Building Owners field is required.'
        ]);

        if($r->hasFile('logoFile')) {
            $logoFile = request()->file('logoFile');
            $ext = $logoFile->getClientOriginalExtension();
            $path = 'public/uploads/';
            $filename = date('m-d-Y').'-'.uniqid().'.' . $ext;
            $logoFile->move($path, $filename);
    
        } 
        
        $lastRec = Branch::orderBy('id', 'desc')->first();
        $lastid = $lastRec->id + 1;
        // return "BR".$lastid;

        $data = new Branch;
        $data->brName = $r->brName;
        $data->brLocation = $r->brLocation;
        $data->brManager = $r->brManager;
        $data->brFloors = $r->brFloors;
        $data->brRooms = $r->brRooms;
        $data->brContact = $r->brContact;
        $data->brCode = $lastid;
        $data->brCode1 = strtoupper($r->brcode);
        $data->terms = $r->terms;
        $data->logo = $filename;
        $data->owners = implode(',', $r->owners);
        $data->ownerName = $r->ownerName;
        $data->landlordName = $r->landlordName;
        $data->landlordEmail = $r->landlordEmail;
        $data->landlordPhone = $r->landlordPhone;
        $data->propertyType = $r->propertyType;
        $data->plotNo = $r->plotNo;
        $data->sDepositAmount = $r->sDepositAmount;
        $data->modePayment = $r->modePayment;
        $data->status = 1;
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
        $branchlist = Branch::find($id);
        return view('branches.edit',compact('branchlist'));
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
            'brName'=>'required',
            'owners'=>'required',
            'brLocation'=>'required',
            'brManager'=>'required',
            'brFloors'=>'required|min:1',
            'brRooms'=>'required|min:1',
            'brContact'=>'required|min:10|max:15'
        ],
        [
            'owners.required' =>   'The Building Owners field is required.'
        ]);

        if($r->hasFile('logoFile')) {
            $logoFile = request()->file('logoFile');
            $ext = $logoFile->getClientOriginalExtension();
            $path = 'public/uploads/';
            $filename = date('m-d-Y').'-'.uniqid().'.' . $ext;
            $logoFile->move($path, $filename);
    
        }else{
            $filename = $r->exist_logo;
        }

      
        $data = Branch::find($id);
        
        $data->brName = $r->brName;
        $data->brLocation = $r->brLocation;
        $data->brManager = $r->brManager;
        $data->brFloors = $r->brFloors;
        $data->brRooms = $r->brRooms;
        $data->brContact = $r->brContact;
        $data->terms = $r->terms;
        $data->logo = $filename;
        $data->owners = implode(',', $r->owners);
        $data->ownerName = $r->ownerName;
        $data->landlordName = $r->landlordName;
        $data->landlordEmail = $r->landlordEmail;
        $data->landlordPhone = $r->landlordPhone;
        $data->propertyType = $r->propertyType;
        $data->plotNo = $r->plotNo;
        $data->sDepositAmount = $r->sDepositAmount;
        $data->modePayment = $r->modePayment;
        $data->updated_by = session()->get('user_id');
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
        $del = Branch::find($id);
        $del->delete();
        return redirect()->back()->with('success', 'Successfully Deleted');
    }
}
