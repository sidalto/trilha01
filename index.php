<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Request;
use App\Routes\Router;
use App\Routes\Routes;

$request = new Request();
$router = new Router($request);
$routes = new Routes($request, $router);

$routes->run();

// use App\Http\Request;
// use App\Routes\Router;
// use App\Database\Connection;
// use App\Models\Customer\CustomerPerson;
// use App\Models\Customer\CustomerCompany;
// use App\Repositories\CustomerRepository\CustomerPersonRepository;
// use App\Repositories\CustomerRepository\CustomerCompanyRepository;


// $request = new Request();
// $router = new Router($request);
// // $router->get('/login', 'CustomerCompanyController@index');
// $router->get('/index', 'CustomerCompanyController@index');
// $router->get('/company/id', 'CustomerCompanyController@getById');
// $router->post('/edit/1', 'CustomerCompanyController@index');


// $connection = Connection::getInstance();

// $customerPerson = new CustomerPerson(
//     'Rua A',
//     '(11) 11111-1111',
//     new DateTimeImmutable('now'),
//     'person@person.com.br',
//     'senha123456',
//     0,
//     'Ana',
//     '111.111.111-11',
//     'UF11.111.111',
//     new DateTimeImmutable('now'),
//     new DateTimeImmutable('now'),
//     null,
//     null
// );

// $customerPersonRepository = new CustomerPersonRepository($connection, $customerPerson);

// $customerPerson = $customerPersonRepository->add($customerPerson);
// $customerPerson = $customerPersonRepository->getById($customerPerson);
// // var_dump($customerPerson);

// $customerPerson->setPersonName('João');
// $customerPerson->setCpf('111.222.000-00');
// $customerPerson = $customerPersonRepository->edit($customerPerson);
// $customerPerson = $customerPersonRepository->getById($customerPerson);
// // var_dump($customerPerson);

// // $customerPersonRepository->remove($customerPerson);
// // var_dump($customerPersonRepository->getAll());


// $customerCompany = new CustomerCompany(
//     'Rua C',
//     '(11) 22222-2222',
//     new DateTimeImmutable('now'),
//     'companhia@companhia.com.br',
//     'senha123456',
//     11,
//     'Companhia',
//     '00.000.000/0000-00',
//     '333344445555',
//     new DateTimeImmutable('now'),
//     new DateTimeImmutable('now'),
//     null,
//     null
// );

// $customerCompanyRepository = new CustomerCompanyRepository($connection, $customerCompany);

// $customerCompany = $customerCompanyRepository->add($customerCompany);
// $customerCompany = $customerCompanyRepository->getById($customerCompany);
// // var_dump($customerCompany);

// $customerCompany->setCompanyName('The Company');
// $customerCompany->setCnpj('11.000.000/0000-00');
// $customerCompany = $customerCompanyRepository->edit($customerCompany);
// $customerCompany = $customerCompanyRepository->getById($customerCompany);
// // var_dump($customerCompany);

// // $customerCompanyRepository->remove($customerCompany);
// // var_dump($customerCompanyRepository->getAll());

// var_dump($customerPersonRepository->getAll(), $customerCompanyRepository->getAll());
