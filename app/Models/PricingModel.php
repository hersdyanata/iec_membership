<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingModel extends Model
{

    protected $table = 'pricing';
    protected $primaryKey = 'prc_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'prc_kode',
        'prc_label',
        'prc_supplier_id',
        'prc_komoditas_id',
        'prc_harga_supplier',
        'prc_kurs',
        'prc_container_size',
        'prc_container_max_qty',
        'prc_container_qty',
        'prc_total_qty',
        'prc_margin_persentase',
        'prc_margin_idr',
        'prc_cost_produk',
        'prc_profit',
        'prc_createdat',
        'prc_createdby',
        'prc_updatedat',
        'prc_updatedby',
    ];

}
