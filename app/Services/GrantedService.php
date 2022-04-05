<?php

namespace App\Services;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

use App\Models\CoreMenuDividerModel as Divider;
use App\Models\CoreMenuModel as Menu;

use App\Models\UsergroupModel as UserGroup;
use Auth;

class GrantedService{

    public function parse_privs_array($raw_group_menu_permission){
        $array_owned_menu = array();
        $array_privileges = array();
        if($raw_group_menu_permission != null){
            $raw_array_owned_menu = array();
            $raw_permission = json_decode($raw_group_menu_permission);
            foreach($raw_permission as $v){
                $array_owned_menu[] = $v->menu_id;
                $array_privileges[] = [
                    'menu_id' => $v->menu_id,
                    'perms' => $v->permissions
                ];
            }
        }

        $data = [
            'array_owned_menu' => $array_owned_menu,
            'array_privileges' => $array_privileges
        ];

        return $data;
    }

    public function get_accessible_menu($raw_group_menu_permission){
        $package = $this->parse_privs_array($raw_group_menu_permission);
        $qMenu = Menu::whereIn('menu_id', $package['array_owned_menu'])
                ->orderBy('menu_div_id')
                ->orderBy('menu_order')
                ->get()->toArray();

        $rawCollection = collect($qMenu)->pluck('menu_div_id');
        $unique_dividers = $rawCollection->unique()->all();

        $data['menus'] = $qMenu;
        $data['dividers'] = Divider::whereIn('div_id', $unique_dividers)->orderBy('div_order')->get()->toArray();
        $data['actions'] = $package;

        return $data;
    }

    public function action_priv(){
        $url = url()->current();
        $segmented = explode('/', $url);
        $route = $segmented[3];
        $menu_id = DB::table('dev_menu')->where('menu_route_name', $route)->pluck('menu_id')->first();

        $qGroup = DB::table('users_group')->where('group_id', session('group_id'))->pluck('group_menu_permission')->first();
        $raw_act_privs = $this->parse_privs_array($qGroup);

        $collections = collect($raw_act_privs['array_privileges'])->where('menu_id', $menu_id)->first();
        return $collections['perms'];
    }

    public function list_menu_and_permission(){
        $dividers = DB::table('dev_menu_divider')->where('div_publish_ke_user', 'Y')->orderBy('div_order')->get()->toArray();
        $menus = DB::table('dev_menu')->where('menu_publish_ke_user', 'Y')->get();
        $permissions = DB::table('dev_mapping_permission_ke_menu')->get();

        $coll_menu = collect($menus);
        $coll_permission = collect($permissions);

        $items = array();
        foreach($dividers as $d){
            $menu = $coll_menu->where('menu_div_id', $d->div_id)->toArray();
            $arr_menu = array();
            foreach($menu as $m){
                $permission = $coll_permission->where('map_menu_id', $m->menu_id)->toArray();
                $arr_permission = array();
                foreach($permission as $p){
                    $arr_permission[] = [
                        'map_perm_nama' => $p->map_perm_nama
                    ];
                }
                $arr_menu[] = [
                    'menu_id' => $m->menu_id,
                    'menu_nama_ina' => $m->menu_nama_ina,
                    'menu_nama_eng' => $m->menu_nama_eng,
                    'menu_controller' => $m->menu_controller,
                    'menu_icon' => $m->menu_icon,
                    'menu_route_name' => $m->menu_route_name,
                    'menu_order' => $m->menu_order,
                    'permission' => $arr_permission
                ];
            }

            $items[] = [
                'div_nama' => $d->div_nama_ina,
                'menus' => $arr_menu
            ];
        }

        return $items;
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

    public function action_buttons_multiple_param($route, $param, $page_permission){
        $privs = $page_permission;
        $btn = '';
        $paramString = '';
        $separator = ',';
        $no = 0;
        foreach($param as $i => $p){
            $no++;
            if($no == count($param)){
                $separator = '';
            }
            $paramString .= $p.$separator;
        }
        $btn_edit = '<a href="'.route($route, $param).'" class="btn btn-success btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> ';
        $btn_delete = '<button class="btn btn-danger btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" onclick="preaction('.$paramString.')"><i class="icon-trash"></i></button>';

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

    public function set_permission(){
        $userLogin = Auth::user();
        $group = UserGroup::where('group_id', $userLogin->group_id)->first();
        if($userLogin->group_id != 0){
            $properties = $this->get_accessible_menu($group->group_menu_permission);
            // $properties = $group->group_menu_permission;
            // dd($properties);
            // die;
            $dividers = $properties['dividers'];
            $menus = $properties['menus'];
            $permission = $properties['actions']['array_privileges'];

            $arr_granted = array();
            foreach($menus as $r){
                $arr_granted[$r['menu_route_name']] = array(
                    'menu_id' => $r['menu_id'],
                    'menu_name' => $r['menu_nama_ina'],
                    'menu_route' => $r['menu_route_name'],
                );
            }
            
            $data = [
                'user_id' => $userLogin->id,
                'group_id' => $userLogin->group_id,
                'group_name' => $group->group_nama,
                'theme' => $userLogin->theme,
                'granted_menu' => $arr_granted,
                'all_menus' => $menus,
                'dividers' => $dividers,
                'permission' => $permission
            ];
        }else{
            $menus = Menu::orderBy('menu_div_id')
                    ->orderBy('menu_order')
                    ->get()->toArray();

            $divider = Divider::all()->toArray();

            $data = [
                'all_menus' => $menus,
                'dividers' => $divider,
                'user_id' => $userLogin->id,
                'group_id' => $userLogin->group_id,
                'group_name' => 'Developer',
                'theme' => $userLogin->theme,
            ];
        }

        session($data);
    }
}