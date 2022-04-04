<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstKomponenBiayaModel extends Model
{

    protected $table = 'mst_komponen_biaya';
    protected $primaryKey = 'komp_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'komp_nama',
        'komp_type'
    ];

}
