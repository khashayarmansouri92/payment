<?php

namespace App\Providers;

use App\Repositories\Account\AccountRepository;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\IdempotencyKey\IdempotencyKeyRepository;
use App\Repositories\IdempotencyKey\IdempotencyKeyRepositoryInterface;
use App\Repositories\ShebaRequest\ShebaRequestRepository;
use App\Repositories\ShebaRequest\ShebaRequestRepositoryInterface;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Services\Account\AccountService;
use App\Services\Account\AccountServiceInterface;
use App\Services\ShebaRequest\ShebaRequestService;
use App\Services\ShebaRequest\ShebaRequestServiceInterface;
use App\Services\Transaction\TransactionService;
use App\Services\Transaction\TransactionServiceInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton( 'files', function ()
        {
            return new Filesystem();
        } );

        $this->app->bind( ShebaRequestRepositoryInterface::class, ShebaRequestRepository::class );
        $this->app->bind( AccountRepositoryInterface::class, AccountRepository::class );
        $this->app->bind( TransactionRepositoryInterface::class, TransactionRepository::class );
        $this->app->bind( IdempotencyKeyRepositoryInterface::class, IdempotencyKeyRepository::class );

        $this->app->bind( ShebaRequestServiceInterface::class, ShebaRequestService::class );
        $this->app->bind( AccountServiceInterface::class, AccountService::class );
        $this->app->bind( TransactionServiceInterface::class, TransactionService::class );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
