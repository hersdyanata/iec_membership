<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstWilayahModel extends Model
{

    protected $table = 'mst_wilayah';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
    ];

}
