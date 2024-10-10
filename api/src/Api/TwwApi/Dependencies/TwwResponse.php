<?php

namespace App\Api\TwwApi\Dependencies;

class TwwResponse
{

    private int $statusCode;
    private array $content;

    public function __construct(
        int $statusCode,
        array $content
    ) {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
