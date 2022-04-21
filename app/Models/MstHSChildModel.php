<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstHSChildModel extends Model
{

    protected $table = 'hs_child';
    protected $primaryKey = 'hsc_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'hsc_code',
        'hsc_desc_ina',
        'hsc_desc_eng',
    ];

}
