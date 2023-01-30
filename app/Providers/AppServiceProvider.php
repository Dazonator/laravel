<?php

namespace App\Providers;

use App\Events\CreatedMessageTaskEvent;
use App\Listeners\SendMessageTaskNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
//        Event::listen(
//            CreatedMessageTaskEvent::class,
//            [SendMessageTaskNotification::class, 'handle']
//        );
    }
}
