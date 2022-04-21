<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstHSParentModel extends Model
{

    protected $table = 'hs_parent';
    protected $primaryKey = 'hsp_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'hsp_code',
        'hsp_desc_ina',
        'hsp_desc_eng',
    ];

}
