<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\ValueObject;

/**
 * Rate limit check result.
 */
final readonly class RateLimitStatus
{
    public function __construct(
        public bool $allowed,
        public int $limit,
        public int $remaining,
        public int $resetAt,
    ) {
    }

    public function isAllowed(): bool
    {
        return $this->allowed;
    }

    public function getRetryAfter(): int
    {
        return max(0, $this->resetAt - time());
    }

    public function toArray(): array
    {
        return [
            'allowed' => $this->allowed,
            'limit' => $this->limit,
            'remaining' => $this->remaining,
            'reset_at' => $this->resetAt,
            'retry_after' => $this->getRetryAfter(),
        ];
    }
}
