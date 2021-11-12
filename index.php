<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Connection;
use App\Repositories\ClientRepository\CompanyRepository;
use App\Repositories\ClientRepository\PersonRepository;
use App\Models\Client\Company;
use App\Models\Client\Person;

$connection = Connection::getInstance();

$company = new Company(
    11,
    new DateTimeImmutable('now'),
    'Rua 1',
    '(11)12312-1231',
    'Companhia',
    '123456',
    '456789',
    new DateTimeImmutable('now'),
    new DateTimeImmutable('now'),
    null,
    null
);

$person = new Person(
    11,
    'Rua 1',
    '(11)12312-1231',
    new DateTimeImmutable('now'),
    'Teste',
    '123456',
    '456789',
    new DateTimeImmutable('now'),
    new DateTimeImmutable('now'),
    null,
    null
);

$companyRepository = new CompanyRepository($connection, $company);
$personRepository = new PersonRepository($connection, $person);

$resultCompany = $companyRepository->getAll();
$resultPerson = $personRepository->getAll();

var_dump($resultPerson, $resultCompany);
