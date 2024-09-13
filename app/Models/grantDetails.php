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
        'grant_type',
        'grant_agency',
        'date',
        'amount',
        'remarks',
        'vid',
        'uid',
    ];
}
