<?php

namespace App\Models\Customer;

use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;

class CustomerPerson implements CustomerInterface
{
    /**
     * @var CustomerAccountInterface[] $accounts
     */
    private $customerAccounts = [];
    private string $address;
    private string $telephone;
    private ?DateTimeImmutable $created_at;
    private string $email;
    private string $password;
    private int $id;
    private string $personName;
    private string $cpf;
    private string $rg;
    private ?DateTimeImmutable $birthDate;
    private ?DateTimeImmutable $updated_at;

    public function fill(
        string $address,
        string $telephone,
        string $email,
        string $password,
        ?string $personName,
        ?string $cpf,
        ?string $rg,
        DateTimeImmutable $birthDate,
        int $id = 0,
        ?DateTimeImmutable $created_at = null,
        ?DateTimeImmutable $updated_at = null
    ) {
        $this->address = $address;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->password = $password;
        $this->personName = $personName;
        $this->cpf = $cpf;
        $this->rg = $rg;
        $this->birthDate = $birthDate;
        $this->id = $id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function addAccount(CustomerAccountInterface $customerAccount): void
    {
        $this->customerAccounts[] = $customerAccount;
    }

    public function getAccounts(): array
    {
        return $this->customerAccounts;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPersonName(): ?string
    {
        return $this->personName;
    }

    public function setPersonName(string $personName): void
    {
        $this->personName = $personName;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getRg(): ?string
    {
        return $this->rg;
    }

    public function setRg(string $rg): void
    {
        $this->rg = $rg;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
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
