<?php

namespace App\Services\ShebaRequest;

interface ShebaRequestServiceInterface
{
    public function create( array $data, string $idempotencyKey ): array;

    public function list(): array;

    public function updateStatus( string $id, string $status, ?string $note = null ): array;
}

