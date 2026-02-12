<?php

use App\Http\Controllers\Api\V1\Sheba\ShebaIndexController;
use App\Http\Controllers\Api\V1\Sheba\ShebaStoreController;
use App\Http\Controllers\Api\V1\Sheba\ShebaUpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix( 'v1/sheba' )->group( function ()
{
    Route::post( '/', ShebaStoreController::class );
    Route::get( '/', ShebaIndexController::class );
    Route::put( '/{id}', ShebaUpdateController::class );
} );