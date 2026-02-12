<?php

namespace App\Repositories\ShebaRequest;

use App\Enums\ShebaRequestStatus;
use App\Models\ShebaRequests\ShebaRequest;
use Illuminate\Database\Eloquent\Collection;

class ShebaRequestRepository implements ShebaRequestRepositoryInterface
{
    /**
     * @param array $data
     * @return \App\Models\ShebaRequests\ShebaRequest
     */
    public function create( array $data ): ShebaRequest
    {
        return ShebaRequest::create( $data );
    }

    /**
     * @param \App\Enums\ShebaRequestStatus $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByStatusOrdered( ShebaRequestStatus $status ): Collection
    {
        return ShebaRequest::findByStatusOrdered( $status->value );
    }

    /**
     * @param string $id
     * @return \App\Models\ShebaRequests\ShebaRequest|null
     */
    public function findByIdWithLock( string $id ): ?ShebaRequest
    {
        return ShebaRequest::findByIdWithLock( $id );
    }

    /**
     * @param \App\Models\ShebaRequests\ShebaRequest $request
     * @param array $data
     * @return void
     */
    public function update( ShebaRequest $request, array $data ): void
    {
        $request->update( $data );
    }
}
