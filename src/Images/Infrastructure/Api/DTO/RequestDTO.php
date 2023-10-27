<?php

declare(strict_types=1);

namespace App\Images\Infrastructure\Api\DTO;

use Symfony\Component\HttpFoundation\Request;

interface RequestDTO
{
    public function __construct(Request $request);
}
