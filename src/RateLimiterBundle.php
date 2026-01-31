<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RateLimiterBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
