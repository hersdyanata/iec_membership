<?php

namespace App\Http\Controllers;
use Auth;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'granted', 'verified']);
    }

    public function index(){
        return view('modules.dashboard.index')
                ->with([
                    'title' => 'Dashboard',
                ]);
    }

}
