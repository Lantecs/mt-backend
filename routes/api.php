<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SummaryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get("/educexpenses", [SummaryController::class, "educExpenses"])
        ->name("educ.expenses");
    Route::get("/entexpenses", [SummaryController::class, "entExpenses"])
        ->name("ent.expenses");
    Route::get("/foodexpenses", [SummaryController::class, "foodExpenses"])
        ->name("food.expenses");
    Route::get("/healthexpenses", [SummaryController::class, "healthExpenses"])
        ->name("health.expenses");

    Route::get("/miscexpenses", [SummaryController::class, "miscExpenses"])
        ->name("misc.expenses");
    Route::get("/shopexpenses", [SummaryController::class, "shopExpenses"])
        ->name("shop.expenses");
    Route::get("/transexpenses", [SummaryController::class, "transExpenses"])
        ->name("trans.expenses");
    Route::get("/utilexpenses", [SummaryController::class, "utilExpenses"])
        ->name("util.expenses");
    Route::get("/barchart", [SummaryController::class, "barChart"])
        ->name("bar.chart");


    Route::controller(IncomeController::class)->group(function () {
        Route::get('/get-user-incomes', 'getUserIncomes')->name('get.user.incomes');
        Route::post('/incomeadd', 'incomeAdd')->name('income.add');
        Route::get('/edit-user-income/{id}', 'incomeEdit')->name('get.edit.income');
        Route::delete('/incomedelete/{id}', 'incomeDelete')->name('income.delete');
        Route::post('/incomesave/{id}', 'incomeSave')->name('income.save');
        Route::get('/dailyincome', 'dailyIncome')->name('get.daily.income');
        Route::get('/dailyspending', 'dailySpending')->name('get.daily.spending');
        Route::get('/dailysaving', 'dailySaving')->name('get.daily.saving');
        Route::get('/weeklyIncome', 'weeklyIncome')->name('get.weekly.income');
        Route::get('/weeklyspending', 'weeklySpending')->name('get.weekly.spending');
        Route::get('/weeklysaving', 'weeklySaving')->name('get.weekly.saving');
    });

    Route::controller(ExpensesController::class)->group(function () {
        Route::get('/get-user-expenses', 'getUserExpenses')->name('get.user.expenses');
        Route::post('/expensesadd', 'expensesAdd')->name('expenses.add');
        Route::get('/edit-user-expenses/{id}', 'expensesEdit')->name('get.edit.expenses');
        Route::delete('/expensesdelete/{id}', 'expensesDelete')->name('expenses.delete');
        Route::post('/expensessave/{id}', 'expensesSave')->name('expenses.save');

    });

    Route::controller(BudgetController::class)->group(function () {
        Route::get('/get-user-budgets', 'getUserBudgets')->name('get.user.budgets');
        Route::post('/budgetadd', 'budgetAdd')->name('budget.add');
        Route::get('/edit-user-budget/{id}', 'budgetEdit')->name('get.edit.budget');
        Route::post('/budgetsave/{id}', 'budgetSave')->name('budget.save');
        Route::delete('/budgetdelete/{id}', 'budgetDelete')->name('budget.delete');
        Route::get('/budgetedit/{id}', 'budgetEdit')->name('budget.edit');
    });

    Route::get('/logout', [AuthController::class, 'logOut']);
});



Route::post('/registeruser', [AuthController::class, 'registerUser']);
Route::post('/loginuser', [AuthController::class, 'loginUser']);
Route::post('/forget-password', [AuthController::class, 'forgetpassword']);
Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword']);
