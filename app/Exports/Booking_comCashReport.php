<?php

namespace App\Exports;
use App\Models\PaymentReceipt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Controllers\Reports;
use Illuminate\Support\Facades\DB;

class Booking_comCashReport implements FromCollection,WithHeadings 
{
    protected $id,$fdate,$tdate;
    function __construct($id,$fdate,$tdate)
    {
        $this->id = $id;
        $this->fdate = $fdate;
        $this->tdate = $tdate;
    }
    public function headings():array{

        return[
            'Date',
            'Cash In',
            'Cash Out',
            'Balance'
        ];

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return PaymentReceipt::all();

        //return collect(Reports::export_payment());
        return collect(
        DB::select("SELECT STR_TO_DATE(created_at,'%Y-%m-%d') as date, creditAmount, debitAmount, 
        cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance
        FROM payment_receipt
        JOIN (SELECT @Balance :=0) as tmp
        WHERE description != 'Balance Amount' AND brCode = '".$this->id."' AND STR_TO_DATE(created_at,'%Y-%m-%d') >= '".$this->fdate."' AND STR_TO_DATE(created_at,'%Y-%m-%d') <= '".$this->tdate."' AND booking_com_ref != 0
        ORDER BY date asc, id asc")
        );
        
    }
}
