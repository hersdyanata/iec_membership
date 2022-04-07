<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;
use App\Services\Mst_WilayahService;

class Mst_WilayahController extends Controller
{
    public function __construct(){
        $this->middleware(['auth']);
    }
    
    function get_provinsi(Mst_WilayahService $wil){
        $data = $wil->provinsi();

        $opts = '<option value="">-- PILIH PROVINSI --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->kode.'">'.strtoupper($r->nama).'</option>';
        }
        return response()->json($opts, 200);
    }

    function get_kota(Mst_WilayahService $wil, $provinsi){
        $data = $wil->kotakab($provinsi);

        $opts = '<option value="">-- PILIH KOTA --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->kode.'">'.strtoupper($r->nama).'</option>';
        }
        return response()->json($opts, 200);
    }

    function get_kecamatan(Mst_WilayahService $wil, $kota){
        $data = $wil->kecamatan($kota);

        $opts = '<option value="">-- PILIH KECAMATAN --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->kode.'">'.strtoupper($r->nama).'</option>';
        }
        return response()->json($opts, 200);
    }

    function get_kelurahan(Mst_WilayahService $wil, $kecamatan){
        $data = $wil->kelurahan($kecamatan);

        $opts = '<option value="">-- PILIH KELURAHAN --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->kode.'">'.strtoupper($r->nama).'</option>';
        }
        return response()->json($opts, 200);
    }


}
