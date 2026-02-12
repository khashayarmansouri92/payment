<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transactions\TransactionModelMethods;
use App\Models\Transactions\TransactionModelRelations;
use App\Models\Transactions\TransactionModelFilters;
use App\Models\Accounts\Account;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use TransactionModelMethods, TransactionModelRelations, TransactionModelFilters, SoftDeletes;

    protected $fillable = [
        'account_id',
        'amount',
        'type',
        'status',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];
}

