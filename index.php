<?php

require_once 'vendor/autoload.php';

use App\Database\Connection;
use App\Repositories\ClientRepository\CompanyRepository;
use App\Repositories\ClientRepository\PersonRepository;
use App\Models\Company;
use App\Models\Person;

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
    new DateTimeImmutable('now'),
    'Rua 1',
    '(11)12312-1231',
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

//$exec = "INSERT INTO clients (person_name, address, telephone) VALUES ('Sidalto Teste', 'Rua 1', '(11)1111-1111')";

$resultCompany = $companyRepository->getAll();
//$resultPerson = $personRepository->getAll();

var_dump($resultCompany);