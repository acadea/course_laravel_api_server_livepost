<?php

use App\Events\ChatMessageEvent;
use App\Models\Post;
use App\Websockets\SocketHandler\UpdatePostSocketHandler;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
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

Route::get('/shared/posts/{post}', function (\Illuminate\Http\Request $request, Post $post){

    return "Specially made just for you 💕 ;) Post id: {$post->id}";

})->name('shared.post')->middleware('signed');



WebSocketsRouter::webSocket('/socket/update-post', UpdatePostSocketHandler::class);



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

        event(new ChatMessageEvent());
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
        event(new ChatMessageEvent($request->message, auth()->user()));
        return null;
    });
}
