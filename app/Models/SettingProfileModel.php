<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingProfileModel extends Model
{
    protected $table = 'setting_profile';
    protected $primaryKey = false;
    public $timestamps = false;

    protected $fillable = [
        'tbl_prefix',
        'tbl_nama_kolom',
        'tbl_kolom_alias',
        'tbl_required_flag'
    ];
}
