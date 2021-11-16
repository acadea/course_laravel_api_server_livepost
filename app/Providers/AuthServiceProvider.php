<?php

namespace App\Providers;

use App\Mail\VerifyMail;
use App\Mail\WelcomeMail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //

        VerifyEmail::toMailUsing(function ($notifiable, $url){
            return new VerifyMail($notifiable, $url);
        });

//        VerifyEmail::createUrlUsing(function (){
//            // logic here
//        });
    }
}
