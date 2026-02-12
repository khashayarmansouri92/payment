<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transactions\Transaction;
use App\Models\Accounts\AccountModelMethods;
use App\Models\Accounts\AccountModelRelations;
use App\Models\Accounts\AccountModelFilters;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use AccountModelMethods, AccountModelRelations, AccountModelFilters, SoftDeletes;

    protected $table = 'accounts';
    protected $fillable = [
        'sheba_number',
        'balance',
    ];

    protected $casts = [
        'balance' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];
}

