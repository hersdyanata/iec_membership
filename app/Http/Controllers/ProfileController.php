<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;
use Auth;

use App\Services\CustDataTables;
use App\Services\GrantedService;
use App\Services\Mst_WilayahService;
use App\Services\MemberService;

use App\Models\MstRegionalModel as Regional;
use App\Models\MemberTypeModel as Memtype;
use App\Models\MemberProfileModel as Profile;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(Mst_WilayahService $wilayah, MemberService $member){
        return view('modules.profile.index')
                ->with([
                    'title' => 'Profile',
                    'profile' => $member->status_profile(Auth::user()->id),
                    'provinsi' => $wilayah->provinsi(),
                    'regionals' => Regional::all(),
                    'member_type' => Memtype::all()
                ]);
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show(MemberService $member, $id){
        $res = $member->status_profile($id);
        return response()->json($res, 200);
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $put = Profile::find($id);
        $put->profile_gender = $request->profile_gender;
        $put->profile_no_anggota = $request->profile_no_anggota;
        $put->profile_tempat_lahir = $request->profile_tempat_lahir;
        $put->profile_tanggal_lahir = date('Y-m-d', strtotime($request->profile_tanggal_lahir));
        $put->profile_alamat = $request->profile_alamat;
        $put->profile_provinsi = $request->profile_provinsi;
        $put->profile_kota = $request->profile_kota;
        $put->profile_kecamatan = $request->profile_kecamatan;
        $put->profile_kelurahan = $request->profile_kelurahan;
        $put->profile_kodepos = $request->profile_kodepos;
        $put->profile_regional = $request->profile_regional;
        $put->profile_tele_id = $request->profile_tele_id;
        $put->profile_wa = $request->profile_wa;
        $put->profile_member_type = $request->profile_member_type;
        $put->profile_updatedat = date('Y-m-d H:i:s');
        $put->save();

        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Perubahan biodata telah disimpan'
        ];

        return response()->json($res, 200);
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