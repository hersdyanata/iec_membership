<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Views\Composers\BuildSideNavigatorComposer;
// use Illuminate\Http\Request;

class MenuBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {        
        // Using class based composers...
        View::composer(
            '*', BuildSideNavigatorComposer::class
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}