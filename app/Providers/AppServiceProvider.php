<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ActionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Contracts\ResourceServiceInterface;
use App\Contracts\ZoneServiceInterface;
use App\Contracts\BuildingServiceInterface;
use App\Contracts\InventionServiceInterface;
use App\Contracts\InventionTypeServiceInterface;
use App\Contracts\FreeSoundServiceInterface;
use App\Services\ActionService;
use App\Services\UserService;
use App\Services\ResourceService;
use App\Services\ZoneService;
use App\Services\BuildingService;
use App\Services\InventionService;
use App\Services\InventionTypeService;
use App\Services\FreesoundService;

class AppServiceProvider extends ServiceProvider
{
    /*
    * Register any application services.
    */
    public function register(): void
    {
        // Services sin dependencias (pueden ir en cualquier orden)
        $this->app->singleton(FreeSoundServiceInterface::class, function ($app) {
            return new FreesoundService();
        });

        $this->app->singleton(InventionTypeServiceInterface::class, function ($app) {
            return new InventionTypeService();
        });

        // 1. Base: UserService (sin dependencias)
        $this->app->singleton(UserServiceInterface::class, function ($app) {
            return new UserService();
        });

        // 2. ZoneService (depende de UserService)
        $this->app->singleton(ZoneServiceInterface::class, function ($app) {
            return new ZoneService(
                $app->make(UserServiceInterface::class),
            );
        });

        // 3. ActionService (depende de UserService y ZoneService)
        $this->app->singleton(ActionServiceInterface::class, function ($app) {
            return new ActionService(
                $app->make(UserServiceInterface::class),
                $app->make(ZoneServiceInterface::class),
            );
        });

        // 4. InventionService (depende de UserService y ActionService)
        $this->app->singleton(InventionServiceInterface::class, function ($app) {
            return new InventionService(
                $app->make(UserServiceInterface::class),
                $app->make(ActionServiceInterface::class),
            );
        });

        // 5. BuildingService (depende de ActionService, UserService e InventionService)
        $this->app->singleton(BuildingServiceInterface::class, function ($app) {
            return new BuildingService(
                $app->make(ActionServiceInterface::class),
                $app->make(UserServiceInterface::class),
                $app->make(InventionServiceInterface::class),
            );
        });

        // 6. ResourceService (depende de todos los anteriores)
        $this->app->singleton(ResourceServiceInterface::class, function ($app) {
            return new ResourceService(
                $app->make(UserServiceInterface::class),
                $app->make(ActionServiceInterface::class),
                $app->make(InventionServiceInterface::class),
                $app->make(ZoneServiceInterface::class),
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
