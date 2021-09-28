<?php

use Illuminate\Support\Facades\Route;
use Nevadskiy\Money\Http\Controllers\Api;
use Nevadskiy\Money\Http\Middleware\SetCurrencyMiddleware;

Route::get('currencies', [Api\CurrencyController::class, 'index'])
    ->name('api.currencies.index');

Route::get('currencies/convert', Api\CurrencyConvertController::class)
    ->name('api.currencies.convert')
    ->middleware(SetCurrencyMiddleware::class);
