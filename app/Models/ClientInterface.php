<?php

namespace App\Models;

interface ClientInterface
{
    public function getAddress(): string;

    public function getTelephone(): string;

    public function getEmail(): string;

    public function getPassword(): string;

    public function isAuthenticate(): bool;
}