<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class ShebaLoggingListener
{
    public function handle( $event ): void
    {
        $request = $event->shebaRequest;
        Log::info( trans( 'messages.log.sheba_state_changed' ), [
            'event' => class_basename( $event ),
            'id' => $request->id,
            'status' => $request->status,
            'price' => $request->price,
            'from' => $request->from_sheba_number,
            'to' => $request->to_sheba_number,
        ] );
    }
}

