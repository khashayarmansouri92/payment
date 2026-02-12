<?php

namespace App\Events;

use App\Models\ShebaRequests\ShebaRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShebaRequestConfirmed
{
    use Dispatchable, SerializesModels;

    public ShebaRequest $shebaRequest;

    public function __construct( ShebaRequest $shebaRequest )
    {
        $this->shebaRequest = $shebaRequest;
    }
}

