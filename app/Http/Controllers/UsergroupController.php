<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use App\Services\Core;
use App\Services\CustDataTables;
use App\Services\GrantedService;
use App\Models\UsergroupModel as Usergroup;
use App\Http\Requests\UsergroupRequest;

class UsergroupController extends Controller
{
    public $core;
    public $privileges;
    public function __construct(){
        $this->middleware(['auth', 'granted', 'verified']);
    }
    
    public function index(Request $request){
        $title = 'Group Pengguna';
        return view('modules.usergroup.index')
                ->with([
                    'title' => $title,
                ]);
    }

    public function action_buttons($route, $param, $page_permission){
        $privs = $page_permission;
        $btn = '';
        $btn_edit = '<a href="'.route($route, $param).'" class="btn btn-success btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> ';
        
        $btn_delete = '<button class="btn btn-danger btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" onclick="preaction('.$param.')"><i class="icon-trash"></i></button>';

        if(session('group_id') != 0){
            if( in_array('update', $privs) ){
                $btn .= $btn_edit;
            }
    
            if( in_array('delete', $privs) ){
                $btn .= $btn_delete;
            }
        }else{
            $btn = $btn_edit.$btn_delete;
        }

        return $btn;
    }

    public function list_usergroup(CustDataTables $dtt, GrantedService $granted, Request $request){
        $colSearch = array(
            'group_nama', 'group_default_menu', 'group_deskripsi',
        );
        
        $colOrder = array(
            null, 'group_nama', 'group_default_menu', 'group_deskripsi', null, null
        );

        $q = "SELECT * from usergroup left join dev_menu on menu_id = group_default_menu";
        $order = 'order by group_id asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();

        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){
            $permission = $granted->get_accessible_menu($r->group_menu_permission);

            $no++;
            $dtRow = array();
            $dtRow[] = '<div class="text-center">'.$no.'</div>';
            $dtRow[] = $r->group_nama;
            $dtRow[] = $r->menu_nama_ina;
            $dtRow[] = $r->group_deskripsi;
            $dtRow[] = view('modules.usergroup.table_permissions')->with(['menus' => $permission['menus']])->render();
            $dtRow[] = '<div class="text-center">'.$this->action_buttons('usergroup.edit', $r->group_id, $request->page_permission).'</div>';
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

    public function create(GrantedService $granted){
        $title = 'Buat Usergroup Baru';
        return view('modules.usergroup.create')
                ->with([
                    'title' => $title,
                    'list_menu' => $granted->list_menu_and_permission()
                ]);
    }

    public function store(UsergroupRequest $request){
        $arr_menus = array();
        if(isset($request->group_menu_permission)){
            foreach($request->group_menu_permission as $menu => $state){
                $arr_menus[$menu] = $menu;
            }
        }

        if(Arr::exists($arr_menus, $request->group_default_menu)){
            $validate_default_menu = true;
        }else{
            $validate_default_menu = false;
        }

        if($validate_default_menu == true){
            Usergroup::create([
                'group_nama' => $request->group_nama,
                'group_deskripsi' => $request->group_deskripsi,
                'group_default_menu' => $request->group_default_menu,
                'group_menu_permission' => $this->jsonfy_menu_permission($request->group_menu_permission, $request->permissions)
            ]);

            $res = [
                'msg_title' => 'Berhasil',
                'msg_body' => 'Data berhasil disimpan.',
            ];    
            return response()->json($res, 200);
        }else{
            $res = [
                'msg_title' => 'Gagal',
                'msg_body' => json_encode(['Default menu yang dipilih tidak termasuk pada menu yang di-grant'])
            ];
            return response()->json($res, 500);
        }
    }

    public function jsonfy_menu_permission($menu_permission, $permissions){
        $menu = array();
        $raw_permission = array();
        if(isset($menu_permission)){
            if(isset($permissions)){
                foreach($permissions as $ic => $ff){
                    $raw_permission[] = [
                        'menu_id' => $ic,
                        'perm' => $ff
                    ];
                }
            }
    
            $coll_permission = collect($raw_permission);
            foreach($menu_permission as $i_menu => $r){
                $perms[$i_menu] = array();
                foreach($coll_permission->where('menu_id', $i_menu)->pluck('perm')->toArray() as $i_permission => $p){
                    foreach($p as $ip => $pr){
                        $perms[$i_menu][] = $ip;
                    }
                }
    
                $menu[] = [
                    'menu_id' => $i_menu,
                    'permissions' => $perms[$i_menu]
                ];
            }
        }

        return json_encode($menu);
    }

    public function edit($id, GrantedService $granted){
        $title = 'Edit Usergroup Baru';
        return view('modules.usergroup.edit')
                ->with([
                    'title' => $title,
                    'owned' => Usergroup::find($id),
                    'list_menu' => $granted->list_menu_and_permission()
                ]);
    }

    public function update(UsergroupRequest $request, $id){
        $arr_menus = array();
        if(isset($request->group_menu_permission)){
            foreach($request->group_menu_permission as $menu => $state){
                $arr_menus[$menu] = $menu;
            }
        }
        
        if(Arr::exists($arr_menus, $request->group_default_menu)){
            $validate_default_menu = true;
        }else{
            $validate_default_menu = false;
        }

        if($validate_default_menu == true){
            $put = Usergroup::find($id);
            $put->group_nama = $request->group_nama;
            $put->group_deskripsi = $request->group_deskripsi;
            $put->group_default_menu = $request->group_default_menu;
            $put->group_menu_permission = $this->jsonfy_menu_permission($request->group_menu_permission, $request->permissions);
            $put->save();
    
            $res = [
                'msg_title' => 'Berhasil',
                'msg_body' => 'Data berhasil disimpan.',
            ];
    
            return response()->json($res, 200);
        }else{
            $res = [
                'msg_title' => 'Gagal',
                'msg_body' => json_encode(['Default menu yang dipilih tidak termasuk pada menu yang di-grant'])
            ];
            return response()->json($res, 500);
        }
    }

    public function predelete(Request $request){
        $id = $request->id;
        $data = Usergroup::find($id);

        return response()->json([
            'msg_title' => 'Konfirmasi',
            'msg_body' => 'Anda akan menghapus usergroup '.$data->group_nama.'! Apakah Anda yakin?',
            'id' => $data->group_id,
            'affected' => $data->group_nama,
        ], 200);
    }

    public function delete(Request $request){
        // return 'yes';
        DB::table('usergroup')->where('group_id', $request->id)->delete();
    }
}
