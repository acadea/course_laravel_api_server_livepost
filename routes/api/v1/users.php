<?php

//Route::apiResource('users', \App\Http\Controllers\UserController::class);

use Illuminate\Support\Facades\Route;

//Route::group([
//    'middleware' => [
//        'auth',
//    ],
//    'prefix' => 'heyaa',
//    'as' => 'users.',
//    'namespace' => "\App\Http\Controllers",
//], function(){
//    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
////        Route::get('/users', 'UserController@index')->name('index');
//
//    Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('show');
//
//    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('store');
//
//    Route::patch('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
//
//    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
//});

Route::middleware([
//    'auth:api',
//    \App\Http\Middleware\RedirectIfAuthenticated::class,
])
    ->name('users.')
    ->namespace("\App\Http\Controllers")
    ->group(function () {
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])
            ->name('index')
            ->withoutMiddleware('auth');

        Route::get('/users/{user}', [\App\Http\Controllers\UserController::class, 'show'])
            ->name('show')
//            ->where('user', '[0-9]+')
            ->whereNumber('user')
        ;

        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('store');

        Route::patch('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');

        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });

