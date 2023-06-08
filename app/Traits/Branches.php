<?php
namespace App\Traits;
use Illuminate\Support\Facades\DB;
trait Branches {
    public function get_branches() {
        
        global $brcodes;
        $res_branches = DB::table('branchs')->where('status', 1)
                                            ->whereRaw('FIND_IN_SET(?, owners)', session()->get('user_id'))->get();
               foreach($res_branches as $br) {
                    $brcodes[] = $br->brCode;
               }  
               //$brcodes = implode(',', $data);
        return $brcodes;                                              
    }
}




