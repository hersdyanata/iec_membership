<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Http\Requests\MstSupplierRequest;

use App\Models\MstKomoditasModel as Komoditas;
use App\Models\MstSupplierModel as Supplier;
use App\Models\PricingModel as Pricing;


use App\Services\CustDataTables;
use App\Services\GrantedService;

class MstSupplierController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(){
        return view('modules.mst_supplier.index')
                ->with([
                    'title' => 'Supplier',
                ]);
    }

    public function create(){
        return view('modules.mst_supplier.create')
                ->with([
                    'title' => 'Input Data Supplier',
                    'produk' => Komoditas::all()
                ]);
    }

    public function store(MstSupplierRequest $request){
        $data = [
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
            'supplier_komoditas' => json_encode($request->supplier_komoditas),
            'supplier_createdat' => date('Y-m-d H:i:s'),
            'supplier_createdby' => session('user_id'),
        ];
        Supplier::create($data);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function show($id){
        $data = Supplier::find($id);
        $res = $this->delete_validation($id, $data->supplier_nama);
        return response()->json($res, 200);
    }

    public function edit($id){
        $dt = Supplier::find($id);
        $komoditas = Komoditas::all();
        $json_produk = json_decode($dt->supplier_komoditas);

        return view('modules.mst_supplier.edit')
                ->with([
                    'title' => 'Edit Data Supplier',
                    'data' => $dt,
                    'produk' => $komoditas,
                    'selected_produk' => $komoditas->whereIn('komoditas_id', $json_produk)->pluck('komoditas_id')->toArray()
                ]);
    }

    public function update(MstSupplierRequest $request, $id){
        $put = Supplier::find($id);
        $put->supplier_nama = $request->supplier_nama;
        $put->supplier_alamat = $request->supplier_alamat;
        $put->supplier_komoditas = json_encode($request->supplier_komoditas);
        $put->save();
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Perubahan data berhasil disimpan.',
        ];
        return response()->json($res, 200);
    }

    public function destroy($id){
        $data = Supplier::find($id);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Supplier '.$data->supplier_nama.' telah dihapus.',
        ];
        $data->delete();
        return response()->json($res,200);
    }
    
    public function list(CustDataTables $dtt, GrantedService $granted, Request $request){
        $colSearch = array(
            'supplier_nama', 'supplier_alamat',
        );
        
        $colOrder = array(
            null, 'supplier_nama', 'supplier_alamat',
        );

        $q = "select * from mst_supplier where supplier_createdby = ".session('user_id');
        $order = 'order by supplier_nama asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();

        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){
            $json_produk = json_decode($r->supplier_komoditas);
            $col = Arr::flatten($json_produk);
            $komoditas = Komoditas::whereIn('komoditas_id', $col)->orderBy('komoditas_nama')->get();

            $no++;
            $dtRow = array();
            $dtRow[] = '<div class="text-center">'.$no.'</div>';
            $dtRow[] = $r->supplier_nama;
            $dtRow[] = $r->supplier_alamat;
            $dtRow[] = view('modules.mst_supplier.komoditas')->with(['produk' => $komoditas])->render();
            $dtRow[] = '<div class="text-center">'.$granted->action_buttons('masterdata.mst_supplier.edit', $r->supplier_id, $request->page_permission).'</div>';
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
        $count = Pricing::where('prc_supplier_id', $id)->count();
        if($count > 0){
            $res = [
                'msg_title' => 'Gagal',
                'msg_body' => 'Supplier '.$nama.' tidak bisa dihapus karena memiliki '.$count.' pricing.',
                'permission' => 'F'
            ];
        }else{
            $res = [
                'msg_title' => 'Konfirmasi',
                'msg_body' => 'Apakah Anda yakin akan menghapus supplier '.$nama.'?',
                'permission' => 'T'
            ];
        }

        return $res;
    }
}