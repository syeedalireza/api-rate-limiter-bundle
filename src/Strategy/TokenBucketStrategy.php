<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Strategy;

use Syeedalireza\RateLimiterBundle\Storage\StorageInterface;
use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

/**
 * Token Bucket Algorithm.
 *
 * Tokens are added at a fixed rate. Each request consumes tokens.
 * Good for allowing bursts while maintaining average rate.
 */
final class TokenBucketStrategy implements RateLimitStrategyInterface
{
    public function __construct(
        private readonly StorageInterface $storage,
    ) {
    }

    public function check(string $key, int $limit, int $window, int $cost = 1): RateLimitStatus
    {
        $now = time();
        $data = $this->storage->get($key);

        if ($data === null) {
            // First request - initialize bucket
            $tokens = $limit - $cost;
            $this->storage->set($key, [
                'tokens' => $tokens,
                'last_refill' => $now,
            ], $window);

            return new RateLimitStatus(
                allowed: $tokens >= 0,
                limit: $limit,
                remaining: max(0, $tokens),
                resetAt: $now + $window,
            );
        }

        // Calculate tokens to add based on elapsed time
        $elapsed = $now - $data['last_refill'];
        $refillRate = $limit / $window;
        $tokensToAdd = (int) floor($elapsed * $refillRate);

        // Current tokens (capped at limit)
        $currentTokens = min($limit, $data['tokens'] + $tokensToAdd);

        // Try to consume tokens
        $newTokens = $currentTokens - $cost;
        $allowed = $newTokens >= 0;

        if ($allowed) {
            $this->storage->set($key, [
                'tokens' => $newTokens,
                'last_refill' => $now,
            ], $window);
        }

        return new RateLimitStatus(
            allowed: $allowed,
            limit: $limit,
            remaining: max(0, $allowed ? $newTokens : $currentTokens),
            resetAt: $now + (int) ceil(($cost - $currentTokens) / $refillRate),
        );
    }

    public function reset(string $key): void
    {
        $this->storage->delete($key);
    }
}
