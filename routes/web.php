<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/app', function (){
    return view('app');
});

Route::get('/reset-password/{token}', function ($token){
    return view('auth.password-reset', [
        'token' => $token
    ]);
})->middleware(['guest:'.config('fortify.guard')])
  ->name('password.reset');

if(\Illuminate\Support\Facades\App::environment('local')){


    Route::get('/playground', function (){
    //    App::setLocale('es');
        $trans = \Illuminate\Support\Facades\Lang::get('auth.failed');
        $trans = __('auth.password');
        $trans = __('auth.throttle', ['seconds' => 5]);
        // current locale
        dump(\Illuminate\Support\Facades\App::currentLocale());
        dump(App::isLocale('en'));

        $trans = __('this is sparta');
        $trans = trans_choice('auth.pants', -4);
        $trans = trans_choice('auth.apples', 2, ['baskets' => 2]);
        $trans = __('auth.welcome', ['name' => 'sam']);


        dd($trans);
        $user = \App\Models\User::factory()->make();
        \Illuminate\Support\Facades\Mail::to($user)
            ->send(new \App\Mail\WelcomeMail($user));
       return null;
    });
}
