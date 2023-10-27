<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\Request;

use App\Images\Infrastructure\Api\DTO\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(private readonly RequestTransformer $requestTransformer)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (null === $argument->getType()) {
            return false;
        }

        return (new \ReflectionClass($argument->getType()))->implementsInterface(RequestDTO::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $this->requestTransformer->transform($request);
        $class = $argument->getType();

        yield new $class($request);
    }
}
