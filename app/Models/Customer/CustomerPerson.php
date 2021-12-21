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

    /**
     * @param string $address
     * @param string $telephone
     * @param string $email
     * @param string $password
     * @param string|null $personName
     * @param string|null $cpf
     * @param string|null $rg
     * @param DateTimeImmutable $birthDate
     * @param int $id
     * @param DateTimeImmutable|null $created_at
     * @param DateTimeImmutable|null $updated_at
     */
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
    ): void {
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
     * @return string|null
     */
    public function getPersonName(): ?string
    {
        return $this->personName;
    }

    /**
     * @param string $personName
     */
    public function setPersonName(string $personName): void
    {
        $this->personName = $personName;
    }

    /**
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    /**
     * @param string $cpf
     */
    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return string|null
     */
    public function getRg(): ?string
    {
        return $this->rg;
    }

    /**
     * @param string $rg
     */
    public function setRg(string $rg): void
    {
        $this->rg = $rg;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    /**
     * @param DateTimeImmutable $birthDate
     */
    public function setBirthDate(DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
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
