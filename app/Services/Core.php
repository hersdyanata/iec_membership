<?php

namespace App\Services;

use DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

use App\Models\CoreMenuDividerModel as Divider;
use App\Models\CoreMenuModel as Menu;

class Core{

    public function list_all_dividers(){
        $data = DB::table('dev_menu_divider')->get()->toArray();
        return $data;
    }

    public function list_active_dividers(){
        $data = DB::table('dev_menu_divider')->where('div_publish_ke_user', 'Y')->get()->toArray();
        return $data;
    }

    public function list_permissions(){
        $data = DB::table('dev_permissions')->get()->toArray();
        return $data;
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
                    'menu_lokasi' => $m->menu_lokasi,
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

    public function list_menu($group_id){
        if(session('group_id') == 0){
            $data['dividers'] = Divider::where('div_publish_ke_user', 'Y')->orderBy('div_order')->get();
            $data['menus'] = Menu::where([
                'menu_publish_ke_user' => 'Y'
            ])->orderBy('menu_order')->get();
        }else{
            $raw_group_menu_permission = DB::table('users_group')->where('group_id', $group_id)->pluck('group_menu_permission')->first();
            $data = $this->get_accessible_menu($raw_group_menu_permission);
        }

        return $data;
    }

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
                ->get();

        $rawCollection = collect($qMenu)->pluck('menu_div_id');
        $unique_dividers = $rawCollection->unique()->all();

        $data['menus'] = $qMenu;
        $data['dividers'] = Divider::whereIn('div_id', $unique_dividers)->orderBy('div_order')->get();
        // $data['privs'] = collect($array_privileges)->toArray();

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
}