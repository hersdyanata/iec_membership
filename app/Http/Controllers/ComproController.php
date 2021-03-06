<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Models\MstHSParentModel as HSParent;
use App\Models\MstHSParentSubModel as HSSubParent;
use App\Models\MstHSChildModel as HSChild;

use App\Services\CustDataTables;
use App\Services\GrantedService;

class ComproController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(){
        return view('modules.compro.index')
                ->with([
                    'title' => 'Company Profile',
                    'hsp' => HSParent::all(),
                    // 'hss' => HSSubParent::all(),
                    // 'hsc' => HSChild::all()
                ]);
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show($id){
        //
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        //
    }

    public function destroy($id){
        //
    }
    
    public function list(CustDataTables $dtt, GrantedService $granted, Request $request){
        $colSearch = array(
            'fakultas_kode', 'fakultas_nama', 'fakultas_nama_eng', 'prodi_nama', 'prodi_nama_eng', 'prodi_kode'
        );
        
        $colOrder = array(
            null, 'fakultas_kode', 'fakultas_nama', 'fakultas_nama_eng', 'prodi_nama', 'prodi_nama_eng', 'prodi_kode', null
        );

        $q = "select * from mst_prodi left join mst_fakultas on fakultas_id = prodi_fakultas_id";
        $order = 'order by fakultas_kode asc';
        
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
            $dtRow[] = $r->fakultas_kode.' - '.$r->fakultas_nama;
            $dtRow[] = $r->prodi_kode.' - '.$r->prodi_nama;
            $dtRow[] = $r->prodi_nama_eng;
            $dtRow[] = '<div class="text-center">'.$r->prodi_akreditasi.'</div>';
            $dtRow[] = '<div class="text-center">'.$granted->action_buttons('masterdata.mst_prodi.edit', $r->prodi_id, $request->page_permission).'</div>';
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
}