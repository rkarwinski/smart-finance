<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;

Route::get('/savings', [SavingController::class, 'index']);
Route::post('/savings', [SavingController::class, 'store']);
Route::get('/savings/totals', [SavingController::class, 'getTotals']);
Route::get('/savings/filter/{month}/{year}', [SavingController::class, 'filterByMonth']);
Route::get('/savings/{id}', [SavingController::class, 'show']);
Route::put('/savings/{id}', [SavingController::class, 'update']);
Route::delete('/savings/{id}', [SavingController::class, 'destroy']);

Route::get('/expenses', [ExpenseController::class, 'index']);
Route::post('/expenses', [ExpenseController::class, 'store']);
Route::get('/expenses/filter/{month}/{year}', [ExpenseController::class, 'filterByMonth']);
Route::get('/expenses/filter-year/{year}', [ExpenseController::class, 'filterByYear']);
Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

Route::get('/incomes', [IncomeController::class, 'index']);
Route::post('/incomes', [IncomeController::class, 'store']);
Route::get('/incomes/filter/{month}/{year}', [IncomeController::class, 'filterByMonth']);
Route::get('/incomes/filter-year/{year}', [IncomeController::class, 'filterByYear']);
Route::get('/incomes/{id}', [IncomeController::class, 'show']);
Route::put('/incomes/{id}', [IncomeController::class, 'update']);
Route::delete('/incomes/{id}', [IncomeController::class, 'destroy']);
