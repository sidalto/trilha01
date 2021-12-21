<?php

namespace App\Auth;

use Firebase\JWT\JWT;
use Exception;
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

    /**
     * @param CustomerInterface $customer
     * @return array
     */
    public function generateToken(CustomerInterface $customer): array
    {
        try {
            $payload = [
                'id' => $customer->getId(),
                'email' => $customer->getEmail()
            ];
            $token = JWT::encode($payload, self::KEY);

            if (!$token) {
                throw new Exception("Erro ao gerar o token de acesso");
            }

            return ['token' => $token];
        } catch (Exception $e) {
            response()
                ->httpCode(400)
                ->json([
                    'message' => $e->getMessage()
                ]);
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     */
    public function authenticate(string $email, string $password): array
    {
        try {
            if (!isset($email) || !isset($password)) {
                throw new Exception("Email e senha são obrigatórios");
            }
            $this->customer = $this->customerRepository->findByEmail($email);
            $this->company = $this->companyRepository->findByEmail($email);

            if (!$this->customer && !$this->company) {
                throw new Exception("Cliente inexistente, por favor cadastre-se");
            } elseif ($this->customer) {
                $user = $this->customer;
            } else {
                $user = $this->company;
            }

            if (!password_verify($password, $user->getPassword())) {
                throw new Exception("Email ou senha inválidos");
            }

            return $this->generateToken($user);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function verifyAuth(): array
    {
        try {
            $token = getallheaders();

            if (!isset($token['Authorization'])) {
                throw new Exception("Token de acesso não informado");
            }

            $token = str_replace('Bearer ', '', $token['Authorization']);
            $decode = (array)JWT::decode($token, self::KEY, ['HS256']);
            $email = $decode['email'];
            $this->customer = $this->customerRepository->findByEmail($email);
            $this->company = $this->companyRepository->findByEmail($email);

            if (!$this->customer && !$this->company) {
                throw new Exception("Token de acesso inválido");
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
                    'message' => 'Token inválido'
                ]);
        }
    }
}
