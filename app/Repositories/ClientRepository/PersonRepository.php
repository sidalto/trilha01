<?php

namespace App\Repositories\ClientRepository;

use App\Repositories\ClientRepository\ClientRepositoryInterface;
use App\Models\Client\ClientInterface;
use DateTimeImmutable;
use PDO;

class PersonRepository implements ClientRepositoryInterface
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
        $sql = "SELECT * FROM clients WHERE NOT is_company ";
        $stmt = $this->connection->query($sql);

        $clientsList = [];

        while ($clientData = $stmt->fetch()) {
            $clientsList[] = new $this->client(
                (int)$clientData['id'],
                $clientData['address'],
                $clientData['telephone'],
                new DateTimeImmutable($clientData['created_at']),
                $clientData['person_name'],
                $clientData['cpf'],
                $clientData['rg'],
                $clientData['birth_date'] ? new DateTimeImmutable($clientData['birth_date']) : NULL,
                $clientData['updated_at'] ? new DateTimeImmutable($clientData['updated_at']) : NULL
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
