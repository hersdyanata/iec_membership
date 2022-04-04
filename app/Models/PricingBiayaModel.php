<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingBiayaModel extends Model
{

    protected $table = 'pricing_biaya';
    protected $primaryKey = false;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'prkomp_prc_id',
        'prkomp_komponen_id',
        'prkomp_komponen_type',
        'prkomp_incoterms',
        'prkomp_persentase',
        'prkomp_nilai_fixed_idr',
        'prkomp_nilai_fixed_usd',
    ];

}
