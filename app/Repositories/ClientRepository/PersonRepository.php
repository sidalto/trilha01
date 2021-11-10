<?php

namespace App\Repositories\ClientRepository;

use App\Database\Connection;
use App\Repositories\ClientRepository\ClientRepositoryInterface;
use App\Models\ClientInterface;
use App\Models\Person;
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
        $sql = "SELECT * FROM clients";
        $stmt = $this->connection->query($sql);

        $clientsList = [];

        while ($clientData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientsList[] = new Person(
                (int)$clientData['id'],
                new DateTimeImmutable($clientData['created_at']),
                $clientData['address'],
                $clientData['telephone'],
                $clientData['person_name'],
                $clientData['cpf'],
                $clientData['rg'],
                new DateTimeImmutable($clientData['birth_date']),
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