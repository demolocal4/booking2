<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypes extends Model
{
    use HasFactory;
    protected $table = 'roomtypes';
    protected $guarded = [];
    public $timestamps = false;

    public function branch() {
        return $this->belongsTo(Branch::class, 'brCode');
    }
    public function roomtype() {
        return $this->belongsTo(RoomType::class, 'roomtype_id');
    }
    
    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
