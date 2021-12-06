<?php

namespace App\Controllers;

use App\Database\Connection;
use App\Models\CustomerAccount\CustomerAccount;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepository;
use App\Repositories\CustomerAccountRepository\CustomerAccountRepositoryInterface;
use function App\Helpers\input;
use function App\Helpers\response;

class HomeController
{
    public function index()
    {
        return response()
            ->httpCode(200)
            ->json([
                'message' => 'Teste',
                // 'data' => $token
            ]);
    }
}
