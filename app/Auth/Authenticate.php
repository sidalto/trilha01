<?php

namespace App\Auth;

use Exception;
use Firebase\JWT\JWT;
use App\Database\Connection;
use App\Models\Customer\CustomerPerson;
use App\Models\Customer\CustomerCompany;
use App\Models\Customer\CustomerInterface;
use App\Repositories\CustomerRepository\CustomerPersonRepository;
use App\Repositories\CustomerRepository\CustomerCompanyRepository;
use function App\Helpers\response;

class Authenticate
{
    private const KEY = '234fdg4564fg234255dfr@#fdgkjs';
    private CustomerPersonRepository $customerRepository;
    private CustomerCompanyRepository $companyRepository;
    private ?CustomerPerson $customer;
    private ?CustomerCompany $company;

    public function __construct()
    {
        $this->customerRepository = new CustomerPersonRepository(Connection::getInstance());
        $this->companyRepository = new CustomerCompanyRepository(Connection::getInstance());
        $this->customer = new CustomerPerson();
        $this->company = new CustomerCompany();
    }

    public function generateToken(CustomerInterface $customer)
    {
        $payload = [
            'id' => $customer->getId(),
            'email' => $customer->getEmail()
        ];

        $token = JWT::encode($payload, self::KEY);

        return ['token' => $token];
    }

    public function authenticate(string $email, string $password)
    {
        if (!isset($email) || !isset($password)) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => 'User and password required'
                ]);
        }

        $this->customer = $this->customerRepository->findByEmail($email);
        $this->company = $this->companyRepository->findByEmail($email);

        if (!$this->customer && !$this->company) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => 'User or password invalid'
                ]);
        } elseif ($this->customer) {
            $customer = $this->customer;
        } else {
            $customer = $this->company;
        }

        if (!password_verify($password, $customer->getPassword())) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => 'User or password invalid'
                ]);
        }

        return $this->generateToken($this->customer);
    }

    public function verifyAuth()
    {
        $token = getallheaders();
        try {
            if (!isset($token['Authorization'])) {
                response()
                    ->httpCode(400)
                    ->json([
                        'message' => 'Access token not found'
                    ]);
            }

            $token = str_replace('Bearer ', '', $token['Authorization']);
            $decode = (array)JWT::decode($token, self::KEY, ['HS256']);
            $email = $decode['email'];

            $this->customer = $this->customerRepository->findByEmail($email);
            $this->company = $this->companyRepository->findByEmail($email);

            if (!$this->customer && !$this->company) {
                response()
                    ->httpCode(400)
                    ->json([
                        'message' => 'Invalid access token'
                    ]);
            }

            if ($this->customer) {
                $customer = $this->customer;
            } else {
                $customer = $this->company;
            }

            $data = [
                'id' => $customer->getId(),
                'email' => $customer->getEmail(),
                'token' => $token,
                'name' => ($customer instanceof CustomerPerson) ? $customer->getPersonName() : $customer->getCompanyName()
            ];

            return $data;
        } catch (Exception $e) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => 'Invalid access token'
                ]);
        }
    }
}
