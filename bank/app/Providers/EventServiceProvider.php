<?php

namespace App\Providers;

use App\Events\ShebaRequestCanceled;
use App\Events\ShebaRequestConfirmed;
use App\Listeners\BalanceUpdateListener;
use App\Listeners\ShebaAuditTrailListener;
use App\Listeners\ShebaLoggingListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ShebaRequestConfirmed::class => [
            BalanceUpdateListener::class,
            ShebaLoggingListener::class,
            ShebaAuditTrailListener::class,
        ],
        ShebaRequestCanceled::class => [
            BalanceUpdateListener::class,
            ShebaLoggingListener::class,
            ShebaAuditTrailListener::class,
        ],
    ];

    public function boot(): void
    {
    }
}

