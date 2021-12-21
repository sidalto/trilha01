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

    /**
     * @param string $address
     * @param string $telephone
     * @param string $email
     * @param string $password
     * @param string $companyName
     * @param string $cnpj
     * @param string $stateRegistration
     * @param DateTimeImmutable $foundationDate
     * @param int $id
     * @param DateTimeImmutable|null $created_at
     * @param DateTimeImmutable|null $updated_at
     */
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
    ): void {
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

    /**
     * @param CustomerAccountInterface $customerAccount
     */
    public function addAccount(CustomerAccountInterface $customerAccount): void
    {
        $this->customerAccounts[] = $customerAccount;
    }

    /**
     * @return array
     */
    public function getAccounts(): array
    {
        return $this->customerAccounts;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    /**
     * @param string $cnpj
     */
    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    /**
     * @return string
     */
    public function getStateRegistration(): string
    {
        return $this->stateRegistration;
    }

    /**
     * @param string $stateRegistration
     */
    public function setStateRegistration(string $stateRegistration): void
    {
        $this->stateRegistration = $stateRegistration;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFoundationDate(): ?DateTimeImmutable
    {
        return $this->foundationDate;
    }

    /**
     * @param DateTimeImmutable $foundationDate
     */
    public function setFoundationDate(DateTimeImmutable $foundationDate): void
    {
        $this->foundationDate = $foundationDate;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
