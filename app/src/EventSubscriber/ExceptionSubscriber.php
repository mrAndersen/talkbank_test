<?php

namespace App\EventSubscriber;

use App\Exception\PromoException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onExceptionEvent',
        ];
    }

    public function onExceptionEvent(ExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof PromoException) {
            $response = new JsonResponse([
                'error' => [
                    'text' => $exception->getMessage()
                ]
            ]);

            $event->setResponse($response);
        }
    }
}
