<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
    }
    public function map()
    {
        
        $this->mapRoutes();
        $this->mapAuthRoutes();
        $this->mapFrontRoutes();
         
        
    }

    
    protected function mapRoutes()
    {
        Route::middleware('web','logged','ganti-password')
              
             ->namespace('App\Http\Controllers\Admin')
             ->group(base_path('routes/route.php'));
    }
    
    protected function mapFrontRoutes()
    {
        Route::middleware('web')
              
             ->namespace('App\Http\Controllers\Front')
             ->group(base_path('routes/front.php'));
    }
    
    protected function mapAuthRoutes()
    {
        Route::middleware('web')   
             ->namespace('App\Http\Controllers\Auth')
             ->group(base_path('routes/auth.php'));
    }

    
}
