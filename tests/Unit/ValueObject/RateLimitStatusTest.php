<?php

declare(strict_types=1);

namespace Syeedalireza\RateLimiterBundle\Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use Syeedalireza\RateLimiterBundle\ValueObject\RateLimitStatus;

final class RateLimitStatusTest extends TestCase
{
    public function testStatusCreation(): void
    {
        $status = new RateLimitStatus(
            allowed: true,
            limit: 100,
            remaining: 99,
            resetAt: time() + 3600
        );

        $this->assertTrue($status->isAllowed());
        $this->assertEquals(100, $status->limit);
        $this->assertEquals(99, $status->remaining);
    }

    public function testRetryAfter(): void
    {
        $resetAt = time() + 300;
        $status = new RateLimitStatus(false, 100, 0, $resetAt);

        $this->assertGreaterThan(0, $status->getRetryAfter());
        $this->assertLessThanOrEqual(300, $status->getRetryAfter());
    }

    public function testToArray(): void
    {
        $status = new RateLimitStatus(true, 100, 50, time() + 3600);
        $array = $status->toArray();

        $this->assertArrayHasKey('allowed', $array);
        $this->assertArrayHasKey('limit', $array);
        $this->assertArrayHasKey('remaining', $array);
        $this->assertArrayHasKey('reset_at', $array);
    }
}
