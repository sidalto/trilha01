<?php

namespace App\Http;

class Request
{
    private string $httpMethod;
    private string $baseUrl = 'http://localhost/trilha01';
    private string $prefix = 'trilha01';
    private string $uri;
    private array $queryParams;
    private array $postData;
    private array $headers;

    public function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        $this->removePrefixUri();
        $this->queryParams = $_GET ?? [];
        $this->postData = $_POST ?? [];
        $this->headers = getallheaders();
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    private function removePrefixUri(): void
    {
        $this->uri = substr($this->uri, (strlen($this->prefix) + 1));
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getPostData(): array
    {
        return $this->postData;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
