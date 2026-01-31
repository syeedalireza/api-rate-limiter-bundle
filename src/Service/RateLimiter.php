<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Service;

use Syeedalireza\RateLimiterBundle\Strategy\RateLimitStrategyInterface;
use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

final class RateLimiter
{
    public function __construct(
        private readonly RateLimitStrategyInterface $strategy,
    ) {
    }

    public function check(string $key, int $limit, int $window, int $cost = 1): RateLimitStatus
    {
        return $this->strategy->check($key, $limit, $window, $cost);
    }

    public function reset(string $key): void
    {
        $this->strategy->reset($key);
    }
}
