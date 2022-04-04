<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstBuyerModel extends Model
{

    protected $table = 'mst_buyer';
    protected $primaryKey = 'buyer_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'buyer_perusahaan',
        'buyer_pic',
        'buyer_no_hp',
        'buyer_negara',
        'buyer_alamat',
        'buyer_createdat',
        'buyer_createdby'
    ];

}
