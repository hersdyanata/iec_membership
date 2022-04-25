<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstNegaraModel extends Model
{
    protected $table = 'mst_negara';
    protected $primaryKey = 'negara_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
            'negara_kode',
            'negara_nama', 
    ];
} 