<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class flexincomeModel extends Model
{
    use HasFactory;
    protected $table = 'flexible_income';
    protected $guarded = [];
    public $timestamps = false;
}
