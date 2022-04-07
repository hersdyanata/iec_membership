<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstRegionalModel extends Model
{

    protected $table = 'mst_regional';
    protected $primaryKey = 'reg_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'reg_nama',
        'reg_deskripsi',
        'reg_url_tele',
    ];

}
