<?php

namespace App\Models;

use App\Models;
use App\Models\ClientInterface;
use DateTimeImmutable;

class Person implements ClientInterface
{
    private int $id;
    private ?string $personName;
    private ?string $cpf;
    private ?string $rg;
    private DateTimeImmutable $birthDate;
    private string $address;
    private string $telephone;
    private DateTimeImmutable $created_at;
    private ?DateTimeImmutable $updated_at;

    public function __construct(
        int $id,
        DateTimeImmutable $created_at,
        string $address,
        string $telephone,
        string $personName = null,
        string $cpf = null,
        string $rg = null,
        DateTimeImmutable $birthDate = null,
        DateTimeImmutable $updated_at = null
    )
    {
        $this->id = $id;
        $this->created_at = $created_at;
        $this->address = $address;
        $this->telephone = $telephone;
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

}