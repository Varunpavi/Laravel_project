<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/'; 

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
            $this->mapWebRoutes();
            $this->mapAdminRoutes();
            $this->mapUserRoutes();
        });

    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();

        $this->mapUserRoutes();

        //
    }

   /**
    * Define the "web" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
   protected function mapWebRoutes()
   {
       Route::middleware('web')
           ->namespace($this->namespace)
           ->group(base_path('routes/web.php'));
   }

   /**
    * Define the "api" routes for the application.
    *
    * These routes are typically stateless.
    *
    * @return void
    */
   protected function mapApiRoutes()
   {
       Route::prefix('api')
           ->middleware('api')
           ->namespace($this->namespace)
           ->group(base_path('routes/api.php'));
   }

   protected function mapAdminRoutes()
   {
       Route::middleware('web')
           ->namespace($this->namespace)
           ->group(base_path('routes/admin.php'));
   }

   protected function mapUserRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/user.php'));
    }
}
