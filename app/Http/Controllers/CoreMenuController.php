<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Response;
use DB;

use App\Http\Requests\CoreMenuDividerRequest;
use App\Http\Requests\CoreMenuRequest;

use App\Models\CoreMenuDividerModel as Divider;
use App\Models\CoreMenuModel as Menu;

use App\Services\Core;
use App\Services\CustDataTables;

use Artisan;
use File;

class CoreMenuController extends Controller
{
    protected $core;
    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->middleware(['auth', 'granted']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Menu Manager';
        return view('modules.menu-manager.index')
                ->with([
                    'title' => $title,
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $title = 'Buat Menu Baru';
        return view('modules.menu-manager.create')
                ->with([
                    'title' => $title,
                    'permissions' => $this->core->list_permissions(),
                    'menus' => Menu::all()
                ]);
    }

    public function list_menu(CustDataTables $dtt, Request $request){
        $colSearch = array(
            'menu_nama_ina', 'menu_nama_eng', 'menu_controller', 'menu_route_name',
        );
        
        $colOrder = array(
            'menu_nama_ina', 'menu_nama_eng', 'menu_controller', 'menu_route_name', null
        );

        $q = "SELECT * from (
                SELECT div_nama_ina,
                        a.menu_id,
                        a.menu_nama_ina,
                        a.menu_nama_eng,
                        a.menu_controller,
                        b.menu_nama_ina menu_parent_name,
                        a.menu_route_name,
                        a.menu_folder_view,
                        a.menu_icon,
                        a.menu_publish_ke_user,
                        a.menu_order,
                        div_order,
                        a.menu_id sort_id,
                        a.menu_parent_id parent_id,
                        a.menu_div_id
                   from dev_menu a 
                   left join dev_menu b on b.menu_id = a.menu_parent_id
                   left join dev_menu_divider on div_id = a.menu_div_id
                  where a.menu_parent_id is null
                  union all
                 SELECT div_nama_ina,
                        a.menu_id,
                        concat('-- ', a.menu_nama_ina) menu_ina,
						concat('-- ', a.menu_nama_eng) menu_eng,
                        a.menu_controller,
                        b.menu_nama_ina menu_parent_name,
                        a.menu_route_name,
                        a.menu_folder_view,
                        a.menu_icon,
                        a.menu_publish_ke_user,
                        a.menu_order,
                        div_order,
                        concat(a.menu_parent_id, a.menu_order) sort_id,
                        a.menu_parent_id parent_id,
                        a.menu_div_id
                   from dev_menu a 
                   left join dev_menu b on b.menu_id = a.menu_parent_id
                   left join dev_menu_divider on div_id = a.menu_div_id
                  where a.menu_parent_id is not null
                    and a.menu_level = 2
                  union all
		          SELECT div_nama_ina,
						 a.menu_id,
						 concat('---- ', a.menu_nama_ina) menu_ina,
						 concat('---- ', a.menu_nama_eng) menu_eng,
						 a.menu_controller,
						 b.menu_nama_ina menu_parent_name,
						 a.menu_route_name,
						 a.menu_folder_view,
						 a.menu_icon,
						 a.menu_publish_ke_user,
						 a.menu_order,
						 div_order,
						 concat(b.menu_parent_id, b.menu_order) sort_id,
						 a.menu_parent_id parent_id,
						 a.menu_div_id
			        from dev_menu a 
			        left join dev_menu b on b.menu_id = a.menu_parent_id
			        left join dev_menu_divider on div_id = a.menu_div_id
			       where a.menu_parent_id is not null
				     and a.menu_level = 3
            ) f";
        $order = 'order by sort_id asc, parent_id asc, menu_order asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();

        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){    
            $icon_publish = '<i class="icon-eye-blocked icon-2x"></i>';
            $tooltip = 'Tidak di-publish';
            if($r->menu_publish_ke_user == 'Y'){
                $icon_publish = '<i class="icon-eye4 icon-2x"></i>';
                $tooltip = 'Di-publish';
            }

            if($r->menu_order == 1){
                $order_toggle = '<i class="icon-arrow-down5" onclick="order_down('.$r->menu_id.')"></i>';
            }else{
                $order_toggle = '<i class="icon-arrow-up5" onclick="order_up('.$r->menu_id.')"></i><i class="icon-arrow-down5" onclick="order_down('.$r->menu_id.')"></i>';
            }

            $permissions = DB::table('dev_mapping_permission_ke_menu')->where('map_menu_id', $r->menu_id)->get();

            $no++;
            $dtRow = array();
            $dtRow[] = '<div class="text-center">'.$no.'</div>';
            $dtRow[] = $r->div_nama_ina;
            $dtRow[] = $r->menu_nama_ina;
            // $dtRow[] = $r->menu_nama_eng;
            $dtRow[] = '<div class="text-center">'.$r->menu_order.$order_toggle.'</div>';
            $dtRow[] = ($r->menu_parent_name == null ? '-' : $r->menu_parent_name);
            $dtRow[] = '<code>'.$r->menu_controller.'</code>';
            $dtRow[] = '<code>'.$r->menu_route_name.'</code>';
            $dtRow[] = '<div class="text-center">'.view('modules.menu-manager.menu_permissions')->with('permissions', $permissions)->render().'</div>';
            $dtRow[] = '<div class="text-center"><i class="'.$r->menu_icon.' icon-2x"></i></div>';
            $dtRow[] = '<div class="text-center tooltiped" data-toggle="tooltip" data-placement="top" data-original-title="'.$tooltip.'">'.$icon_publish.'</div>';
            $dtRow[] = '<div class="text-center">
                            <a href="'.route('menu-manager.edit', $r->menu_id).'" class="btn btn-success btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="icon-pencil"></i></a> 
                            <button class="btn btn-danger btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" onclick="preaction('.$r->menu_id.')"><i class="icon-trash"></i></button>
                        </div>';
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

    protected function getStub($type){
        return file_get_contents(resource_path("stubs/$type.stub"));
    }

    protected function make_controller($controller_name, $module_name, $view_module_dir){
        $controllerTemplate = str_replace([
            '{{controllerName}}',
            '{{moduleName}}',
            '{{viewFolderName}}'
        ],
        [
            $controller_name,
            $module_name,
            $view_module_dir
        ],
        $this->getStub('Controller'));
        file_put_contents(app_path("/Http/Controllers/{$controller_name}.php"), $controllerTemplate);
    }

    protected function create_module_dir($name){
        return File::makeDirectory(resource_path("views/modules/".$name), 0755, true, true);
    }

    protected function create_module_index_file($nama_folder, $route_name, $route_group){
        $tableViewTemplate = str_replace([
            '{{thName}}',
            '{{routeName}}',
            '{{routeGroup}}'
        ],
        [
            $nama_folder,
            $route_name,
            $route_group
        ],
        $this->getStub('View_modules'));
        file_put_contents(resource_path("/views/modules/".$nama_folder."/index.blade.php"), $tableViewTemplate);
    }

    protected function declare_route($route_name, $controller_name){
        $sp = "::";
        $controller_class = '\\'.$controller_name.$sp.'class';
        File::append(base_path('routes/web.php'),"\nRoute::resource('".$route_name."', App\Http\Controllers".$controller_class.");");
        Artisan::call('route:cache');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CoreMenuRequest $request){
        $parent_level = $this->set_parent_level($request->menu_parent_id);

        if($parent_level['level'] != 4){
            $menu_id = Menu::insertGetId([
                'menu_div_id' => $request->menu_div_id,
                'menu_parent_id' => $parent_level['parent'],
                'menu_nama_ina' => $request->menu_nama_ina,
                'menu_nama_eng' => $request->menu_nama_eng,
                'menu_controller' => $request->menu_controller,
                'menu_route_group' =>$request->menu_route_group,
                'menu_route_name' => $request->menu_route_name,
                'menu_folder_view' => $request->menu_folder_view,
                'menu_icon' => $request->menu_icon,
                'menu_publish_ke_user' => ($request->menu_publish_ke_user == 'on' ? 'Y' : 'N'),
                'menu_order' => $request->menu_order,
                'menu_level' => $parent_level['level'],
                'menu_createdat' => date('Y-m-d H:i:s'),
                'menu_createdby' => session('user_id'),
            ]);
    
            if(!empty($request->perm_nama)){
                foreach($request->perm_nama as $i => $r){
                    DB::table('dev_mapping_permission_ke_menu')
                        ->insert([
                            'map_menu_id' => $menu_id,
                            'map_perm_nama' => $i
                        ]);
                }
            }
    
            if(!empty($menu_id)){
                // Asumsinya, jika controller = # maka menu tersebu akan menjadi parent menu
                if($request->menu_controller != '#'){
                    $this->make_controller($request->menu_controller, $request->menu_nama_ina, $request->menu_folder_view);
                    $this->create_module_dir(strtolower($request->menu_folder_view));
                    $this->create_module_index_file($request->menu_folder_view, $request->menu_route_name, $request->menu_route_group);
                    $this->declare_route($request->menu_route_name, $request->menu_controller);
                }
            }
    
            $res = [
                'msg_title' => 'Berhasil',
                'msg_body' => 'Data berhasil disimpan.',
            ];
            $res_code = 200;
        }else{
            $res = [
                'msg_title' => 'Gagal',
                'msg_body' => 'Level parent menu tidak mendukung.'
            ];
            $res_code = 400;
        }

        return response()->json($res, $res_code);
    }

    public function set_parent_level($parent_level){
        if($parent_level != null){
            $parent_level = explode(',', $parent_level);
            $level = $parent_level[1];
            
            $res['parent'] = $parent_level[0];
            if($level == 1){
                $res['level'] = 2;
            }elseif($level == 2){
                $res['level'] = 3;
            }else{
                $res['level'] = 4;
            }
        }else{
            $res['parent'] = null;
            $res['level'] = 1;
        }

        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $title = 'Edit Menu';
        return view('modules.menu-manager.edit')
                ->with([
                    'title' => $title,
                    'data' => Menu::find($id),
                    'permissions_mapping' => DB::table('dev_mapping_permission_ke_menu')
                                                ->select('perm_nama')
                                                ->leftJoin('dev_permissions', 'perm_nama', 'map_perm_nama')
                                                ->where('map_menu_id', $id)
                                                ->get()->toArray(),
                    'permissions' => $this->core->list_permissions(),
                    'menus' => Menu::all()
                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $parent_level = $this->set_parent_level($request->menu_parent_id);

        if($parent_level['level'] != 4){
            $put = Menu::find($id);
            $put->menu_div_id = $request->menu_div_id;
            $put->menu_parent_id = $parent_level['parent'];
            $put->menu_nama_ina = $request->menu_nama_ina;
            $put->menu_nama_eng = $request->menu_nama_eng;
            // $put->menu_controller = $request->menu_controller;
            // $put->menu_route_name = $request->menu_route_name;
            $put->menu_icon = $request->menu_icon;
            $put->menu_publish_ke_user = ($request->menu_publish_ke_user == 'on' ? 'Y' : 'N');
            $put->menu_order = $request->menu_order;
            $put->menu_level = $parent_level['level'];
            $put->save();

            if(!empty($request->perm_nama)){
                DB::table('dev_mapping_permission_ke_menu')->where('map_menu_id', $id)->delete();
                foreach($request->perm_nama as $i => $r){
                    DB::table('dev_mapping_permission_ke_menu')
                        ->insert([
                            'map_menu_id' => $id,
                            'map_perm_nama' => $i
                        ]);
                }
            }

            $res = [
                'msg_title' => 'Berhasil',
                'msg_body' => 'Perubahan data berhasil disimpan',
            ];
            $res_code = 200;
        }else{
            $res = [
                'msg_title' => 'Gagal',
                'msg_body' => 'Level parent menu tidak mendukung.'
            ];
            $res_code = 400;
        }
        
        return response()->json($res, $res_code);
    }

    public function predelete_menu(Request $request){
        $id = $request->id;
        $data = Menu::find($id);

        return response()->json([
            'msg_title' => 'Konfirmasi',
            'msg_body' => 'Anda akan menghapus menu '.$data->menu_nama_ina.'! Apakah Anda yakin? Jika menu ini dihapus, hapus manual <strong><code>controller, view,</code></strong> dan <strong><code>route list</code></strong>!',
            'id' => $data->menu_id,
            'affected' => $data->menu_nama_ina,
        ], 200);
    }

    public function set_order_menu(Request $request){
        if($request->move == 'up'){
            $current = Menu::find($request->menu_id);
            if($current->menu_order == 1){
                $new_order = 1;
            }else{
                $new_order = $current->menu_order-1;
            }

            DB::table('dev_menu')->where('menu_order')->update(['menu_order' => $new_order+1]);
            $current->menu_order = $new_order;
            $current->save();
        }else{
            $current = Menu::find($request->menu_id);
            DB::statement("update dev_menu set menu_order = menu_order-1 where menu_order = $current->menu_order+1");
            $current->menu_order = $current->menu_order+1;
            $current->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_menu(Request $request){
        DB::table('dev_menu')->where('menu_id', $request->id)->delete();
        DB::table('dev_mapping_permission_ke_menu')->where('map_menu_id', $request->id)->delete();
    }

    public function divider_options(){
        $opt = '<option value="">Pilih Divider</option>';
        foreach($this->core->list_active_dividers() as $r){
            $opt .= '<option value="'.$r->div_id.'">'.$r->div_nama_ina.'</option>';
        }
        return response()->json($opt, 200);
    }

    public function list_menu_divider(Request $request){
        $col_menu = Menu::where('menu_div_id', $request->div_id)->get();
        $level_1 = $col_menu->where('menu_level', 1)->all();
        $opt = '<option value="">Pilih Parent Menu</option>';

        foreach($level_1 as $lv1){
            $opt .= '<option value="'.$lv1->menu_id.',1">'.$lv1->menu_nama_ina.'</option>';

            $level_2 = $col_menu->where('menu_parent_id', $lv1->menu_id)->where('menu_level', 2)->all();
            foreach($level_2 as $lv2){
                $opt .= '<option value="'.$lv2->menu_id.',2">&nbsp;&nbsp;-- '.$lv2->menu_nama_ina.'</option>';
                
                $level_3 = $col_menu->where('menu_parent_id', $lv2->menu_id)->where('menu_level', 3)->all();
                foreach($level_3 as $lv3){
                    $opt .= '<option value="'.$lv3->menu_id.',3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-- '.$lv3->menu_nama_ina.'</option>';
                }
            }
        }
        return response()->json($opt, 200);
    }

    public function simpan_divider(CoreMenuDividerRequest $request){
        Divider::create([
            'div_nama_ina' => $request->div_nama_ina,
            'div_nama_eng' => $request->div_nama_eng,
            'div_order' => $request->div_order,
            'div_publish_ke_user' => ($request->div_publish_ke_user == 'on' ? 'Y' : 'N')
        ]);
        
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan.',
        ];

        return response()->json($res, 200);
    }

    public function update_divider(CoreMenuDividerRequest $request){
        $put = Divider::find($request->div_id);
        $put->div_nama_ina = $request->div_nama_ina;
        $put->div_nama_eng = $request->div_nama_eng;
        $put->div_order = $request->div_order;
        $put->div_publish_ke_user = ($request->div_publish_ke_user == 'on' ? 'Y' : 'N');
        $put->save();

        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'Perubahan data berhasil disimpan.',
            'affected' => $put
        ];

        return response()->json($res, 200);
    }

    public function read_divider(Request $request){
        $id = $request->id;
        $data = Divider::find($id);

        return response()->json($data, 200);
    }

    public function predelete_divider(Request $request){
        $id = $request->id;
        $data = Divider::find($id);

        return response()->json([
            'msg_title' => 'Konfirmasi',
            'msg_body' => 'Anda akan menghapus divider '.$data->div_nama_ina.'! Apakah Anda yakin?',
            'id' => $data->div_id,
            'affected' => $data->div_nama_ina,
        ], 200);
    }

    public function delete_divider(Request $request){
        DB::table('dev_menu_divider')->where('div_id', $request->id)->delete();
    }

    public function list_divider(CustDataTables $dtt, Request $request){
        $colSearch = array(
            'div_nama_ina', 'div_nama_eng', 'div_order',
        );
        
        $colOrder = array(
            'div_nama_ina', 'div_nama_eng', 'div_order', null
        );

        $q = "SELECT * from dev_menu_divider";
        $order = 'order by div_order asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();

        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){    
            $icon_publish = '<i class="icon-eye-blocked"></i>';
            $tooltip = 'Tidak di-publish';
            if($r->div_publish_ke_user == 'Y'){
                $icon_publish = '<i class="icon-eye4"></i>';
                $tooltip = 'Di-publish';
            }

            if($r->div_order == 1){
                $order_toggle = '<i class="icon-arrow-down5" onclick="order_down('.$r->div_id.')"></i>';
            }else{
                $order_toggle = '<i class="icon-arrow-up5" onclick="order_up('.$r->div_id.')"></i><i class="icon-arrow-down5" onclick="order_down('.$r->div_id.')"></i>';
            }

            $no++;
            $dtRow = array();
            $dtRow[] = $r->div_nama_ina;
            $dtRow[] = $r->div_nama_eng;
            $dtRow[] = '<div class="text-center">'.$r->div_order.$order_toggle.'</div>';
            $dtRow[] = '<div class="text-center tooltiped" data-toggle="tooltip" data-placement="top" data-original-title="'.$tooltip.'">'.$icon_publish.'</div>';
            $dtRow[] = '<div class="text-center">
                            <button class="btn btn-success btn-sm btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" onclick="edit_divider('.$r->div_id.')"><i class="icon-pencil"></i></button>
                            <button class="btn btn-danger btn-sm btn-icon rounded-round tooltiped" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" onclick="preaction('.$r->div_id.')"><i class="icon-trash"></i></button>
                        </div>';
            $data[] = $dtRow;
        }

        $json_data = array(
            "draw"              => $request->input('draw'),
            "recordsTotal"      => $dtt->count_all(),
            "recordsFiltered"   => $dtt->count_filtered(),
            "data"              => $data,
        );

        echo json_encode($json_data);
    }

    public function set_order_divider(Request $request){
        if($request->move == 'up'){
            $current = Divider::find($request->div_id);
            if($current->div_order == 1){
                $new_order = 1;
            }else{
                $new_order = $current->div_order-1;
            }

            DB::table('dev_menu_divider')
            ->where('div_order', $new_order)
            ->update([
                'div_order' => $new_order+1
            ]);

            $current->div_order = $new_order;
            $current->save();
        }else{
            $current = Divider::find($request->div_id);
            DB::statement("update dev_menu_divider set div_order = div_order-1 where div_order = $current->div_order+1");
            $current->div_order = $current->div_order+1;
            $current->save();
        }
    }
}
