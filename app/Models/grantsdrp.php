<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grantsdrp extends Model
{
    use HasFactory;
    protected $fillable = [
        'grant_type',
        'date_of_grant',
        'grant_amount',
        'g_remarks',
        'grant_agency',
        'grant_title',
        'grant_muncit'
    ];
}
