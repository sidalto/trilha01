<?php

namespace App\Routes;

use PDO;
use App\Routes\Router;
use App\Database\Connection;
use App\Models\Customer\CustomerPerson;
use App\Models\Customer\CustomerCompany;
use App\Models\CustomerAccount\CustomerAccount;
use App\Controllers\CustomerPersonController;
use App\Controllers\CustomerCompanyController;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;

class Routes
{
    private PDO $connection;
    private CustomerCompany $company;
    private CustomerPerson $customer;
    private CustomerAccount $account;
    private CustomerCompanyRepository $companyRepository;
    private CustomerPersonRepository $customerRepository;
    private CustomerCompanyController $companyController;
    private CustomerPersonController $customerController;
    private Router $router;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $this->customer = new CustomerPerson();
        $this->company = new CustomerCompany();
        $this->account = new CustomerAccount();
        $this->customerRepository = new CustomerPersonRepository($this->connection, $this->customer, $this->account);
        $this->companyRepository = new CustomerCompanyRepository($this->connection);
        $this->companyController = new CustomerCompanyController($this->companyRepository, $this->company);
        $this->customerController = new CustomerPersonController($this->customerRepository, $this->customer);
        $this->router = new Router();
    }

    public function run()
    {
        // $this->router->get(
        //     '/',
        //     'HomeController@index'
        // );

        // $this->router->get(
        //     '/login',
        //     'HomeController@index'
        // );

        $this->router->get(
            '/companies',
            function ($params) {
                $this->companyController->index();
            }
        );

        $this->router->get(
            '/company',
            function ($params) {
                $this->companyController->getById($params);
            }
        );

        $this->router->post(
            '/company',
            function ($params) {
                $this->companyController->create($params);
            }
        );

        $this->router->put(
            '/company',
            function ($params) {
                $this->companyController->update($params);
            }
        );

        $this->router->delete(
            '/company',
            function ($params) {
                $this->companyController->delete($params);
            }
        );
    }
}


// $customer = new CustomerPerson();
// $customerRepository = new CustomerPersonRepository($connection, $customer);




// $router->get('/companies', 'CustomerCompanyController@index');
// $router->post('/company', 'CustomerCompanyController@create');
// $router->put('/company', 'CustomerCompanyController@update');
// $router->delete('/company', 'CustomerCompanyController@delete');

// $router->get('/customers', 'CustomerPersonController@index');
// $router->get('/customer', 'CustomerPersonController@getById');
// $router->post('/customer', 'CustomerPersonController@create');
// $router->put('/customer', 'CustomerPersonController@update');

// $router->delete('/company/1', 'CustomerCompanyController@remove');
