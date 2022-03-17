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

Route::get('/shared/posts/{post}', function (\Illuminate\Http\Request $request, \App\Models\Post $post){

    return "Specially made just for you 💕 ;) Post id: {$post->id}";

})->name('shared.post')->middleware('signed');


if(\Illuminate\Support\Facades\App::environment('local')){

//    Route::get('/shared/videos/{video}', function (\Illuminate\Http\Request $request, $video){
//
////        if(!$request->hasValidSignature()){
////            abort(401);
////        }
//
//        return 'git gud';
//    })->name('share-video')->middleware('signed');

    Route::get('/playground', function (){

        event(new \App\Events\ChatMessageEvent());
//        $url = URL::temporarySignedRoute('share-video', now()->addSeconds(30), [
//            'video' => 123
//        ]);
//        return $url;
       return null;
    });

    Route::get('/ws', function (){
        return view('websocket');
    });

    Route::post('/chat-message', function (\Illuminate\Http\Request $request){
        event(new \App\Events\ChatMessageEvent($request->message));
        return null;
    });
}
