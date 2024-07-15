<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grantDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'district',
        'muncit',
        'barangay',
        'grant',
        'office',
        'coordinator',
        'date',
        'amount',
        'remarks',
        'vid',
        'uid',
    ];
}
