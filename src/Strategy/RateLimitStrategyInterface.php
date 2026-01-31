<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Strategy;

use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

interface RateLimitStrategyInterface
{
    public function check(string $key, int $limit, int $window, int $cost = 1): RateLimitStatus;

    public function reset(string $key): void;
}
