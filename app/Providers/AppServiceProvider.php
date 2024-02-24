<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Smtp;
use Config;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $mailsetting =Smtp::first();
        if($mailsetting !=null){
            if($mailsetting->status ==1){
                $data =[
                    'driver' => 'smtp',
                    'host' => $mailsetting->host,
                    'port' => $mailsetting->port,
                    'encryption' => 'tls',
                    'username' => $mailsetting->user,
                    'password' => $mailsetting->pass,
                    'from' => [
                        'address' =>$mailsetting->from_name,
                        'name' => env('APP_NAME'),
                    ]
                    
                ];
                Config::set('mail',$data);
            }
        }

       


    }
}
