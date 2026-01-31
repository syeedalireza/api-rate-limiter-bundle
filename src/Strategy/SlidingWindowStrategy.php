<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Strategy;

use Syeedalireza\RateLimiterBundle\Storage\StorageInterface;
use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

/**
 * Sliding Window Algorithm.
 * 
 * Most accurate algorithm that prevents boundary issues.
 */
final class SlidingWindowStrategy implements RateLimitStrategyInterface
{
    public function __construct(
        private readonly StorageInterface $storage,
    ) {
    }

    public function check(string $key, int $limit, int $window, int $cost = 1): RateLimitStatus
    {
        $now = time();
        $windowStart = $now - $window;
        
        // Get timestamps of requests in current window
        $timestamps = $this->storage->get($key) ?? ['requests' => []];
        
        // Filter out old requests
        $validRequests = array_filter(
            $timestamps['requests'] ?? [],
            fn($ts) => $ts > $windowStart
        );
        
        $currentCount = count($validRequests);
        $allowed = ($currentCount + $cost) <= $limit;
        
        if ($allowed) {
            // Add new request timestamp
            for ($i = 0; $i < $cost; $i++) {
                $validRequests[] = $now;
            }
            
            $this->storage->set($key, ['requests' => $validRequests], $window);
        }
        
        return new RateLimitStatus(
            allowed: $allowed,
            limit: $limit,
            remaining: max(0, $limit - $currentCount - ($allowed ? $cost : 0)),
            resetAt: $now + $window,
        );
    }

    public function reset(string $key): void
    {
        $this->storage->delete($key);
    }
}
