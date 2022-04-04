<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstSupplierModel extends Model
{

    protected $table = 'mst_supplier';
    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'supplier_nama',
        'supplier_alamat',
        'supplier_komoditas',
        'supplier_createdat',
        'supplier_createdby',
    ];

}
