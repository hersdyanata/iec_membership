<?php

namespace App\Services;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\PricingModel as Pricing;
use App\Models\PricingFinalModel as PricingFinal;
use App\Models\PricingPackagingModel as PricingPack;
use App\Models\MstKomoditasModel as Komoditas;

class PricingService{

    public function generate_kode_pricing($komoditas_id){
        $master = Komoditas::where('komoditas_id', $komoditas_id)->first();
        $get_max = Pricing::where('prc_kode', 'like', $master->komoditas_prefix.'%')->select(DB::raw('max(prc_kode) as kode'))->first();
        $right_code = explode('-', $get_max->kode);
        if($get_max->kode == null){
            $kode_pricing = 1;
        }else{
            $kode_pricing = $right_code[1]+1;
        }

        return $master->komoditas_prefix.'-'.$kode_pricing;
    }

    public function cetak_buyer($pricing_id, $terms){
        $pricing = Pricing::leftJoin('mst_komoditas', 'komoditas_id', 'prc_komoditas_id')
                    ->leftJoin('pricing_final', 'prdetail_prc_id', 'prc_id')
                    ->where('prdetail_prc_incoterms', $terms)
                    ->whereIn('prc_id', $pricing_id)
                    ->orderBy('prc_kode', 'asc')
                    ->get();
        $packing = PricingPack::leftJoin('mst_packaging', 'mst_packaging.pack_id', 'pricing_packaging.pack_id')->where('pack_prc_id', $pricing_id)->get();
        
        return $data = [
            'pricing' => $pricing,
            'packing' => $packing
        ];
    }

}