<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;
use Illuminate\Support\Facades\Hash;

use App\Services\CustDataTables;
use App\Models\User;
use App\Models\UsergroupModel as Usergroup;

use App\Services\Core;

use App\Http\Requests\UserRequest;

class UserController extends Controller{
    public $core;
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
    public function index(){
        $title = 'Data Pengguna';
        return view('modules.user.index')
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

    public function list_user(CustDataTables $dtt, Request $request){
        $colSearch = array(
            'name', 'email', 'group_nama', 'parent_user_name'
        );
        
        $colOrder = array(
            null, 'name', 'email', 'group_nama', null, null
        );

        $devOnly = '';
        $parentUser = '';
        if(session('group_id') != 0){
            $devOnly = "where a.group_id != 0";
            $parentUser = "and a.parent_user_id = ".session('user_id');
        }

        $q = "SELECT a.*, b.group_nama, c.name parent_user_name
                from users a 
                left join usergroup b on a.group_id = b.group_id
                left join users c on a.parent_user_id = c.id
                $devOnly $parentUser";
        $order = 'order by id asc';
        
        $dtt->set_table($q);
        $dtt->set_col_order($colOrder);
        $dtt->set_col_search($colSearch);
        $dtt->set_order($order);

        $posts = $dtt->get_datatables();
        $no = $request->input('start');
        $data = array();
        foreach($posts as $r){
            $no++;

            if($r->group_id == 0){
                $parentUserRow = '<code>Developer</code>';
            }elseif($r->group_id != 0){
                $parentUserRow = ($r->parent_user_name == null) ? 'Owner' : $r->parent_user_name;
            }

            $show_parent_user = '';
            if(session('group_id') == 0){
                $show_parent_user = $parentUserRow;
            }

            $dtRow = array();
            $dtRow[] = '<div class="text-center">'.$no.'</div>';
            $dtRow[] = $r->name;
            $dtRow[] = $r->email;
            if(session('group_id') == 0){
                $dtRow[] = $parentUserRow;
            }
            $dtRow[] = $r->username;
            $dtRow[] = ($r->group_id == 0) ? 'Developer' : $r->group_nama;
            $dtRow[] = date('Y-m-d', strtotime($r->created_at));
            $dtRow[] = '<div class="text-center">'.$this->action_buttons('user.edit', $r->id, $request->page_permission).'</div>';
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Data Pengguna Baru';
        return view('modules.user.create')
                ->with([
                    'title' => $title,
                    'groups' => Usergroup::get()
                ]);
    }

    public function show($id){
        $data = User::find($id);
        $res = [
            'msg_title' => 'Konfirmasi',
            'msg_body' => 'Apakah Anda yakin akan menghapus user '.$data->name.' ?',
            'permission' => 'Y'
        ];
        return response()->json($res, 200);
    }

    public function destroy($id){
        $data = User::find($id);
        $res = [
            'msg_title' => 'Berhasil',
            'msg_body' => 'User '.$data->name.' telah dihapus.',
        ];
        $data->delete();
        return response()->json($res,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $req){
        // User::create([
        //     'name' => $req->name,
        //     'email' => $req->email,
        //     'username' => $req->username,
        //     'group_id' => $req->group_id,
        //     'theme' => 'light',
        //     'parent_user_id' => session('user_id'),
        //     'password' => Hash::make($req->password),
        // ]);

        // dd($req->all());
        User::create($req->all());

        return response()->json([
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Edit Data Pengguna';
        return view('modules.user.edit')
                ->with([
                    'title' => $title,
                    'owned' => User::find($id),
                    'groups' => Usergroup::get()
                ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $req, $id)
    {
        $put = User::find($id);
        $put->name = $req->name;
        $put->email = $req->email;
        $put->username = $req->username;
        $put->group_id = $req->group_id;
        $put->password = $req->password;
        $put->save();

        return response()->json([
            'msg_title' => 'Berhasil',
            'msg_body' => 'Data berhasil disimpan'
        ], 200);
    }

    public function predelete(Request $req){
        $id = $req->id;
        $data = User::find($id);

        return response()->json([
            'msg_title' => 'Konfirmasi',
            'msg_body' => 'Anda akan menghapus user '.$data->name.'! Apakah Anda yakin?',
            'id' => $data->id,
            'affected' => $data->name,
        ], 200);
    }

    public function delete(Request $req){
        DB::table('users')->where('id', $req->id)->delete();
    }
}
