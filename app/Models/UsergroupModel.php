<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsergroupModel extends Model
{
    protected $table = 'usergroup';
    protected $primaryKey = 'group_id';
    public $timestamps = false;

    protected $fillable = [
        'group_nama',
        'group_deskripsi',
        'group_menu_permission',
        'group_default_menu'
    ];
}
