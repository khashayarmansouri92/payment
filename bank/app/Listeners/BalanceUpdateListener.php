<?php

namespace App\Listeners;

use App\Events\ShebaRequestCanceled;
use App\Events\ShebaRequestConfirmed;
use App\Services\Account\AccountServiceInterface;
use Illuminate\Support\Facades\Log;

class BalanceUpdateListener
{
    protected AccountServiceInterface $accountService;

    public function __construct( AccountServiceInterface $accountService )
    {
        $this->accountService = $accountService;
    }

    public function handle( $event ): void
    {
        $request = $event->shebaRequest;
        $from = $this->accountService->getBySheba($request->from_sheba_number);
        $to   = $this->accountService->getBySheba($request->to_sheba_number);

        if ( $event instanceof ShebaRequestConfirmed )
        {
            Log::info( 'BalanceUpdateListener confirmed fired', [
                'from' => $request->from_sheba_number,
                'to' => $request->to_sheba_number,
                'price' => $request->price,
                'to_exists' => (bool)$to,
            ] );
            if ( $to )
            {
                $this->accountService->credit( $to, $request->price );
            }
        }

        if ( $event instanceof ShebaRequestCanceled )
        {
            Log::info( 'BalanceUpdateListener canceled fired', [
                'from' => $request->from_sheba_number,
                'to' => $request->to_sheba_number,
                'price' => $request->price,
                'from_exists' => (bool)$from,
            ] );
            if ( $from )
            {
                $this->accountService->credit( $from, $request->price );
            }
        }
    }
}

