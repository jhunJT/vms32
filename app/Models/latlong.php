<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class latlong extends Model
{
    use HasFactory;
    protected $fillable = [
        'district',
        'muncit',
        'barangay',
        'latitude',
        'longitude',
        'remarks',
    ];
}
