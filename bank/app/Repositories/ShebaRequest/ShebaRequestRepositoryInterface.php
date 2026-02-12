<?php

namespace App\Repositories\ShebaRequest;

use App\Enums\ShebaRequestStatus;
use App\Models\ShebaRequests\ShebaRequest;
use Illuminate\Database\Eloquent\Collection;

interface ShebaRequestRepositoryInterface
{
    public function create( array $data ): ShebaRequest;

    public function findByStatusOrdered( ShebaRequestStatus $status ): Collection;

    public function findByIdWithLock( string $id ): ?ShebaRequest;

    public function update( ShebaRequest $request, array $data ): void;
}
