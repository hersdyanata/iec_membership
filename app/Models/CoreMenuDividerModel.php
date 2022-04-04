<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreMenuDividerModel extends Model
{
    protected $table = 'dev_menu_divider';
    protected $primaryKey = 'div_id';
    public $timestamps = false;

    protected $fillable = [
        'div_nama_ina',
        'div_nama_eng',
        'div_order',
        'div_publish_ke_user'
    ];
}
