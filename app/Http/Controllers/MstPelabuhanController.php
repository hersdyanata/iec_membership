<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Http\Requests\MstPelabuhanRequest;

use App\Models\MstPelabuhanModel as Pelabuhan;  
use App\Models\MstNegaraModel as Negara;  

use App\Services\CustDataTables;
use App\Services\GrantedService;

class MstPelabuhanController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(){
        return view('modules.mst_pelabuhan.index')
                ->with([
                    'negara' => Negara::all(),
                    'title' => 'Pelabuhan',
                ]);
    }

    public function create(){
        return view('modules.mst_pelabuhan.create')
                ->with([
                    'title' => 'Input Data Pelabuhan',
                    'negara' => Negara::all(),
                ]);
    }

    public function store(MstPelabuhanRequest $request){
        $data = [
            'port_negara_kode' => $request->port_negara_kode,
            'port_kode_tujuan' => $request->port_kode_tujuan,
            'port_nama' => $request->port_nama, 
        ];
        Pelabuhan::create($data);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function edit($id){
         
    }

    // public function show($id){
    //     $data = Pelabuhan::find($id);
    //     $res = $this->delete_validation($id, $data->port_nama);
    //     return response()->json($res,200);
    // }
    
    public function list(CustDataTables $dtt, GrantedService $granted, Request $request){
        $kondisi = '';
        if(!empty($request->params)){
            $kondisi = " where port_negara_kode like '".$request->params."%'";
        }

        $colSearch = array(
            'port_negara_kode', 'port_kode_tujuan', 'port_nama'
        );
        
        $colOrder = array(
            null, 'port_negara_kode', 'port_kode_tujuan', 'port_nama'
        );
 
        $q = "select * from mst_negara_pelabuhan ".$kondisi;
        $order = 'order by port_negara_kode asc';
        
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
            $dtRow[] = $r->port_negara_kode;
            $dtRow[] = $r->port_kode_tujuan; 
            $dtRow[] = $r->port_nama; 
            $dtRow[] = '<div class="text-center">'.$granted->action_buttons('masterdata.mst_pelabuhan.edit', $r->port_kode_tujuan, $request->page_permission).'</div>';
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