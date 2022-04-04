<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstKomoditasModel extends Model
{

    protected $table = 'mst_komoditas';
    protected $primaryKey = 'komoditas_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'komoditas_nama',
        'komoditas_prefix',
        'komoditas_spesifikasi',
        'komoditas_createdby',
        'komoditas_createdat',
    ];

}
