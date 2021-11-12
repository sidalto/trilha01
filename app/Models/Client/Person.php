<?php

namespace App\Models\Client;

use App\Models\Client\ClientInterface;
use DateTimeImmutable;

class Person implements ClientInterface
{
    private int $id;
    private string $address;
    private string $telephone;
    private DateTimeImmutable $created_at;
    private ?string $personName;
    private ?string $cpf;
    private ?string $rg;
    private ?DateTimeImmutable $birthDate;
    private ?DateTimeImmutable $updated_at;

    public function __construct(
        int $id,
        string $address,
        string $telephone,
        DateTimeImmutable $created_at,
        ?string $personName,
        ?string $cpf,
        ?string $rg,
        ?DateTimeImmutable $birthDate,
        ?DateTimeImmutable $updated_at
    ) {
        $this->id = $id;
        $this->address = $address;
        $this->telephone = $telephone;
        $this->created_at = $created_at;
        $this->personName = $personName;
        $this->cpf = $cpf;
        $this->rg = $rg;
        $this->birthDate = $birthDate;
        $this->updated_at = $updated_at;
    }

    public function getAddress(): string
    {
        return '';
    }

    public function getTelephone(): string
    {
        return '';
    }

    public function getEmail(): string
    {
        return '';
    }

    public function getPassword(): string
    {
        return '';
    }

    public function isAuthenticate(): bool
    {
        return true;
    }

    public function isCompany(): bool
    {
        return false;
    }
}
