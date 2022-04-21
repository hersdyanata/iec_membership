<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstHSParentSubModel extends Model
{

    protected $table = 'hs_parent_sub';
    protected $primaryKey = 'hss_code';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'hss_code',
        'hss_desc_ina',
        'hss_desc_eng',
    ];

}
