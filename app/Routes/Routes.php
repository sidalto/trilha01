<?php

namespace App\Routes;

use App\Http\Request;
use App\Routes\Router;

class Routes
{
    private Router $router;
    private Request $request;

    public function __construct(Request $request, Router $router)
    {
        $this->router = $router;
        $this->request = $request;
    }

    public function run()
    {
        $this->router->dispatch($this->request);
    }
}
