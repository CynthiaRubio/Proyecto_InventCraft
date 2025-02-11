<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ActionManagementService;
use App\Services\UserManagementService;
use App\Services\ResourceManagementService;
use App\Services\ZoneManagementService;
use App\Services\BuildingManagementService;
use App\Services\InventionService;
use App\Services\FreeSoundService;

class AppServiceProvider extends ServiceProvider
{
    /*
    * Register any application services.
    */
    public function register(): void
    {
        $this->app->singleton(FreeSoundService::class, function ($app) {
            return new FreeSoundService();
        });

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

        $this->app->singleton(ZoneManagementService::class, function ($app) {
            return new ZoneManagementService(
                $app->make(UserManagementService::class),
            );
        });

        $this->app->singleton(ActionManagementService::class, function ($app) {
            return new ActionManagementService(
                $app->make(UserManagementService::class),
                $app->make(ZoneManagementService::class),
            );
        });

        $this->app->singleton(BuildingManagementService::class, function ($app) {
            return new BuildingManagementService(
                $app->make(ActionManagementService::class),
                $app->make(UserManagementService::class),
            );
        });

        $this->app->singleton(ResourceManagementService::class, function ($app) {
            return new ResourceManagementService(
                $app->make(UserManagementService::class),
                $app->make(ActionManagementService::class),
                $app->make(InventionService::class),
                $app->make(ZoneManagementService::class),
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
