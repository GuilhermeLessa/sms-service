<?php

namespace App\Api\TwwApi;

use Exception;

class TwwSms
{

    const DEFAULT_FROM = "1";

    private string $from;
    private string $to;
    private string $message;

    public function __construct(
        string $from,
        string $to,
        string $message
    ) {
        $this->from = $this->validateFrom($from);
        $this->to = $this->validateTo($to);
        $this->message = $this->validateMessage($message);
    }

    private function validateFrom(string $from): string
    {
        if (empty($from) || !is_string($from)) {
            throw new Exception('Invalid from.');
        }

        $from = preg_replace('/\D/', '', $from);
        if (strlen($from) == 0) {
            throw new Exception('Invalid from.');
        }

        return $from;
    }

    private function validateTo(string $to): string
    {
        if (empty($to) || !is_string($to)) {
            throw new Exception('Invalid to.');
        }

        $to = preg_replace('/\D/', '', $to);
        if (!(strlen($to) == 12 || strlen($to) == 13)) {
            throw new Exception('Invalid to.');
        }

        return $to;
    }

    private function validateMessage(string $message): string
    {
        if (empty($message) || !is_string($message)) {
            throw new Exception('Invalid message.');
        }

        $message = trim($message);
        if (strlen($message) == 0) {
            throw new Exception('Invalid message.');
        }

        return $message;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
