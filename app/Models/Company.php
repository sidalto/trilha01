<?php

namespace App\Models;

use App\Models;
use App\Models\ClientInterface;
use DateTimeImmutable;

class Company implements ClientInterface
{
    private int $id;
    private ?string $companyName;
    private ?string $cnpj;
    private ?string $stateRegistration;
    private ?DateTimeImmutable $foundationDate;
    private string $address;
    private string $telephone;
    private DateTimeImmutable $created_at;
    private DateTimeImmutable $updated_at;

    public function __construct(
        int $id,
        DateTimeImmutable $created_at,
        string $address,
        string $telephone,
        string $companyName = null,
        string $cnpj = null,
        string $stateRegistration = null,
        DateTimeImmutable $foundationDate = null,
        DateTimeImmutable $updated_at = null
    )
    {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->address = $address;
        $this->telephone = $telephone;
        $this->companyName = $companyName;
        $this->cnpj = $cnpj;
        $this->stateRegistration = $stateRegistration;
        $this->foundationDate = $foundationDate;
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

}