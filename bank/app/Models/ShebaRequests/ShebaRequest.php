<?php

namespace App\Models\ShebaRequests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ShebaRequests\ShebaRequestModelMethods;
use App\Models\ShebaRequests\ShebaRequestModelRelations;
use App\Models\ShebaRequests\ShebaRequestModelFilters;
use App\Models\Transactions\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShebaRequest extends Model
{
    use HasUuids, ShebaRequestModelMethods, ShebaRequestModelRelations, ShebaRequestModelFilters, SoftDeletes;

    protected $table = 'sheba_requests';

    protected $fillable = [
        'price',
        'status',
        'from_sheba_number',
        'to_sheba_number',
        'note',
    ];

    protected $casts = [
        'price' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];
}

