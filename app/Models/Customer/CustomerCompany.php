<?php

namespace App\Models\Customer;

use App\Models\Customer\CustomerInterface;
use DateTimeImmutable;

class CustomerCompany implements CustomerInterface
{
    private string $address;
    private string $telephone;
    private DateTimeImmutable $created_at;
    private string $email;
    private string $password;
    private ?int $id;
    private ?string $companyName;
    private ?string $cnpj;
    private ?string $stateRegistration;
    private ?DateTimeImmutable $foundationDate;
    private ?DateTimeImmutable $updated_at;

    public function __construct(
        string $address,
        string $telephone,
        DateTimeImmutable $created_at,
        string $email,
        string $password,
        ?int $id,
        ?string $companyName,
        ?string $cnpj,
        ?string $stateRegistration,
        ?DateTimeImmutable $foundationDate,
        ?DateTimeImmutable $updated_at
    ) {
        $this->address = $address;
        $this->telephone = $telephone;
        $this->created_at = $created_at;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
        $this->companyName = $companyName;
        $this->cnpj = $cnpj;
        $this->stateRegistration = $stateRegistration;
        $this->foundationDate = $foundationDate;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    public function getStateRegistration(): string
    {
        return $this->stateRegistration;
    }

    public function setStateRegistration(string $stateRegistration): void
    {
        $this->stateRegistration = $stateRegistration;
    }

    public function getFoundationDate(): ?DateTimeImmutable
    {
        return $this->foundationDate;
    }

    public function setFoundationDate(DateTimeImmutable $foundationDate): void
    {
        $this->foundationDate = $foundationDate;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
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
