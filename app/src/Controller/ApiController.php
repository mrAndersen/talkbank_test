<?php

namespace App\Controller;

use App\Service\PromocodeService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * Из задания не ясно какие именно методы API нужно сделать, и по какому протоколу работает API (HTTP, GRPC, AMPQ, Binary и так далее)
     * поэтому сделаю получение кода по HTTP через JSON
     *
     * @Route(
     *     "/api/discount/{rawCode}",
     *     methods={"GET"},
     *     requirements={"rawCode"="^[a-zA-Z0-9]*$"},
     *     name="api_get_discount"
     * )
     * @param string $rawCode
     * @param PromocodeService $promocodeService
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function discount(
        string $rawCode,
        PromocodeService $promocodeService
    )
    {
        return new JsonResponse($promocodeService->getPromoCode($rawCode));
    }
}
