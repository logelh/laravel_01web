<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     * 自动授权注册
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::guessPolicyNamesUsing(function ($modelClass) {
           return 'App\Policies\\' . class_basename($modelClass) . 'Policy';
        });
    }
}
