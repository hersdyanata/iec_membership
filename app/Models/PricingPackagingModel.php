<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPackagingModel extends Model
{

    protected $table = 'pricing_packaging';
    protected $primaryKey = false;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'pack_prc_id',
        'pack_id',
        'pack_size',
        'pack_harga',
        'pack_unit',
        'pack_qty',
        'pack_cost_idr',
        'pack_cost_usd',
    ];

}
