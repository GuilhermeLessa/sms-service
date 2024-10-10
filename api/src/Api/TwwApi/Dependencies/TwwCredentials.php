<?php

namespace App\Api\TwwApi\Dependencies;

use Exception;

class TwwCredentials
{

    private string $user;
    private string $pass;

    public function __construct(
        string $user,
        string $pass
    ) {
        $this->user = $this->validateUser($user);
        $this->pass = $this->validatePass($pass);
    }

    private function validateUser(string $user): string
    {
        if (empty($user) || !is_string($user)) {
            throw new Exception('Invalid user.');
        }

        $user = trim($user);
        if (strlen($user) == 0) {
            throw new Exception('Invalid user.');
        }

        return $user;
    }

    private function validatePass(string $pass): string
    {
        if (empty($pass) || !is_string($pass)) {
            throw new Exception('Invalid pass.');
        }

        $pass = trim($pass);
        if (strlen($pass) == 0) {
            throw new Exception('Invalid pass.');
        }

        return $pass;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPass(): string
    {
        return $this->pass;
    }
}
