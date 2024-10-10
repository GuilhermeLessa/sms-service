<?php

namespace App\Services\SmsService;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Services\AbstractService;

use App\Repository\ParamsRepository;
use App\Api\TwwApi\TwwApi;
use App\Api\TwwApi\Dependencies\TwwUrl;
use App\Api\TwwApi\Dependencies\TwwCredentials;
use App\Api\TwwApi\TwwSms;
use App\Api\TwwApi\Dependencies\TwwResponse;

class SmsService extends AbstractService
{

    private TwwApi $twwApi;

    public function __construct(
        HttpClientInterface $http,
        ParamsRepository $paramsRepository,
        SmsServiceParams $params
    ) {
        $this->loadApi($http, $paramsRepository, $params);
    }

    private function loadApi(
        HttpClientInterface $http,
        ParamsRepository $paramsRepository,
        SmsServiceParams $params
    ) {
        $params = new SmsServiceParams($paramsRepository);
        $url = $params->getUrl();
        $user = $params->getUser();
        $pass = $params->getPass();

        $url = new TwwUrl($url);
        $credentials = new TwwCredentials($user, $pass);
        $this->twwApi = new TwwApi($http, $url, $credentials);
    }

    public function send(string  $to, string $message): TwwResponse
    {
        $sms = new TwwSMS(TwwSms::DEFAULT_FROM, $to, $message);

        /** @var TwwResponse $response */
        $response = $this->twwApi->send($sms);

        return $response;
    }

    public function sendPhoneNumberConfirmation(string  $to, string $link): TwwResponse
    {
        $message = "Confirm your mobile phone number clicking here: {$link}";

        $sms = new TwwSMS(TwwSms::DEFAULT_FROM, $to, $message);

        /** @var TwwResponse $response */
        $response = $this->twwApi->send($sms);

        return $response;
    }
}
