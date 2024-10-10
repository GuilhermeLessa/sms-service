<?php

namespace App\Api\TwwApi\Dependencies;

use Exception;

class TwwUrl
{

    private string $url;

    public function __construct(
        string $url
    ) {
        $this->url = $this->validateUrl($url);
    }

    private function validateUrl(string $url): string
    {
        if (empty($url) || !is_string($url)) {
            throw new Exception('Invalid url');
        }

        $url = trim($url);
        if (strlen($url) == 0) {
            throw new Exception('Invalid url');
        }

        return $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
