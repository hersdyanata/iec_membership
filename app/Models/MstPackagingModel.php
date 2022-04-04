<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MstPackagingModel extends Model
{

    protected $table = 'mst_packaging';
    protected $primaryKey = 'pack_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'pack_id',
        'pack_label',
    ];

}
