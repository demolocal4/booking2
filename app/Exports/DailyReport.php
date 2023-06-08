<?php



namespace App\Exports;



use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Support\Facades\DB;

class DailyReport implements FromCollection,WithHeadings

{

    protected $id,$fdate,$tdate;

    function __construct($id,$fdate)

    {

        $this->id = $id;

        $this->fdate = $fdate;

        // $this->tdate = $tdate;

    }

    public function headings():array{



        return[

            'Date',

            'Customer Name',

            'Room No',

            'Booking Ref',

            'Cash In',

            'Cash Out',

            'Balance',

        ];



    }

    /**

    * @return \Illuminate\Support\Collection

    */

    public function collection()

    {

    		// AND Date <= '".$this->tdate."'

            return collect(

                DB::select("SELECT Date,customer_name,room_no,refId,creditAmount, debitAmount,

                cast((@Balance := @Balance + creditAmount - debitAmount) as DECIMAL(16,2)) as Balance

                FROM daily_report

                JOIN (SELECT @Balance :=0) as tmp

                WHERE brCode = '".$this->id."' AND description != 'Balance Amount' AND Date = '".$this->fdate."' AND booking_com_ref = 0 

                ORDER BY date asc, id asc")

            );

    }

}

