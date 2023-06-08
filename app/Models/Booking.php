<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Booking extends Model

{

    use HasFactory;

    protected $table = 'booking';

    protected $guarded = [];

    public $timestamps = false;





    public function branch() {

        return $this->belongsTo(Branch::class, 'brCode');

    }


    protected $casts = [
        'payout_date' => 'date:Y-m-d',
    ];

}

