<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Http\Requests\MstKomoditasRequest;
use App\Models\MstKomoditasModel as Komoditas;
use App\Models\PricingModel as Pricing;

use App\Models\MstHSParentModel as HSP;

use App\Services\CustDataTables;
use App\Services\GrantedService;

class MstKomoditasController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(){
        return view('modules.mst_komoditas.index')
                ->with([
                    'hs_parent' => HSP::all(),
                    'title' => 'Komoditas',
                ]);
    }

    public function create(){
        return view('modules.mst_komoditas.create')
                ->with([
                    'title' => 'Input Data Komoditas'
                ]);
    }

    public function store(MstKomoditasRequest $request){
        Komoditas::create([
            'komoditas_nama' => $request->komoditas_nama,
            'komoditas_prefix' => $request->komoditas_prefix,
            'komoditas_spesifikasi' => $request->komoditas_spesifikasi,
            'komoditas_createdat' => date('Y-m-d H:i:s'),
            'komoditas_createdby' => session('user_id')
        ]);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function show($id){
        $data = Komoditas::find($id);
        $res = $this->delete_validation($id, $data->komoditas_nama);
        return response()->json($res,200);
    }

    public function edit($id){
        $data = Komoditas::find($id);
        return view('modules.mst_komoditas.edit')
                ->with([
                    'title' => 'Edit Data Komoditas',
                    'data' => $data
                ]);
    }

    public function update(MstKomoditasRequest $request, $id){
        $put = Komoditas::find($id);
        $put->komoditas_nama = $request->komoditas_nama;
        $put->komoditas_prefix = $request->komoditas_prefix;
        $put->komoditas_spesifikasi = $request->komoditas_spesifikasi;
        $put->save();
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Perubahan data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function destroy($id){
        $data = Komoditas::find($id);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Komoditas '.$data->komoditas_nama.' telah dihapus.',
        ];
        $data->delete();
        return response()->json($res,200);
    }
    
    public function list(CustDataTables $dtt, GrantedService $granted, Request $request){
        $kondisi = '';
        if(!empty($request->params)){
            $kondisi = " where hsp_code like '".$request->params."%'";
        }

        $colSearch = array(
            'hsp_code', 'parent', 'hsp_desc_ina'
        );
        
        $colOrder = array(
            null, 'hsp_code', 'parent', 'hsp_desc_ina'
        );

        $q = "select * from (
                select hsp_code, hsp_code parent, hsp_desc_ina, hsp_desc_eng
                  from hs_parent
                 union
                select hss_code, hss_parent_code, hss_desc_ina, hss_desc_eng
                  from hs_parent_sub
                 union
                select hsc_code, hsc_sub_code, hsc_desc_ina, hsc_desc_eng
                  from hs_child
                  left join hs_parent_sub on hss_code = hsc_sub_code
                order by hsp_code
            ) f ".$kondisi;
        $order = 'order by hsp_code asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();

        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){

            $no++;
            $dtRow = array();
            $dtRow[] = '<div class="text-center">'.$no.'</div>';
            $dtRow[] = $r->hsp_code;
            // $dtRow[] = $r->parent;
            $dtRow[] = $r->hsp_desc_ina;
            $dtRow[] = $r->hsp_desc_eng;
            $data[] = $dtRow;
        }

        $json_data = array(
            "draw"              => $request->input('draw'),
            "recordsTotal"      => $dtt->count_all(),
            "recordsFiltered"   => $dtt->count_filtered(),
            "data"              => $data,
        );

        return response()->json($json_data, 200);
    }

    public function delete_validation($id, $nama){
        $count = Pricing::where('prc_komoditas_id', $id)->count();
        if($count > 0){
            $res = [
                'msg_title' => 'Gagal',
                'msg_body' => 'Komoditas '.$nama.' tidak bisa dihapus karena memiliki '.$count.' pricing.',
                'permission' => 'F'
            ];
        }else{
            $res = [
                'msg_title' => 'Konfirmasi',
                'msg_body' => 'Apakah Anda yakin akan menghapus komoditas '.$nama.'?',
                'permission' => 'T'
            ];
        }

        return $res;
    }
}