<?php

namespace App\Database;

interface DatabaseInterface
{
    public function getInstance(): PDO;
}