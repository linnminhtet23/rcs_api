<?php
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::namespace('Api\V1')->group(function(){
    Route::prefix('v1')->group(function(){
        Route::post('io-register', [AuthController::class,'register']);
        Route::post('io-login', [AuthController::class, 'login']);

        Route::middleware(['auth:api'])->group(function(){
            //User
            Route::get('user', [AuthController::class,'user']);

            //Blogs
            Route::get('blogs',[BlogController::class,'index']);
            Route::post('blogs',[BlogController::class,'store']);
        });

        if (App::environment('local')) {
            Route::get('routes', function () {
                $routes = [];

                foreach (Route::getRoutes()->getIterator() as $route) {
                    if (strpos($route->uri, 'api') !== false) {
                        $routes[] = $route->uri;
                    }
                }

                return response()->json($routes);
            });
        }
    });
});