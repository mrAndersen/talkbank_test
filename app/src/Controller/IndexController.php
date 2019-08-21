<?php

namespace App\Controller;

use App\Classes\PromocodeType\BasicPromoCodeType;
use App\Classes\PromocodeType\NumberPromoCodeType;
use App\Service\PromocodeService;
use DateInterval;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PromocodeService $promocodeService
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function index(
        PromocodeService $promocodeService
    )
    {
        $data = [];

        $data[] = $promocodeService->generatePromoCode(0.20, new BasicPromoCodeType());
        $data[] = $promocodeService->generatePromoCode(
            0.30,
            new NumberPromoCodeType(),
            (new DateTime())->add(new DateInterval("P20D"))
        );

        for ($i = 0; $i < 10; $i++) {
            $data[] = $promocodeService->generatePromoCode(0.05, new BasicPromoCodeType());
        }

        return new JsonResponse($data);
    }
}
