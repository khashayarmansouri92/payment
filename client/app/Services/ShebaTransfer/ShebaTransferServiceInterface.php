<?php

namespace App\Services\ShebaTransfer;

interface ShebaTransferServiceInterface
{
    public function send( int $price, string $fromShebaNumber, string $toShebaNumber, ?string $note = null, ?string $idempotencyKey = null ): array;
}

