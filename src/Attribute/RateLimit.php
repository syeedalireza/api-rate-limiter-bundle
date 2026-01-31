<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class RateLimit
{
    public function __construct(
        public readonly int $limit,
        public readonly int $window,
        public readonly string $algorithm = 'token_bucket',
        public readonly string $key = 'ip',
        public readonly int $cost = 1,
    ) {
    }
}
