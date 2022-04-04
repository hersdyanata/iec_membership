<?php

namespace App\Services;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\MstWilayahModel as Wilayah;

class Mst_WilayahService{

    function provinsi(){
        return Wilayah::whereRaw('length(kode) = 2')->orderBy('nama')->get();
    }

    function kotakab($provinsi){
        return Wilayah::whereRaw("length(kode) = 5 and kode like '".$provinsi."%'")->orderBy('nama')->get();
    }

    function kecamatan($kotakab){
        return Wilayah::whereRaw("length(kode) = 8 and kode like '".$kotakab."%'")->orderby('nama')->get();
    }

    function kelurahan($kecamatan){
        return Wilayah::whereRaw("length(kode) = 13 and kode like '".$kecamatan."%'")->orderby('nama')->get();
    }
}