<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class houseleader extends Model
{
    use HasFactory;
    protected $fillable = [
        'muncit',
        'barangay',
        'houseleader',
        'remarks',
        'purok',
        'sqn',
    ];
}
