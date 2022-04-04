<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingFinalModel extends Model
{

    protected $table = 'pricing_final';
    protected $primaryKey = false;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'prdetail_prc_id',
        'prdetail_prc_incoterms',
        'prdetail_keseluruhan_idr',
        'prdetail_keseluruhan_usd',
        'prdetail_ton_idr',
        'prdetail_ton_usd',
        'prdetail_kilo_idr',
        'prdetail_kilo_usd',
    ];

}
