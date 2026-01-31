<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\Attribute;

use PHPUnit\Framework\TestCase;
use Syeedalireza\RateLimiterBundle\Attribute\RateLimit;

final class RateLimitTest extends TestCase
{
    public function testAttributeCreation(): void
    {
        $attr = new RateLimit(limit: 100, window: 3600);

        $this->assertEquals(100, $attr->limit);
        $this->assertEquals(3600, $attr->window);
        $this->assertEquals('token_bucket', $attr->algorithm);
        $this->assertEquals('ip', $attr->key);
        $this->assertEquals(1, $attr->cost);
    }

    public function testCustomAlgorithm(): void
    {
        $attr = new RateLimit(
            limit: 50,
            window: 60,
            algorithm: 'sliding_window',
            key: 'user',
            cost: 5
        );

        $this->assertEquals('sliding_window', $attr->algorithm);
        $this->assertEquals('user', $attr->key);
        $this->assertEquals(5, $attr->cost);
    }
}
