<?php

namespace App\Api\TwwApi;

use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

use App\Api\TwwApi\Dependencies\TwwCredentials;
use App\Api\TwwApi\Dependencies\TwwUrl;
use App\Api\TwwApi\Dependencies\TwwResponse;

class TwwApi
{
    private HttpClientInterface $http;
    private TwwUrl $url;
    private TwwCredentials $credentials;

    public function __construct(
        HttpClientInterface $http,
        TwwUrl $url,
        TwwCredentials $credentials
    ) {
        $this->http = $http;
        $this->url = $url;
        $this->credentials = $credentials;
    }

    public function setUrl(TwwUrl $url): void
    {
        $this->url = $url;
    }

    public function setCredentials(TwwCredentials $credentials): void
    {
        $this->credentials = $credentials;
    }

    public function send(TwwSms $sms): TwwResponse
    {
        $response =  $this->request("POST", "/EnviaSMS", [
            "NumUsu" => $this->credentials->getUser(),
            "Senha" => $this->credentials->getPass(),
            "SeuNum" => $sms->getFrom(),
            "Celular" => $sms->getTo(),
            "Mensagem" => $sms->getMessage(),
        ]);

        $status = $response["response"]["status"];
        $message = $response["response"]["content"]["d"];

        if ($status === Response::HTTP_OK && $message === "OK") {
            return new TwwResponse($status, ["message" => $message]);
        }

        $message = $message ? $message : "SMS Fail.";

        throw new Exception($message);
    }

    private function request(string $method, string $url, array $body = null): array
    {
        $fullUrl = $this->url->getUrl() . $url;

        $options = [
            'headers' => ['Content-Type' => 'application/json']
        ];

        if ($body !== null) {
            $options['body'] = is_string($body) ? $body : json_encode($body);
        }

        /** @var ResponseInterface $response */
        $response = $this->http->request($method, $fullUrl, $options);

        $status = $response->getStatusCode();

        $content = json_decode($response->getContent(false), true);

        return [
            "response" => ["status" => $status, "content" => $content]
        ];
    }
}
