<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Http\Middleware\RoleMiddleware;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ✅ Registra o middleware com nome "role"
        Route::aliasMiddleware('role', RoleMiddleware::class);

        // ✅ Aqui você registra as rotas
        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
