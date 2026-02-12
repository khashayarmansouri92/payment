<?php

use App\Http\Controllers\Api\ShebaTransferController;
use Illuminate\Support\Facades\Route;

Route::post( '/sheba', [ ShebaTransferController::class, 'send' ] );