<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/conta', [AccountController::class, 'index']);
Route::post('/conta', [AccountController::class, 'addBalance']);
Route::post('/transacao', [TransactionController::class, 'makeTransaction']);
