<?php

namespace App\Exports;
use App\Models\Booking;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingStatement implements FromView,ShouldAutoSize  //FromCollection //,WithHeadings,Responsable
{
    use Exportable;
    private $fileName = "statement.xlxs"; 
    // protected $id,$fdate,$tdate;
    // function __construct($id,$fdate,$tdate)
    // {
    //     $this->id = $id;
    //     $this->fdate = $fdate;
    //     $this->tdate = $tdate;
    // }
    
    // public function headings():array{

    //     return[
    //         'Booking ID',
    //         'Booking.com Ref-ID',
    //         'Branch Name',
    //         'Total Amount',
    //         'Commission',
    //         'Payment Charges',
    //         'Online Vat',
    //         'Payout ID'
    //     ];

    // }
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Booking::with('branch')->whereNotNull('booking_com_ref')
    //     ->select('id','booking_com_ref','total_amount','commission','payment_charge','vat_online','payout_id')
    //     ->get();
     
        
    // }

    public function view(): View
    {
        $allbookings = Booking::with('branch')->where('booking_com_ref','!=', 0)->get();
        return view('print.statement',compact('allbookings'));
    }
}
