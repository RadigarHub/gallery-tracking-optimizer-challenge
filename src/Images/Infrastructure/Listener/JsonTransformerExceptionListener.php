<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Listener;

use App\Images\Domain\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class JsonTransformerExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($e instanceof InvalidArgumentException) {
            $code = Response::HTTP_BAD_REQUEST;
        }
        $response = new JsonResponse(['message' => $e->getMessage()], $code);
        $event->setResponse($response);
    }
}
