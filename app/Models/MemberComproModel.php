<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberComproModel extends Model
{

    protected $table = 'member_compro';
    protected $primaryKey = 'comp_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'comp_user_id',
        'comp_nama_perusahaan',
        'comp_tempat_berdiri',
        'comp_tanggal_berdiri',
        'comp_provinsi',
        'comp_kota',
        'comp_kecamatan',
        'comp_kelurahan',
        'comp_alamat',
        'comp_kodepos',
        'comp_komoditas',
        'comp_file_profile',
        'comp_file_katalog',
        'comp_status',
        'comp_pic',
        'comp_wa',
        'comp_website',
    ];

}
