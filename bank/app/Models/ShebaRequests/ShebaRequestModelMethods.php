<?php

namespace App\Models\ShebaRequests;

trait ShebaRequestModelMethods
{
    /**
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findByStatusOrdered( string $status ): \Illuminate\Database\Eloquent\Collection
    {
        return static::query()
            ->where( 'status', $status )
            ->orderBy( 'created_at', 'asc' )
            ->get();
    }

    /**
     * @param string $id
     * @return \App\Models\ShebaRequests\ShebaRequestModelMethods|\App\Models\ShebaRequests\ShebaRequest|null
     */
    public static function findByIdWithLock( string $id ): ?self
    {
        return static::where( 'id', $id )->lockForUpdate()->first();
    }
}

