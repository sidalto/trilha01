<?php

namespace App\Repositories\ClientRepository;

use App\Database\Connection;
use App\Repositories\ClientRepository\ClientRepositoryInterface;
use App\Models\ClientInterface;
use App\Models\Company;
use DateTimeImmutable;
use PDO;

class CompanyRepository implements ClientRepositoryInterface
{
    private PDO $connection;
    private ClientInterface $client;

    public function __construct(PDO $connection, ClientInterface $client)
    {
        $this->connection = $connection;
        $this->client = $client;
    }

    /**
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM clients WHERE is_company = 1";
        $stmt = $this->connection->query($sql);
        $stmt->execute();

        $clientsList = [];

        while ($clientData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientsList[] = new Company(
                (int)$clientData['id'],
                new DateTimeImmutable($clientData['created_at']),
                $clientData['address'],
                $clientData['telephone'],
                $clientData['company_name'],
                $clientData['cnpj'],
                $clientData['state_registration'],
                new DateTimeImmutable($clientData['foundation_date']),
                new DateTimeImmutable($clientData['updated_at'])
            );
        }

        return $clientsList;
    }

    /**
     *
     *
     * @return ClientInterface
     */
    public function getById(): ClientInterface
    {
        return $this->client;
    }

    /**
     *
     * @var ClientInterface $client
     * @return bool
     */
    public function add(ClientInterface $client): bool
    {
        return true;
    }

    /**
     *
     * @var int $id
     * @return ClientInterface
     */
    public function edit(int $id): ClientInterface
    {
        return $this->client;
    }

    /**
     *
     * @var int $id
     * @return bool
     */
    public function remove(int $id): bool
    {
        return true;
    }
}