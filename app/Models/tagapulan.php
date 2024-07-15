<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagapulan extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name',
        'District',
        'Province',
        'Municipality',
        'Barangay',
        'Precinct_no',
        '_Orig_num',
        'SIP',
        'occupation',
        'purok_rv',
        'survey_stat',
        'HL',
        'PL',
        'goffice',
        'gcoordinator',
        'man_add',
        'grant_rv',
        'gdate',
        'gamount',
        'gremarks',
        'remarks',
        'vstatus',
        'contact_no',
        'dob',
        'hlids',
        'plids',
        'userid',
        'user',
        'userlogs',
        'pop',
        'sqn',
    ];
}

