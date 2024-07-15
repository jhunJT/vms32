<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class d1nle2023 extends Model
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
        'remarks',
        'HL',
        'PL',
        'goffice',
        'gcoordinator',
        'man_add',
        'grant_rv',
        'gdate',
        'gamount',
        'gremarks',
        'vstatus',
        'contact_no',
        'dob',
        'hlids',
        'plids',
        'userid',
        'user',
        'userlogs',
        'pop',
        'sethl',
        'coord_lat',
        'coord_long',
        'qrcode_id',
        'isScannedQrCodeId',
        'isSent',
        'isToBeUploaded',
        'sqn'
    ];
    protected $casts = [
        'Name' => 'string',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['Name'] = strtoupper($value);
    }

}
