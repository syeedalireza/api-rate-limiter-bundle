<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Strategy;

use Syeedalireza\RateLimiterBundle\Storage\StorageInterface;
use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

/**
 * Fixed Window Algorithm.
 * 
 * Simple and efficient, but can have burst issues at window boundaries.
 */
final class FixedWindowStrategy implements RateLimitStrategyInterface
{
    public function __construct(
        private readonly StorageInterface $storage,
    ) {
    }

    public function check(string $key, int $limit, int $window, int $cost = 1): RateLimitStatus
    {
        $now = time();
        $windowKey = $key . ':' . floor($now / $window);
        
        $currentCount = $this->storage->get($windowKey)['count'] ?? 0;
        $allowed = ($currentCount + $cost) <= $limit;
        
        if ($allowed) {
            $newCount = $currentCount + $cost;
            $this->storage->set($windowKey, ['count' => $newCount], $window);
        }
        
        $resetAt = (floor($now / $window) + 1) * $window;
        
        return new RateLimitStatus(
            allowed: $allowed,
            limit: $limit,
            remaining: max(0, $limit - $currentCount - ($allowed ? $cost : 0)),
            resetAt: (int) $resetAt,
        );
    }

    public function reset(string $key): void
    {
        $this->storage->delete($key);
    }
}
