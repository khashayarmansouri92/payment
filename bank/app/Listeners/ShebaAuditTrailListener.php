<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class ShebaAuditTrailListener
{
    public function handle( $event ): void
    {
        $request = $event->shebaRequest;
        Log::channel( 'single' )->info( trans( 'messages.log.sheba_audit' ), [
            'event' => class_basename( $event ),
            'sheba_request_id' => $request->id,
            'status' => $request->status,
            'timestamp' => now()->toIso8601String(),
        ] );
    }
}

