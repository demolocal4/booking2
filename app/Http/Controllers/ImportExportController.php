<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Room;
use Excel;
use App\Imports\RoomsImport;
class ImportExportController extends Controller
{
   function rooms_import(Request $r) {

            Excel::import(new RoomsImport, $r->file);
            return back()->with('success', 'Data Successfully uploaded'); 

   }
}
