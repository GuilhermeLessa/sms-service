<?php

namespace App\Controller;

use Exception;
use App\Entity\AbstractEntity;
use App\Exception\EntityValidationException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

use App\Services\SmsService\SmsService;
use App\Api\TwwApi\Dependencies\TwwResponse;

/**
 * @Route("api/app/sms", name="api_app_sms")
 */
class SmsController extends AbstractRestController
{

    /** @var SmsService */
    private $service;

    public function __construct(
        ContainerInterface $appContainer,
        SmsService $service
    ) {
        parent::__construct($appContainer);
        $this->service = $service;
    }

    /**
     * @Rest\Post("/send", name="_send")
     */
    public function send(Request $request): JsonResponse
    {
        try {
            $to = $request->get('to');
            if (empty($to) || !is_string($to)) {
                throw new Exception('Invalid to');
            }

            $to = preg_replace('/\D/', '', $to);
            if (!(strlen($to) == 12 || strlen($to) == 13)) {
                throw new Exception('Invalid to');
            }

            $message = $request->get('message');
            if (empty($message) || !is_string($message)) {
                throw new Exception('Invalid message');
            }

            $message = trim($message);
            if (strlen($message) == 0) {
                throw new Exception('Invalid message');
            }

            /** @var TwwResponse $response */
            $response = $this->service->send($to, $message);
            $content = $response->getContent();

            return $this->json(["message" => $content["message"]], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param AbstractEntity $entity
     * @return void
     * @throws EntityValidationException
     */
    protected function validate(AbstractEntity $entity): void {}

    protected function getService(): void {}
}
