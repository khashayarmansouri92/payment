<?php

namespace App\Providers;

use App\Services\ShebaTransfer\ShebaTransferService;
use App\Services\ShebaTransfer\ShebaTransferServiceInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('files', function () {
            return new Filesystem();
        });

        $this->app->bind(ShebaTransferServiceInterface::class, ShebaTransferService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
