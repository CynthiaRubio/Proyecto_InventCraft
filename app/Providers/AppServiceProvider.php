<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ActionManagementService;
use App\Services\UserManagementService;
use App\Services\ResourceManagementService;
use App\Services\InventionService;

class AppServiceProvider extends ServiceProvider
{
    /*
    * Register any application services.
    */
    public function register(): void
    {
        $this->app->singleton(InventionTypeService::class, function ($app) {
            return new InventionTypeService();
        });


        $this->app->singleton(UserManagementService::class, function ($app) {
            return new UserManagementService();
        });

        
        $this->app->singleton(InventionService::class, function ($app) {
            return new InventionService(
                $app->make(UserManagementService::class),
            );
        });

        
        $this->app->singleton(ActionManagementService::class, function ($app) {
            return new ActionManagementService(
                $app->make(UserManagementService::class),
            );
        });


        $this->app->singleton(ResourceManagementService::class, function ($app) {
            return new ResourceManagementService(
                $app->make(UserManagementService::class),
                $app->make(ActionManagementService::class),
                $app->make(InventionService::class),
            );
        });

        
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
