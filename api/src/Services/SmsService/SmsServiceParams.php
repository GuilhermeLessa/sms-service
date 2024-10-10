<?php

namespace App\Services\SmsService;

use Exception;

use App\Repository\ParamsRepository;

class SmsServiceParams
{

    private ParamsRepository $paramsRepository;

    private string $url;
    private string $user;
    private string $pass;

    public function __construct(
        ParamsRepository $paramsRepository,
    ) {
        $this->paramsRepository = $paramsRepository;
        $this->url = $this->findUrl();
        $this->user = $this->findUser();
        $this->pass = $this->findPass();
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPass(): string
    {
        return $this->pass;
    }

    private function findUrl(): string
    {
        $url = $this->paramsRepository->getParam("SMS_URL");
        if (!$url) {
            throw new Exception("SmsService param not found: SMS_URL.");
        }
        return $url->getValor();
    }

    private function findUser(): string
    {
        $user = $this->paramsRepository->getParam("SMS_USERNAME");
        if (!$user) {
            throw new Exception("SmsService param not found: SMS_USERNAME.");
        }
        return $user->getValor();
    }

    private function findPass(): string
    {
        $pass = $this->paramsRepository->getParam("SMS_PASSWORD");
        if (!$pass) {
            throw new Exception("SmsService param not found: SMS_PASSWORD.");
        }
        return $pass->getValor();
    }
}
