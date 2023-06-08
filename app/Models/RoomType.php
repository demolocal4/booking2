<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'roomtype';
    public $timestamps = false;

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id', 'brCode');
    }

}
