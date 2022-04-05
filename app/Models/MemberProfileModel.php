<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfileModel extends Model
{

    protected $table = 'member_profile';
    protected $primaryKey = 'profile_user_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'profile_user_id',
        'profile_gender',
        'profile_tempat_lahir',
        'profile_tanggal_lahir',
        'profile_alamat',
        'profile_provinsi',
        'profile_kota',
        'profile_kecamatan',
        'profile_kelurahan',
        'profile_regional',
        'profile_tele_id',
        'profile_wa',
        'profile_member_type',
    ];

}
