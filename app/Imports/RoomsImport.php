<?php

namespace App\Imports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class RoomsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Room([
            'brCode' => $row['brcode'],
            'floorRef' => $row['floorref'],
            'floor' => $row['floor'],
            'roomNo' => $row['roomno'],
            'roomStatus' => $row['roomstatus'],
            'roomType' => $row['roomtype'],
            'no_beds' => $row['no_beds'],
            'remarks' => $row['remarks'],
            'created_by' => $row['created_by'],
            'updated_by' => $row['updated_by'],
            'created_date' => $row['created_date'],
            'updated_date' => $row['updated_date'],
        ]);
    }
}
