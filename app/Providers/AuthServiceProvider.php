<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*Gate::resource('posts', 'PostPolicy', [
            'image' => 'updateImage',
            'photo' => 'updatePhoto',
        ]); untuk add methode lain*/

        $this->registerPolicies();
        Gate::resource('manage-user','App\Policies\UserModelPolicy');
    }
}
