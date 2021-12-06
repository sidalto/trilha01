<?php

namespace App\Routes;

use Pecee\SimpleRouter\SimpleRouter;
use App\Controllers\HomeController;
use App\Controllers\AccountController;
use App\Controllers\CompanyController;
use App\Controllers\CustomerController;
use App\Controllers\TransactionController;
use App\Controllers\AuthenticateController;
use App\Middlewares\AuthMiddleware;

// SimpleRouter::get('/trilha01/login', [LoginController::class, 'index']);
SimpleRouter::post('/trilha01/auth', [AuthenticateController::class, 'index']);

SimpleRouter::group(['middleware' => AuthMiddleware::class], function () {
    SimpleRouter::post('/trilha01/dashboard', [HomeController::class, 'index']);

    SimpleRouter::get('/trilha01/companies', [CompanyController::class, 'index']);
    SimpleRouter::get('/trilha01/company/{company}', [CompanyController::class, 'getById']);
    SimpleRouter::post('/trilha01/company', [CompanyController::class, 'create']);
    SimpleRouter::put('/trilha01/company/{company}', [CompanyController::class, 'update']);
    SimpleRouter::delete('/trilha01/company/{company}', [CompanyController::class, 'delete']);

    SimpleRouter::get('/trilha01/customers', [CustomerController::class, 'index']);
    SimpleRouter::get('/trilha01/customer/{customer}', [CustomerController::class, 'getById']);
    SimpleRouter::post('/trilha01/customer', [CustomerController::class, 'create']);
    SimpleRouter::put('/trilha01/customer/{customer}', [CustomerController::class, 'update']);
    SimpleRouter::delete('/trilha01/customer/{customer}', [CustomerController::class, 'delete']);

    SimpleRouter::get('/trilha01/accounts/{customer}', [AccountController::class, 'index']);
    SimpleRouter::get('/trilha01/account/{account}/customer/{customer}', [AccountController::class, 'getById']);
    SimpleRouter::post('/trilha01/account', [AccountController::class, 'create']);
    SimpleRouter::put('/trilha01/account/{account}', [AccountController::class, 'update']);
    SimpleRouter::delete('/trilha01/account/{account}/customer/{customer}', [AccountController::class, 'delete']);

    SimpleRouter::get('/trilha01/transactions/{account}', [TransactionController::class, 'index']);
    SimpleRouter::get('/trilha01/transactions/{account}/{initialDate}/{finalDate}', [TransactionController::class, 'getReport']);
    SimpleRouter::put('/trilha01/transaction/{account}/withdraw', [TransactionController::class, 'withdraw']);
    SimpleRouter::put('/trilha01/transaction/{account}/deposit', [TransactionController::class, 'deposit']);
    SimpleRouter::put('/trilha01/transaction/{account}/transfer/', [TransactionController::class, 'transfer']);
});
