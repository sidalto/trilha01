<?php

namespace App\Repositories\ClientRepository;

use App\Models\ClientInterface;

interface ClientRepositoryInterface
{
    public function getAll(): array;

    public function getById(): ClientInterface;

    public function add(ClientInterface $client): bool;

    public function edit(int $id): ClientInterface;
    
    public function remove(int $id): bool;
}