<?php

use Illuminate\Support\Facades\Route;
use Nevadskiy\Money\Http\Controllers\Api;

Route::get('currencies', [Api\CurrencyController::class, 'index'])->name('api.currencies.index');
Route::get('currencies/convert', Api\CurrencyConvertController::class)->name('api.currencies.convert');
