<?php

namespace App\Http;

class Response
{
    private string $httpCode;
    private array $headers;
    private array $content;

    public function __construct(string $httpCode, array $content)
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
    }

    public function setHeaders(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    private function sendHeaders()
    {
        http_response_code($this->httpCode);

        foreach ($this->headers as $key => $value) {
            header($key . ':' . $value);
        }
    }

    public function send()
    {
        $this->sendHeaders();

        echo json_encode($this->content);
    }
}
