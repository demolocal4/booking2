<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\RoomTypes;

use App\Models\RoomType;

use App\Models\Branch;

use Illuminate\Support\Carbon;

use Carbon\CarbonPeriod;

use Illuminate\Support\Fluent;



class RoomTypeController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $roomTypes = RoomTypes::orderBy('id','desc')->paginate(50);

        // new filter for list view added on 12-04-2023

        if(request('search_from') == 'roomType') {
        $type = request('search_with');
        $roomTypes = RoomTypes::query()->where('roomType', 'like', "%{$type}%")
                         ->paginate(30)->withQueryString();
        }
        if(request('search_from') == 'brCode') {
        $type = request('search_with');
        $roomTypes = RoomTypes::query()->where('brCode', 'like', "%{$type}%")
                         ->paginate(30)->withQueryString();
        }

        // new filter for list view added on 12-04-2023

         if(session()->get('role_id') == 1) {
            $branches = Branch::whereNotIn('id', [1000])->where('status', 1)->get();    
            }else{
            $branches = Branch::whereNotIn('id', [1000])
                                ->where('status', 1)
                                ->where('brCode', session()->get('br_code'))
                                ->get();
            }
    
           
            if(session()->get('role_id') == 1) {
            $data = RoomTypes::query()->when(request('branches'), function($query){
                    $query->where('brCode', request('branches')); 
            })->whereHas('branch')->orderBy('id')->whereDate('date','>=',date('Y-m-d'))->get();    
            }
            else{
            $data = RoomTypes::query()->when(request('branches'), function($query){
                    $query->where('brCode', request('branches')); 
            })->whereHas('branch')->where('brCode', session()->get('br_code'))->orderBy('id')->whereDate('date','>=',date('Y-m-d'))->get();    
            }

        return view('room_types.home', compact('roomTypes','data','branches'));

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
	
	   $dt = explode('-', $req->daterange);

        if(count($dt) > 1) {

        $start = Carbon::parse($dt[0])->toDateTimeString();

        $end = Carbon::parse($dt[1])->toDateTimeString();

        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $start);

        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $end);

        $dateRange = CarbonPeriod::create($startDate, $endDate);

        foreach($dateRange as $key => $val) {
        $chkDate = RoomTypes::where('roomType', $req->roomType)->whereDate('date', $val)->where('brCode', $req->brCode)->get();
        if($chkDate->count()) {
            foreach($chkDate as $key=>$r) {
              $save = RoomTypes::where('id', $r->id)->update([
                        'date'          => $val,
                        'regular'       => $req->regular,
                        'weekly'        => $req->weekly,
                        'monthly'       => $req->monthly,
                        'vat'           => $req->vat,
                        'created_by'    => session()->get('user_id'),
                        'updated_by'    => session()->get('user_id'),
                        'created_date'  => date('Y-m-d h:i:s'),
                        'updated_date'  => date('Y-m-d h:i:s'),
                ]);
            }
        }else{

            $data = new RoomTypes;
            $data->brCode   = $req->brCode;
            $data->roomtype_id = $req->id;
            $data->roomType = $req->roomType;
            $data->date     = $val;
            $data->regular  = $req->regular;
            $data->weekly   = $req->weekly;
            $data->monthly  = $req->monthly;
            $data->vat      = $req->vat;
            $data->created_by = session()->get('user_id');
            $data->updated_by = session()->get('user_id');
            $data->created_date = date('Y-m-d h:i:s');
            $data->updated_date = date('Y-m-d h:i:s');
            $save = $data->save();
        }    
        }

        }else{

        $dateSingle = Carbon::parse($dt[0])->toDateTimeString();    

                $data = new RoomTypes;

                $data->brCode   = $req->brCode;

                $data->roomtype_id = $req->id;

                $data->roomType = $req->roomType;

                $data->date     = $dateSingle;

                $data->regular  = $req->regular;

                $data->weekly   = $req->weekly;

                $data->monthly  = $req->monthly;

                $data->vat      = $req->vat;

                $data->created_by = session()->get('user_id');

                $data->updated_by = session()->get('user_id');

                $data->created_date = date('Y-m-d h:i:s');

                $data->updated_date = date('Y-m-d h:i:s');

                $save = $data->save();

        }

        

        if($save) {

            return back()->with('success', 'Rates Successfully added');                

        }else{

            return back()->with('fail', 'Something went wrong!');

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

        return RoomTypes::find($id);

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

       

        

        $data = RoomTypes::find($req->id);

        $data->brCode = $req->brCode;

        $data->roomtype_id = $req->id2;        

        $data->roomType = $req->roomType;

        $data->date     =  Carbon::parse($req->date)->toDateTimeString();

        $data->regular = $req->regular;

        $data->weekly = $req->weekly;

        $data->monthly = $req->monthly;

        $data->vat = $req->vat;

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

        $del = RoomTypes::find($id);

        $del->delete();

        return redirect()->back()->with('success', 'Successfully Deleted');

    }





    public function room_rates(Request $request) {

        if(session()->get('role_id') == 1) {
        $branches = Branch::whereNotIn('id', [1000])->where('status', 1)->get();    
        }else{
        $branches = Branch::whereNotIn('id', [1000])
                            ->where('status', 1)
                            ->where('brCode', session()->get('br_code'))
                            ->get();
        }



       

        if(session()->get('role_id') == 1) {
        $data = RoomTypes::query()->when(request('branches'), function($query){
                $query->where('brCode', request('branches'));
        })->whereHas('branch')->orderBy('id')->get();    
        }
        else{
        $data = RoomTypes::query()->when(request('branches'), function($query){
                $query->where('brCode', request('branches')); 
        })->whereHas('branch')->where('brCode', session()->get('br_code'))->orderBy('id')->get();    

        }    



        return view('room_types.room_rates',compact('data','branches'));

                       

        // if($request->has('branches')) {

        // $data = RoomTypes::whereHas('branch')

        //                    ->where('brCode', $request->branches) 

        //                   ->get();    

        // }else{

        // if(session()->get('user_id') == 1) {    

        // $data = RoomTypes::whereHas('branch')->get();

        // }else{

        // $data = RoomTypes::whereHas('branch')->where('brCode', session()->get('br_code'))->get();    

        // }

        // }



        // if(session()->get('role_id') == 1) {

        // $data = RoomTypes::whereHas('branch')->get();    

        // }else{

        // $data = RoomTypes::whereHas('branch')->where('brCode', session()->get('br_code'))->get();    

        // }    



        

       

    }



    public function addType(Request $request) {

        $data = $request->all();

        $data = $request->except('_token');

        $res = RoomType::updateOrCreate(['id' => $request->id], $data);

        if($res) {

            if($request->filled('id')) {

                return back()->with('success', 'Type Successfully Updated');

            }else{

                return back()->with('success', 'Type Successfully added');

            }

        }else{

            return back()->with('fail', 'Something went wrong!');

        }

    }

    public function rates_update(Request $request) {
        $data = $request->only('regular','weekly','monthly');
        $res = RoomTypes::where('id',$request->id)->update($data);
        if($res) {
        return back()->with('success', 'Rates Successfully Updated');    
        }else{
        return back()->with('fail', 'Something went wrong!');    
        }
    }

}

