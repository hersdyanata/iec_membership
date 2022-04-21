<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Models\MstHSParentModel as HSP;
use App\Models\MstHSParentSubModel as HSS;
use App\Models\MstHSChildModel as HSC;

class Mst_HSController extends Controller
{
    public function __construct(){
        $this->middleware(['auth']);
    }
    
    function get_parent(){
        $data = HSP::all();

        $opts = '<option value="">-- Pilih HS Code Induk --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->hsp_code.'">'.$r->hsp_code.' - '.strtoupper($r->hsp_desc_ina).'</option>';
        }
        return response()->json($opts, 200);
    }

    function get_sub($parent){
        $data = HSS::where('hss_parent_code', $parent)->get();

        $opts = '<option value="">-- Pilih Sub HS Code --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->hss_code.'">'.$r->hss_code.' - '.strtoupper($r->hss_desc_ina).'</option>';
        }
        return response()->json($opts, 200);
    }

    function get_hscode($parent){
        $data = HSC::where('hsc_sub_code', $parent)->get();

        $opts = '<option value="">-- Pilih HS Code --</option>';
        foreach($data as $r){
            $opts .= '<option value="'.$r->hsc_code.'">'.$r->hsc_code.' - '.strtoupper($r->hsc_desc_ina).'</option>';
        }
        return response()->json($opts, 200);
    }

}
