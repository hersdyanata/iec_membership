<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Http\Requests\MstBuyerRequest;
use App\Models\MstBuyerModel as Buyer;
use App\Services\CustDataTables;
use App\Services\GrantedService;

class MstBuyerController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted']);
    }

    public function index(){
        return view('modules.mst_buyer.index')
                ->with([
                    'title' => 'Buyer',
                ]);
    }

    public function create(){
        return view('modules.mst_buyer.create')
                ->with([
                    'title' => 'Input Data Buyer',
                ]);
    }

    public function store(MstBuyerRequest $request){
        Buyer::create($request->all());
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function show($id){
        $data = Buyer::find($id);
        $res = $this->delete_validation($id, $data->buyer_nama);
        return response()->json($res, 200);
    }

    public function edit($id){
        return view('modules.mst_buyer.edit')
                ->with([
                    'title' => 'Edit Data Supplier',
                    'data' => Buyer::find($id),
                ]);
    }

    public function update(MstBuyerRequest $request, $id){
        $put = Buyer::find($id);
        $put->buyer_perusahaan = $request->buyer_perusahaan;
        $put->buyer_pic = $request->buyer_pic;
        $put->buyer_no_hp = $request->buyer_no_hp;
        $put->buyer_alamat = $request->buyer_alamat;
        $put->buyer_negara = $request->buyer_negara;
        $put->save();
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Perubahan data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function destroy($id){
        $data = Buyer::find($id);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Buyer '.$data->buyer_nama.' telah dihapus.',
        ];
        $data->delete();
        return response()->json($res,200);
    }
    
    public function list(CustDataTables $dtt, GrantedService $granted, Request $request){
        $colSearch = array(
            'buyer_perusahaan', 'buyer_pic', 'buyer_no_hp', 'buyer_alamat', 'buyer_negara',
        );
        
        $colOrder = array(
            null, 'buyer_perusahaan', 'buyer_pic', 'buyer_no_hp', 'buyer_alamat', 'buyer_negara', null
        );

        $q = "select * from mst_buyer";
        $order = 'order by buyer_perusahaan asc';
        
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
            $dtRow[] = $r->buyer_perusahaan;
            $dtRow[] = $r->buyer_pic;
            $dtRow[] = $r->buyer_no_hp;
            $dtRow[] = $r->buyer_alamat;
            $dtRow[] = $r->buyer_negara;
            $dtRow[] = '<div class="text-center">'.$granted->action_buttons('masterdata.mst_buyer.edit', $r->buyer_id, $request->page_permission).'</div>';
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
        // $count = Supplier::where('supplier_id', $id)->count();
        // if($count > 0){
        //     $res = [
        //         'msg_title' => 'Gagal',
        //         'msg_body' => 'Supplier '.$nama.' tidak bisa dihapus karena memiliki '.$count.' pricing.',
        //         'permission' => 'F'
        //     ];
        // }else{
            $res = [
                'msg_title' => 'Konfirmasi',
                'msg_body' => 'Apakah Anda yakin akan menghapus buyer '.$nama.'?',
                'permission' => 'T'
            ];
        // }

        return $res;
    }
}