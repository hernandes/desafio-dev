<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionsController;


Route::get('transactions', [TransactionsController::class, 'index']);
Route::post('transactions/import', [TransactionsController::class, 'import']);
