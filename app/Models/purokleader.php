<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purokleader extends Model
{
    use HasFactory;
    protected $fillable = [
        'muncit',
        'barangay',
        'purok_leader',
        'purok',
        'mid',
        'remarks',
    ];
}
