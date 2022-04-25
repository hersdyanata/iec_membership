<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPelabuhanModel extends Model
{
    protected $table = 'mst_negara_pelabuhan';
    protected $primaryKey = 'port_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
            'port_negara_kode',
            'port_kode_tujuan',
            'port_nama',
    ];
}



