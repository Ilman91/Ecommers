<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeCartListener;
use App\Events\OrderPaidEvent;
use App\Listeners\SendOrderPaidEmail;

class EventServiceProvider extends ServiceProvider
{
        protected $listen = [
        Login::class => [
            MergeCartListener::class,
        ],

        OrderPaidEvent::class => [
            SendOrderPaidEmail::class,
        ],
    ];
}