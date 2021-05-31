<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::post('/messages', [MessageController::class, 'store'])->name('api.messages.store');
Route::post('/messages/{message}/predict', [MessageController::class, 'predict'])->name('api.messages.predict');
Route::post('/messages/{message}/report', [MessageController::class, 'report'])->name('api.messages.report');
