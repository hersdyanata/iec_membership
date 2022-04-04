<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use DB;

use Closure;
use Auth;

use App\Models\CoreMenuModel as Menu;
use App\Models\CoreMenuDividerModel as Divider;

class Granted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next){
        $accessed = [$request->segment(1), $request->segment(2)];
        // dd($accessed);
        // die;

        if(session('group_id') != 0){

            
            $menu_route = collect(session('all_menus'))->whereIn('menu_route_name', $accessed)->pluck('menu_route_name')->first();
            $has_access = Arr::exists(session('granted_menu'), $menu_route);
            // dd($has_access);
            // die;
            if( $has_access === false ){
                abort(404);
            }

            $permission = collect(session('permission'));

            $accessed_menu_id = collect(session('granted_menu'))->where('menu_route', $menu_route)->collapse()->all()['menu_id'];
            // echo $accessed_menu_id;
            // die;
            $page_permission = $permission->where('menu_id', $accessed_menu_id)->collapse()->all()['perms'];
            // dd($page_permission);
            // die;
            
            $request->merge(array('page_permission' => $page_permission));
            return $next($request);
        }else{
            // $menus = Menu::orderBy('menu_div_id')
            //             ->orderBy('menu_order')
            //             ->get();

            // $divider = Divider::all();

            // $request->merge(array('all_menus' => $menus, 'dividers' => $divider));
            return $next($request);
        }

    }
}
