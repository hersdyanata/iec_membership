<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTypeModel extends Model
{

    protected $table = 'member_type';
    protected $primaryKey = 'type_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'type_nama',
    ];

}
