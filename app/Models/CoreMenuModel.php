<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreMenuModel extends Model
{
    use HasFactory;

    protected $table = 'dev_menu';
    protected $primaryKey = 'menu_id';
    public $timestamps = false;

    const CREATED_AT = 'menu_createdat';

    protected $fillable = [
        'menu_div_id',
        'menu_parent_id',
        'menu_nama_ina',
        'menu_nama_eng',
        'menu_controller',
        'menu_route_name',
        'menu_folder_view',
        'menu_icon',
        'menu_publish_ke_user',
        'menu_order',
        'menu_level',
        'menu_layanan_id',
        'menu_lokasi',
        'menu_user_group',
        'menu_createdat',
        'menu_createdby',
    ];
}
