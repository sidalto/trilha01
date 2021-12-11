<?php

namespace App\Models\Customer;

use DateTimeImmutable;
use App\Models\Customer\CustomerInterface;
use App\Models\CustomerAccount\CustomerAccountInterface;

class CustomerCompany implements CustomerInterface
{
    /**
     * @var CustomerAccountInterface[] $customerAccounts
     */
    public array $customerAccounts;
    private string $address;
    private string $telephone;
    private string $email;
    private string $password;
    private int $id;
    private string $companyName;
    private string $cnpj;
    private string $stateRegistration;
    private DateTimeImmutable $foundationDate;
    private ?DateTimeImmutable $created_at;
    private ?DateTimeImmutable $updated_at;

    public function fill(
        string $address,
        string $telephone,
        string $email,
        string $password,
        string $companyName,
        string $cnpj,
        string $stateRegistration,
        DateTimeImmutable $foundationDate,
        int $id = 0,
        ?DateTimeImmutable $created_at = null,
        ?DateTimeImmutable $updated_at = null
    ) {
        $this->address = $address;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->password = $password;
        $this->companyName = $companyName;
        $this->cnpj = $cnpj;
        $this->stateRegistration = $stateRegistration;
        $this->foundationDate = $foundationDate;
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

    public function setAddress(string $address): void
    {
        $this->address = $address;
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
